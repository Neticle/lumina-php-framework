<?php

// =============================================================================
//
// Copyright 2013 Neticle
// http://lumina.neticle.com
//
// This file is part of "Lumina/PHP Framework", hereafter referred to as 
// "Lumina".
//
// Lumina is free software: you can redistribute it and/or modify it under the 
// terms of the GNU General Public License as published by the Free Software 
// Foundation, either version 3 of the License, or (at your option) any later
// version.
//
// Lumina is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
// A PARTICULAR PURPOSE. See theGNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License along with
// "Lumina". If not, see <http://www.gnu.org/licenses/>.
//
// =============================================================================

namespace system\core;

/**
 * A centralized event bus capable of having handlers registered to events
 * raised by multiple class instances that might not even exist and the moment.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
class EventBus
{
	/**
	 * An multi-indexed array holding the handler callable reference, indexed
	 * by class and event.
	 *
	 * @type array
	 */
	private $handlers = array();

	/**
	 * Constructor.
	 */
	public function __construct()
	{
	}
	
	/**
	 * Registers a new event handler for the specified event, regardless of
	 * which object it was raised from.
	 *
	 * @param string $event
	 *	The name of the event to register the handler for.
	 *
	 * @param callable $callback
	 *	A callable reference to the function handling this event.
	 *
	 *	The function signature depends on the event it is supposed to handle and
	 *	you should look at the element documentation to find out exactly what
	 *	it is.
	 *
	 *	The callback function should always return TRUE, unless it needs to
	 *	stop propagation or cancel the event, in which case FALSE should be
	 *	returned.
	 *
	 *	Do not forget NULL evaluates to FALSE, meaning that if no return
	 *	statement is specified no more handlers will be called and in some
	 *	cases the event will be canceled.
	 */
	public function on($event, callable $callback)
	{
		return $this->onClassEvent(0, $event, $callback);
	}
	
	/**
	 * Registers a new event handler for the specified event, as long as it
	 * is raised by an object of the specified class.
	 *
	 * @param string $class
	 *	The name of the class to register the event handler for.
	 *
	 * @param string $event
	 *	The name of the event to register the handler for.
	 *
	 * @param callable $callback
	 *	A callable reference to the function handling this event.
	 *
	 *	The function signature depends on the event it is supposed to handle and
	 *	you should look at the element documentation to find out exactly what
	 *	it is.
	 *
	 *	The callback function should always return TRUE, unless it needs to
	 *	stop propagation or cancel the event, in which case FALSE should be
	 *	returned.
	 *
	 *	Do not forget NULL evaluates to FALSE, meaning that if no return
	 *	statement is specified no more handlers will be called and in some
	 *	cases the event will be canceled.
	 */
	public function onClassEvent($class, $event, callable $callback)
	{
		if (!isset($this->handlers[$event]))
		{
			$this->handlers[$event] = array();
		}
		
		$this->handlers[$event][$class][] = $callback;
	}
	
	/**
	 * Raises the specified event through this event bus.
	 *
	 * @param object $source
	 *	The object this event is being raised from.
	 *
	 * @param string $event
	 *	The event to raise.
	 *
	 * @param mixed ...$arguments
	 *	The event arguments.
	 *
	 * @return bool
	 *	Returns FALSE if one of the handlers stopped propagation or canceled
	 *	the event, TRUE otherwise.
	 */
	public function raise($source, $event)
	{
		return $this->raiseArray($source, $event, array_slice(func_get_args(), 2));
	}
	
	/**
	 * Raises the specified event through this event bus.
	 *
	 * @param object $source
	 *	The object this event is being raised from.
	 *
	 * @param string $event
	 *	The event to raise.
	 *
	 * @param array $arguments
	 *	The event arguments.
	 *
	 * @return bool
	 *	Returns FALSE if one of the handlers stopped propagation or canceled
	 *	the event, TRUE otherwise.
	 */
	public function raiseArray($source, $event, array $arguments = null)
	{
		if (isset($this->handlers[$event]))
		{
			$callbackArguments = null;
			
			foreach ($this->handlers[$event] as $class => $handlers)
			{
				if ($class === 0 || is_a($source, $class))
				{
					if(!isset($callbackArguments))
					{
						$callbackArguments = array_merge(array($source), (array) $arguments);
					}
					
					foreach ($handlers as $callback)
					{
						if (!call_user_func_array($callback, $callbackArguments))
						{
							return false;
						}
					}
				}
			}
		}
		
		return true;
	}
}


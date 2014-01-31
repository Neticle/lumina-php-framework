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

use \system\core\Express;
use \system\core\Lumina;

/**
 * Element.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
abstract class Element extends Express
{
	/**
	 * Constructor.
	 *
	 * @param array $configuration
	 *	The construction express configuration array.
	 */
	public function __construct(array $configuration = null)
	{
		parent::__construct($configuration);
	}
	
	/**
	 * Raises the specified event through the Lumina global event bus.
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
	protected function raise($event)
	{
		return $this->raiseArray($event, array_slice(func_get_args(), 1));
	}
	
	/**
	 * Raises the specified event through the Lumina global event bus.
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
	protected function raiseArray($event, array $arguments = null)
	{
		return Lumina::getEventBus()->raiseArray($this, $event, $arguments);
	}
	
	/**
	 * Returns a component instance.
	 *
	 * @throws RuntimeException
	 *	Thrown when: an application is yet to be loaded; the specified
	 *	component is not defined.
	 *
	 * @param string $component
	 *	The name of the component to return.
	 *
	 * @param bool $recursive
	 *	A flag indicating wether or not this component should be checked
	 *	recursively through the parent extensions.
	 *
	 * @return Component
	 *	The component instance.
	 */
	public function getComponent($component, $recursive = true)
	{
		return Lumina::getApplication()->getComponent($component, false);
	}
	
	/**
	 * Checks wether or not a component is defined.
	 *
	 * @throws RuntimeException
	 *	Thrown when an application is yet to be loaded.
	 *
	 * @param string $component
	 *	The name of the component to check.
	 *
	 * @param bool $recursive
	 *	A flag indicating wether or not this component should be checked
	 *	recursively through the parent extensions.
	 *
	 * @return bool
	 *	Returns TRUE if the component is defined, FALSE otherwise.
	 */
	public function hasComponent($component, $recursive = true)
	{
		return Lumina::getApplication()->hasComponent($component, false);
	}
	
	/**
	 * Returns the element class as a string.
	 *
	 * @param bool $namespace
	 *	When set to FALSE the namespace will not be included in the
	 *	returned class name.
	 *
	 * @return string
	 *	The element class name.
	 */
	public function getClass($namespace = true)
	{
		$class = get_class($this);
		
		if ($namespace || ($index = strrpos($class, '\\')) === false)
		{
			return $class;
		}
		
		return substr($class, $index + 1);		
	}
}

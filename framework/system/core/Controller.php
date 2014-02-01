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

use \ReflectionClass as PHPReflectionClass;

use \system\core\Extension;

/**
 * A Context is a special kind of extension that can be used with other
 * extensions capable of having different behaviours depending on the
 * information provided by it.
 *
 * An example of where a Context extension is useful is when creating URLs
 * that are relative to a specific module or controller.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
abstract class Controller extends Context
{
	/**
	 * Constructor.
	 *
	 * @param string $name
	 *	The contextual extension name.
	 *
	 * @param Extension $parent
	 *	The parent extension instance, if any.
	 */
	public final function __construct($name, Context $parent = null, array $configuration = null)
	{
		parent::__construct($name, $parent);
		$this->construct($configuration);
	}
	
	/**
	 * This method is invoked right before the controller dispatch procedure
	 * takes place.
	 *
	 * This method encapsulates the "beforeDispatch" event.
	 *
	 * @param string $action
	 *	The name of the action to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @return bool
	 *	Returns TRUE to continue with the event, FALSE to cancel it.
	 */
	protected function onBeforeDispatch($action, array $parameters = null)
	{
		return $this->raiseArray('beforeDispatch', array($action, $parameters));
	}
	
	/**
	 * This method is invoked right after the controller dispatch procedure
	 * fails, for whatever reason.
	 *
	 * This method encapsulates the "dispatchFailure" event.
	 *
	 * @param string $action
	 *	The name of the action to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @return bool
	 *	This event is not cancelable and the value returned by this function
	 *	will not take any effect.
	 */
	protected function onDispatchFailure($action, array $parameters = null)
	{
		return $this->raiseArray('dispatchFailure', array($action, $parameters));
	}
	
	/**
	 * This method is invoked right after the controller dispatch procedure
	 * fails, due to the action method not being found or meeting the required
	 * visibility rules.
	 *
	 * This method encapsulates the "dispatchActionNotFound" event.
	 *
	 * @param string $action
	 *	The name of the action to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @return bool
	 *	This event is not cancelable and the value returned by this function
	 *	will not take any effect.
	 */
	protected function onDispatchActionNotFound($action, array $parameters = null)
	{
		return $this->raiseArray('dispatchActionNotFound', array($action, $parameters));
	}
	
	/**
	 * This method is invoked right after the controller dispatch procedure
	 * fails, due to the action method not meeting the required
	 * visibility rules.
	 *
	 * This method encapsulates the "dispatchActionNotVisible" event.
	 *
	 * @param string $action
	 *	The name of the action to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @return bool
	 *	This event is not cancelable and the value returned by this function
	 *	will not take any effect.
	 */
	protected function onDispatchActionNotVisible($action, array $parameters = null)
	{
		return $this->raiseArray('dispatchActionNotVisible', array($action, $parameters));
	}
	
	/**
	 * This method is invoked right after all action parameters are bound and
	 * the action method is validated.
	 *
	 * This is the last event to be fired before the action method is invoked.
	 *
	 * This method encapsulates the "dispatch" event.
	 *
	 * @param string $action
	 *	The name of the action to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @param array $arguments
	 *	A numeric array of arguments to use when invoking the action method.
	 *
	 * @return bool
	 *	Returns TRUE to continue with the event, FALSE to cancel it.
	 */
	protected function onDispatch($action, array $parameters = null, array $arguments = null)
	{
		return $this->raiseArray('dispatch', array($action, $parameters, $arguments));
	}
	
	/**
	 * This method is invoked right after all action parameters are bound and
	 * the action method is validated.
	 *
	 * This is the last event to be fired before the action method is invoked.
	 *
	 * This method encapsulates the "afterDispatch" event.
	 *
	 * @param string $action
	 *	The name of the action to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @param array $arguments
	 *	A numeric array of arguments to use when invoking the action method.
	 *
	 * @return bool
	 *	This event is not cancelable and the value returned by this function
	 *	will not take any effect.
	 */
	protected function onAfterDispatch($action, array $parameters = null, array $arguments = null)
	{
		return $this->raiseArray('afterDispatch', array($action, $parameters, $arguments));
	}

	/**
	 * Returns the name of the default method for an action.
	 *
	 * @param string $action
	 *	The action to get the default method for.
	 *
	 * @return string
	 *	The default action method name.
	 */
	protected function getDefaultActionMethod($action)
	{		
		return 'action' . ucfirst($action);
	}
	
	/**
	 * Dispatches the current request.
	 *
	 * @param string $action
	 *	The action to dispatch the request to.
	 *
	 * @param array $parameters
	 *	An associative array defining the action method parameters,
	 *	indexed by name.
	 *
	 *	This is usually the value of {$_GET}, which represents the query
	 *	string as a multidimensional associative array.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public final function dispatch($action, array $parameters = null)
	{
		if (!$this->isInitialized())
		{
			$this->initialize();
		}
		
		// Normalize the action name.
		$action = str_replace(' ', '', 
			lcfirst(ucwords(str_replace(array('_', '-'), ' ', $action)))
		);
		
		if ($this->onBeforeDispatch($action, $parameters))
		{
			$class = new PHPReflectionClass($this);
			$method = $this->getDefaultActionMethod($action);
			
			if ($class->hasMethod($method))
			{
				$method = $class->getMethod($method);
				
				if ($method->isPublic() && !$method->isStatic())
				{
					$filter = array();
					$arguments = array();
					$success = true;
					
					foreach ($method->getParameters() as $parameter)
					{
						$name = $parameter->getName();
						
						if (isset($parameters[$name]))
						{
							$arguments[] = $parameters[$name];
							$filter[$name] = $parameters[$name];
						}
						
						else if ($parameter->isOptional())
						{
							$arguments[] = $parameter->getDefaultValue();
							$filter[$name] = $parameter->getDefaultValue();
						}
						
						else
						{
							$success = false;
							break;
						}
					}
					
					if ($success)
					{
						if ($this->onDispatch($action, $filter, $arguments))
						{
							$method->invokeArgs($this, $arguments);
							$this->onAfterDispatch($action, $filter, $arguments);
							return true;
						}
					}
				}
				else
				{
					$this->onDispatchActionNotVisible($action, $parameters);
					$this->onDispatchActionNotFound($action, $parameters);
				}
			}
			else
			{
				$this->onDispatchActionNotFound($action, $parameters);
			}
		}
		
		$this->onDispatchFailure($action, $parameters);
		return false;
	}
}


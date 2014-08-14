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

namespace system\base;

use \system\base\Module;
use \system\core\Context;
use \system\core\View;

/**
 * A Context is a special kind of extension that can be used with other
 * extensions capable of having different behaviours depending on the
 * information provided by it.
 *
 * An example of where a Context extension is useful is when creating URLs
 * that are relative to a specific module or controller.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
abstract class Controller extends Context
{
	/**
	 * The controller views path.
	 *
	 * @type string
	 */
	private $viewsPath;
	
	/**
	 * The default action.
	 *
	 * @type string
	 */
	private $defaultAction = 'index';
	
	/**
	 * The name of the action being currently processed, which is defined
	 * right before the action method is invoked and undefined right after
	 * the script execution resumes.
	 *
	 * @type string
	 */
	private $currentAction;

	/**
	 * Constructor.
	 *
	 * @param string $name
	 *	The contextual extension name.
	 *
	 * @param Extension $parent
	 *	The parent extension instance, if any.
	 */
	public final function __construct($name, Module $parent = null, array $configuration = null)
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
	 *	Returns TRUE.
	 */
	protected function onDispatchFailure($action, array $parameters = null)
	{
		$this->raiseArray('dispatchFailure', array($action, $parameters));
		return true;
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
	 *	Returns TRUE.
	 */
	protected function onDispatchActionNotFound($action, array $parameters = null)
	{
		$this->raiseArray('dispatchActionNotFound', array($action, $parameters));
		return true;
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
	 *	Returns TRUE.
	 */
	protected function onDispatchActionNotVisible($action, array $parameters = null)
	{
		$this->raiseArray('dispatchActionNotVisible', array($action, $parameters));
		return true;
	}
	
	/**
	 * This method is invoked right after the controller dispatch procedure
	 * fails binding the provided parameters.
	 *
	 * This method encapsulates the "dispatchActionBindFailure" event.
	 *
	 * @param string $action
	 *	The name of the action to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @return bool
	 *	Returns TRUE.
	 */
	protected function onDispatchActionBindFailure($action, array $parameters = null)
	{
		$this->raiseArray('dispatchActionBindFailure', array($action, $parameters));
		return true;
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
	 *	Returns TRUE.
	 */
	protected function onAfterDispatch($action, array $parameters = null, array $arguments = null)
	{
		$this->raiseArray('afterDispatch', array($action, $parameters, $arguments));
		return true;
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
	 * Returns the parent module path.
	 *
	 * @return string
	 *	The parent module path.
	 */
	public function getModulePath()
	{
		return $this->getParent()->getPath();
	}
	
	/**
	 * Defines the path to the controller layouts folder.
	 *
	 * @param string $layoutsPath
	 *	An alias resolving to the controller folder, relative
	 *	to the module path.
	 */
	protected final function setLayoutsPath($layoutsPath)
	{
		$this->layoutsPath = Lumina::getAliasPath($layoutsPath, null, $this->getModulePath());
	}
	
	/**
	 * Returns the controller layouts path.
	 *
	 * @return string
	 *	The controller layouts path.
	 */
	public final function getLayoutsPath()
	{
		if (!isset($this->layoutsPath))
		{
			return $this->getParent()->getLayoutsPath();
		}
		
		return $this->layoutsPath;
	}
	
	/**
	 * Defines the path to the module views folder.
	 *
	 * @param string $viewsPath
	 *	An alias resolving to the views folder, relative to the module path.
	 */
	protected final function setViewsPath($viewsPath)
	{
		$this->viewsPath = Lumina::getAliasPath($viewsPath, null, $this->getModulePath());
	}
	
	/**
	 * Returns the module views path.
	 *
	 * If the path was not previously defined it will be set with the
	 * result of concatenating the module views path with the name of
	 * this controller.
	 *
	 * @return string
	 *	The module views path.
	 */
	public final function getViewsPath()
	{
		if (!isset($this->viewsPath))
		{
			$this->viewsPath = $this->getParent()->getViewsPath()
				. DIRECTORY_SEPARATOR . $this->getName();
		}
		
		return $this->viewsPath;
	}
	
	/**
	 * Defines the layout to be used by this module.
	 *
	 * @param string $layout
	 *	An alias resolving to the layout script, relative to the module
	 *	layouts path.
	 */
	public final function setLayout($layout)
	{
		$this->layout = Lumina::getAliasPath($layout, 'layout.php', $this->getLayoutsPath());
	}
	
	/**
	 * Returns the absolute path to the layout script.
	 *
	 * @return string
	 *	The absolute path to the layout script.
	 */
	public final function getLayoutPath()
	{
		if (!isset($this->layoutPath))
		{
			return $this->getParent()->getLayoutPath(true);
		}
		
		return $this->layoutPath;
	}
	
	/**
	 * Defines the default action for this controller.
	 *
	 * @param string $action
	 *	The default action for this controller.
	 */
	public final function setDefaultAction($action)
	{
		$this->defaultAction = $action;
	}
	
	/**
	 * Returns the default action for this controller.
	 *
	 * @return string
	 *	The default action for this controller.
	 */
	public final function getDefaultAction()
	{
		return $this->defaultAction;
	}
	
	/**
	 * Returns the name of the action being processed, if any.
	 *
	 * The current action is only defined while the action method logic
	 * is being executed and, once it's finished, the value will be undefined.
	 *
	 * @return string
	 *	The current action identifier.
	 */
	public final function getCurrentAction()
	{
		return $this->currentAction;
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
		
		if (!isset($action))
		{
			$action = $this->defaultAction;
		}
		
		// Normalize the action name.
		$action = str_replace(' ', '', 
			lcfirst(ucwords(str_replace(array('_', '-'), ' ', $action)))
		);
		
		if ($this->onBeforeDispatch($action, $parameters))
		{
			$class = new \ReflectionClass($this);
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
							$this->currentAction = $action;
							$method->invokeArgs($this, $arguments);
							$this->currentAction = null;
							
							$this->onAfterDispatch($action, $filter, $arguments);
							return true;
						}
					}
					else
					{
						$this->onDispatchActionBindFailure($action, $parameters);
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


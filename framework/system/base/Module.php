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

use \system\core\Context;
use \system\core\Lumina;
use \system\core\exception\RuntimeException;

/**
 * Defines the base behaviour and events for all Lumina modules.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
class Module extends Context
{
	/**
	 * The module path.
	 */
	private $path;
	
	/**
	 * The module namespace.
	 *
	 * @type array
	 */
	private $namespace;
	
	/**
	 * The controllers express construction and configurarion array.
	 *
	 * @type array
	 */
	private $controllers = array();
	
	/**
	 * The modules express construction and configurarion array.
	 *
	 * @type array
	 */
	private $modules = array();
	
	/**
	 * The module instances, indexed by name.
	 *
	 * @type array
	 */
	private $moduleInstances = array();
	
	/**
	 * The absolute path to the module layouts folder.
	 *
	 * @type string
	 */
	private $layoutsPath;
	
	/**
	 * The absolute path to the module layout.
	 *
	 * @type string
	 */
	private $layoutPath;
	
	/**
	 * The absolute path to the module views folder.
	 *
	 * @type string
	 */
	private $viewsPath;
	
	/**
	 * The default controller name.
	 *
	 * @type string
	 */
	private $defaultController = 'default';

	/**
	 * Constructor.
	 *
	 * @param string $name
	 *	The module name.
	 *
	 * @param string $namespace
	 *	The module namespace.
	 *
	 * @param Context $parent
	 *	The parent context extension.
	 *
	 * @param array $configuration
	 *	The module express configuration array.
	 */
	public final function __construct($name, $namespace, Context $parent = null, array $configuration = null)
	{
		parent::__construct($name, $parent);
		
		$this->namespace = $namespace;
		$this->path = Lumina::getNamespacePath($namespace);
		$this->construct($configuration);
	}
	
	/**
	 * Returns the module namespace.
	 *
	 * @return string
	 *	The module namespace.
	 */
	public final function getNamespace()
	{
		return $this->namespace;
	}
	
	/**
	 * Returns the module path.
	 *
	 * @return string
	 *	The module path.
	 */
	public final function getPath()
	{
		return $this->path;
	}
	
	/**
	 * Defines the path to the module layouts folder.
	 *
	 * @param string $layoutsPath
	 *	An alias resolving to the layouts folder, relative to the module path.
	 */
	protected final function setLayoutsPath($layoutsPath)
	{
		$this->layoutsPath = Lumina::getAliasPath($layoutsPath, null, $this->path);
	}
	
	/**
	 * Returns the module layouts path.
	 *
	 * If the path was not previously defined it will be set with the
	 * result of concatenating the module path with '/layouts'.
	 *
	 * @return string
	 *	The module layouts path.
	 */
	public final function getLayoutsPath()
	{
		if (!isset($this->layoutsPath))
		{
			$this->layoutsPath = $this->path . '/layouts';
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
		$this->viewsPath = Lumina::getAliasPath($viewsPath, null, $this->path);
	}
	
	/**
	 * Returns the module views path.
	 *
	 * If the path was not previously defined it will be set with the
	 * result of concatenating the module path with '/views'.
	 *
	 * @return string
	 *	The module views path.
	 */
	public final function getViewsPath()
	{
		if (!isset($this->viewsPath))
		{
			$this->viewsPath = $this->path . '/views';
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
		$this->layoutPath = Lumina::getAliasPath($layout, 'php', $this->getLayoutsPath());
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
			$module = $this;
			
			while (!$module->isBaseExtension())
			{
				$module = $module->getParent();
				
				if (isset($module->layoutPath))
				{
					return $module->layoutPath;
				}
			}
		}
		
		return $this->layoutPath;
	}
	
	/**
	 * Defines the module default controller.
	 *
	 * @param string $controller
	 *	The module default controller name.
	 */
	public final function setDefaultController($controller)
	{
		$this->defaultController = $controller;
	}
	
	/**
	 * Loads a module based on the specified array.
	 *
	 * @param string $name
	 *	The name of the module to load.
	 *
	 * @param array $module
	 *	The module construction data.
	 *
	 * @return Module
	 *	The module instance.
	 */
	public final function loadModuleFromArray($name, array $configuration = null)
	{
		if (isset($configuration))
		{
			if (isset($configuration['class']))
			{
				$class = $configuration['class'];
				$namespace = Lumina::getClassNamespace($class);
				unset($configuration['class']);
			}
			else
			{
				$class = $this->getDefaultModuleClass($name);
			}
		
			if (isset($configuration['namespace']))
			{
				$namespace = $configuration['namespace'];
				unset($configuration['namespace']);
			}
			else
			{
				$namespace = $this->getDefaultModuleNamespace($name);
			}
		}
		else
		{
			$class = $this->getDefaultModuleClass($name);
			$namespace = $this->getDefaultModuleNamespace($name);
		}
		
		return $this->loadModule($name, $namespace, $class, $configuration);
	}
	
	/**
	 * Loads a module.
	 *
	 * @param string $name
	 *	The module name.
	 *
	 * @param string $namespace
	 *	The module namespace.
	 *
	 * @param string $class
	 *	The module class.
	 *
	 * @param array $configuration
	 *	The module express configuration array.
	 *
	 * @return Module
	 *	The module instance.
	 */
	public final function loadModule($name, $namespace, $class, array $configuration)
	{
		return $this->moduleInstances[$name] = new $class($name, $namespace, $this, $configuration);
	}
	
	/**
	 * Loads a controller based on the specified array.
	 *
	 * @param string $name
	 *	The name of the controller to load.
	 *
	 * @param array $controller
	 *	The controller construction data.
	 *
	 * @return Controller
	 *	The controller instance.
	 */
	public final function loadControllerFromArray($name, array $configuration)
	{
		if (isset($configuration['class']))
		{
			$class = $configuration['class'];
			unset($configuration['class']);
		}
		else
		{
			throw new RuntimeException('Controller "' . $name . '" class not defined.');
		}
		
		return $this->loadModule($name, $class, $configuration);
	}
	
	/**
	 * Loads a controller.
	 *
	 * @param string $name
	 *	The controller name.
	 *
	 * @param string $class
	 *	The controller class.
	 *
	 * @param array $configuration
	 *	The controller express configuration array.
	 *
	 * @return Controller
	 *	The controller instance.
	 */
	public final function loadController($name, $class, array $configuration = null)
	{
		return $this->moduleInstances[$name] =
			new $class($name, $this, $configuration);
	}
	
	/**
	 * Checks wether or not a child module is defined.
	 *
	 * @param string $name
	 *	The name of the module to verify.
	 *
	 * @return bool
	 *	Returns TRUE if the module is defined, FALSE otherwise.
	 */
	public final function hasModule($name)
	{
		return isset($this->moduleInstances[$name]) ||
			array_key_exists($name, $this->modules) ||
			in_array($name, $this->modules);
	}
	
	/**
	 * Returns a module instance.
	 *
	 * @param string $name
	 *	The module name.
	 *
	 * @param string $class
	 *	The module class.
	 *
	 * @param array $configuration
	 *	The module express configuration array.
	 *
	 * @return Module
	 *	The module instance.
	 */
	public final function getModule($name, $initialize = true)
	{
		if (isset($this->moduleInstances[$name]))
		{
			$instance = $this->moduleInstances[$name];
		}		
		else
		{
			throw new RuntimeException('Module "' . $name . '" is not defined.');
		}
		
		if ($initialize && !$instance->isInitialized())
		{
			$instance->initialize();
		}
		
		return $instance;
	}
	
	/**
	 * Defines the child modules construction and configuration data.
	 *
	 * @param array $modules
	 *	The child modules construction and configuration data.
	 */
	public final function setModules(array $modules, $merge = true)
	{
		$collection = array();
		
		foreach ($modules as $name => $configuration)
		{
			if (is_string($configuration))
			{
				if (!isset($collection[$configuration]))
				{
					$collection[$configuration] = array();
				}
				
				continue;
			}
			
			$collection[$name] = $configuration;
		}
		
		$this->modules = $merge ?
			array_replace_recursive($this->modules, $collection) : $collection;
		
	}
	
	/**
	 * Returns a flag indicating wether or not the specified controller
	 * is defined or it's default class exists.
	 *
	 * @param string $name
	 *	The name of the controller to be verified.
	 *
	 * @return bool
	 *	Returns TRUE if the controller exists, FALSE otherwise.
	 */
	public final function hasController($name)
	{
		if (isset($this->controllers[$name]))
		{
			return true;
		}
		
		return Lumina::classExists($this->getDefaultControllerClass($name));
	}
	
	/**
	 * Returns a controller instance.
	 *
	 * @param string $name
	 *	The controller name.
	 *
	 * @param string $class
	 *	The controller class.
	 *
	 * @param array $configuration
	 *	The controller express configuration array.
	 *
	 * @return Module
	 *	The controller instance.
	 */
	public final function getController($name, $initialize = true)
	{
		if (isset($this->controllerInstances[$name]))
		{
			$instance = $this->controllerInstances[$name];
		}
		
		else if (isset($this->controllers[$name]))
		{
			$instance = $this->loadControllerFromArray($name, $this->controller[$name]);
		}
		
		else
		{
			$class = $this->getDefaultControllerClass($name);
			$instance = $this->loadController($name, $class, null);
		}
		
		if ($initialize && !$instance->isInitialized())
		{
			$instance->initialize();
		}
		
		return $instance;
	}
	
	/**
	 * Dispatches the request recursively through the child module
	 * and controllers.
	 *
	 * @param string $route
	 *	The route to dispatch to, resolving to 
	 */
	public final function dispatch($route, array $parameters = null)
	{
		$tokens = preg_split('/(\s*\/\s*)/', $route, -1, PREG_SPLIT_NO_EMPTY);
		$length = count($tokens);
		$module = $this;
		
		do
		{
			$token = (--$length < 0) ?
				$this->defaultController : array_shift($tokens);
			
			if ($length < 2 && $module->hasController($token))
			{
				$action = $length > 0 ? 
					$tokens[0] : null;
				
				$controller = $module->getController($token, true);
				
				if ($this->onDispatch($controller, $action, $parameters))
				{
					if ($controller->dispatch($action, $parameters))
					{
						$this->onAfterDispatch($controller, $action, $parameters);
						return true;
					}
				}
			}
			
			else if ($module->hasModule($token))
			{
				$module = $module->getModule($token, true);
				continue;
			}
			
			break;
			
		} while (true);
		
		$this->onDispatchFailure($route, $parameters);
		return false;		
	}
	
	/**
	 * Returns the default class for a controller.
	 *
	 * @param string $name
	 *	The name of the controller to get the default class for.
	 *
	 * @return string
	 *	The controller default class.
	 */
	protected function getDefaultControllerClass($name)
	{
		$name = str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', $name)));
		return $this->namespace . '\\controllers\\' . $name . 'Controller';
	}
	
	/**
	 * Returns the default class for a module.
	 *
	 * @param string $name
	 *	The name of the module to get the default class for.
	 *
	 * @return string
	 *	The module default class.
	 */
	protected function getDefaultModuleClass($name)
	{
		return 'system\\base\\Module';
	}
	
	/**
	 * Returns the default namespace for a module.
	 *
	 * @param string $name
	 *	The name of the module to get the default namespace for.
	 *
	 * @return string
	 *	The module default namespace.
	 */
	protected function getDefaultModuleNamespace($name)
	{
		return $this->namespace . '\\modules\\' . $name;
	}
	
	/**
	 * This method is invoked during the extension initialization procedure,
	 * before the child extensions get loaded -- when applicable.
	 *
	 * This method encapsulates the "initialize" event.
	 *
	 * @return bool
	 *	Returns TRUE to continue with the event, FALSE to cancel it.
	 */
	protected function onInitialize()
	{
		if (parent::onInitialize())
		{
			if (isset($this->modules))
			{
				foreach ($this->modules as $name => $configuration)
				{
					if (is_string($configuration))
					{
						$name = $configuration;
						$configuration = null;
					}
			
					$this->loadModuleFromArray($name, $configuration);
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * This method is invoked right before the module dispatch procedure
	 * takes place.
	 *
	 * This method encapsulates the "beforeDispatch" event.
	 *
	 * @param string $route
	 *	The route to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @return bool
	 *	Returns TRUE to continue with the event, FALSE to cancel it.
	 */
	protected function onBeforeDispatch($route, array $parameters = null)
	{
		return $this->raiseArray('beforeDispatch', array($route, $parameters));
	}
	
	/**
	 * This method is invoked right after the module dispatch procedure
	 * fails, for whatever reason.
	 *
	 * This method encapsulates the "dispatchFailure" event.
	 *
	 * @param string $route
	 *	The route to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @return bool
	 *	Returns TRUE.
	 */
	protected function onDispatchFailure($route, array $parameters = null)
	{
		$this->raiseArray('dispatchFailure', array($route, $parameters));
		return true;
	}
	
	/**
	 * This method is invoked right before the request is dispatched to
	 * a child controller instance, which may or may not belong to this
	 * module.
	 *
	 * @param Controller $controller
	 *	The instance of the controller to dispatch to.
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
	protected function onDispatch(Controller $controller, $action, array $parameters = null)
	{
		return $this->raiseArray('dispatch', array($controller, $action, $parameters));
	}
	
	/**
	 * This method is invoked right after the request is dispatched to
	 * a child controller instance, which may or may not belong to this
	 * module.
	 *
	 * @param Controller $controller
	 *	The instance of the controller to dispatch to.
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
	protected function onAfterDispatch(Controller $controller, $action, array $parameters = null)
	{
		return $this->raiseArray('afterDispatch', array($controller, $action, $parameters));
	}
}


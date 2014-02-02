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
	public final function loadModuleFromArray($name, array $configuration)
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
		
		else if (!isset($configuration['namespace']))
		{
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
	
	public final function hasModule($name)
	{
		return isset($this->modules[$name]);
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
		
		else if (isset($this->modules[$name]))
		{
			$instance = $this->loadModuleFromArray($name, $this->modules[$name]);
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
				
				return $module->getController($token, true)
					->dispatch($action, $parameters);
			}
			
			else if ($module->hasModule($token))
			{
				$module = $module->getModule($token, true);
				continue;
			}
			
			break;
			
		} while (true);
			
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
}


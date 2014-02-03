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

use \system\base\Module;
use \system\core\Element;
use \system\core\Lumina;
use \system\core\exception\RuntimeException;

/**
 * Defines the base behaviour and events for all Lumina extensions.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
abstract class Extension extends Element
{
	/**
	 * The parent extension.
	 *
	 * @type Extension
	 */
	private $parent;
	
	/**
	 * The extension components construction and configuration data, indexed
	 * by name.
	 *
	 * @type array
	 */
	private $components = array();

	/**
	 * Constructor.
	 *
	 * @param Extension $parent
	 *	The parent extension instance, if any.
	 */
	protected function __construct(Extension $parent = null, array $configuration = null)
	{
		parent::__construct($configuration);
		$this->parent = $parent;
	}
	
	/**
	 * Returns the parent extension.
	 *
	 * @return Extension
	 *	The parent extension, or NULL.
	 */
	public final function getParent()
	{
		return $this->parent;
	}
	
	/**
	 * Returns the parent module.
	 *
	 * @return Module
	 *	The parent module, or NULL.
	 */
	public final function getParentModule()
	{
		$parent = $this->parent;
		
		while ($parent && !($parent instanceof Module))
		{
			$parent = $parent->parent;
		}
		
		return $parent;		
	}
	
	/**
	 * Checks wether or not this is a base extension, meaning that it
	 * doesn't have a parent instance.
	 *
	 * An example of a base extension is the Application itself.
	 *
	 * @return bool
	 *	Returns TRUE if this is a base extension, FALSE otherwise.
	 */
	public final function isBaseExtension()
	{
		return !$this->parent;
	}
	
	/**
	 * Defines the extension components configuration.
	 *
	 * @param array $components
	 *	The components express construction and configuration array.
	 *
	 * @param bool $merge
	 *	A flag indicating wether or not the given settings should be merged
	 *	with the previously defined ones instead of discarding them.
	 */
	public final function setComponents(array $components, $merge = true)
	{
		$this->components = $merge ?
			array_replace_recursive($this->components, $components) :
			$components;
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
	public final function hasComponent($name, $recursive = true)
	{
		if (isset($this->components[$name]))
		{
			return true;
		}
		
		if ($recursive && isset($this->parent))
		{
			return $this->parent->hasComponent($name, true);
		}
		
		return false;
	}
	
	/**
	 * Returns a component instance.
	 *
	 * @throws RuntimeException
	 *	Thrown when: the component does not exist; the component configuration
	 *	settings are invalid; the component fails to construct;
	 *
	 * @param string $name
	 *	The name of the component to return.
	 *
	 * @param bool $initialize
	 *	When set to TRUE the component will be initialized -- unless it already
	 *	was -- before being returned.
	 *
	 * @param bool $recursive
	 *	When set to TRUE the component will be returned from the parent
	 *	extensions if it's not explicitly defined for this extension.
	 *
	 * @return Component
	 *	Returns the component instance.
	 */
	public function getComponent($name, $initialize = true, $recursive = true)
	{
		// Get a cached instance
		if (isset($this->componentInstances[$name]))
		{
			$instance = $this->componentInstances[$name];
		}
		
		// Load an available component
		else if (isset($this->components[$name]))
		{
			$component = $this->components[$name];
			$instance = $this->loadComponentFromArray($name, $component);
		}
		
		// Check the parent extensions
		else if ($recursive && isset($this->parent))
		{
			$instance = $this->parent->getComponent($name, true);
			$this->componentInstances[$name] = $instance;
		}
		else
		{
			throw new RuntimeException('Component "' . $name . '" is not defined.');
		}
		
		// Initialize it
		if ($initialize && !$instance->isInitialized)
		{
			$instance->initialize();
		}
		
		return $instance;
	}
	
	/**
	 * Loads a component based on the specified array.
	 *
	 * @param string $name
	 *	The name of the component to load.
	 *
	 * @param array $component
	 *	The component construction data.
	 *
	 * @return Component
	 *	The component instance.
	 */
	public function loadComponentFromArray($name, array $component)
	{
		// Get the component class
		if (isset($component['class']))
		{
			$class = $component['class'];
			unset($component['class']);
		}
		else
		{
			throw new RuntimeException('Component "' . $name . '" is not defined.');
		}
		
		return $this->loadComponent($name, $class, $component);
	}
	
	/**
	 * Loads a component.
	 *
	 * @param string $name
	 *	The component name.
	 *
	 * @param string $class
	 *	The component class.
	 *
	 * @param array $configuration
	 *	The component express configuration array.
	 */
	public function loadComponent($name, $class, array $configuration = null)
	{
		return $this->componentInstances[$name] = 
			new $class($this, $configuration);
	}

}


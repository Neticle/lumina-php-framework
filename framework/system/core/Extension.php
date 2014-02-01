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
	 * The extension initialization state.
	 *
	 * @type bool
	 */
	private $isInitialized = false;

	/**
	 * Constructor.
	 *
	 * @param Extension $parent
	 *	The parent extension instance, if any.
	 */
	protected function __construct(Extension $parent = null)
	{
		parent::__construct(null);
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
	 * Starts the extension construction procedure.
	 *
	 * This method should be called from the actual class constructor and
	 * must only be called twice. Failing to do this will result in
	 * undefined behaviour.
	 *
	 * You should never have to call this method unless you are extending
	 * the Extension class directly.
	 *
	 * @throws RuntimeException
	 *	Thrown if one of the construction events is canceled.
	 */
	protected final function construct(array $configuration = null)
	{
		if ($this->onConstruction())
		{
			if (isset($configuration))
			{
				$this->configure($configuration);
			}
		
			if ($this->onAfterConstruction())
			{
				return;
			}
		}
		
		throw new RuntimeException('Extension construction was interrupted.');
	}
	
	/**
	 * Starts the extension initialization procedure.
	 *
	 * @throws RuntimeException
	 *	Thrown if one of the construction events is canceled.
	 */
	public final function initialize()
	{		
		if ($this->onInitialize())
		{
			foreach ($this->components as $name => $component)
			{
				if (isset($component['preload']))
				{
					unset($component['preload']);
					$this->loadComponentFromArray($name, $component);
				}
			}
		
			if ($this->onAfterInitialize())
			{
				$this->isInitialized = true;
				return;
			}
		}
		
		throw new RuntimeException('Extension initialization was interrupted.');
	}
	
	/**
	 * Checks the extension initialization state.
	 *
	 * @return bool
	 *	Returns TRUE if the extension is initialized, FALSE otherwise.
	 */
	public final function isInitialized()
	{
		return $this->isInitialized;
	}
	
	/**
	 * This method is invoked during the extension construction procedure,
	 * before the configuration takes place.
	 *
	 * This method encapsulates the "construction" event.
	 *
	 * @return bool
	 *	Returns TRUE to continue with the event, FALSE to cancel it.
	 */
	protected function onConstruction()
	{
		return $this->raiseArray('construction');
	}
	
	/**
	 * This method is invoked during the extension construction procedure,
	 * after the configuration takes place.
	 *
	 * This method encapsulates the "afterConstruction" event.
	 *
	 * @return bool
	 *	Returns TRUE to continue with the event, FALSE to cancel it.
	 */
	protected function onAfterConstruction()
	{
		return $this->raiseArray('afterConstruction');
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
		return $this->raiseArray('initialize');
	}
	
	/**
	 * This method is invoked after the extension initialization procedure and
	 * by the time that happens this instance should be ready to use.
	 *
	 * This method encapsulates the "initialize" event.
	 *
	 * @return bool
	 *	Returns TRUE to continue with the event, FALSE to cancel it.
	 */
	protected function onAfterInitialize()
	{
		return $this->raiseArray('afterInitialize');
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


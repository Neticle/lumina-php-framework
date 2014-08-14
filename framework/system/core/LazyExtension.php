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
use \system\core\Extension;
use \system\core\Lumina;
use \system\core\exception\RuntimeException;

/**
 * A Lazy Extension has the ability to be constructed and initialized at
 * separate times.
 *
 * This behavior is usually required by heavier extensions so that they don't
 * cripple the overall performance due to having to run excessive logic during
 * each request.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
abstract class LazyExtension extends Extension
{
	/**
	 * The extension initialization state.
	 *
	 * @type bool
	 */
	private $isInitialized = false;
	
	/**
	 * An array of component names to pre initialize
	 *
	 * @type bool
	 */
	private $preload;

	/**
	 * Constructor.
	 *
	 * @param Extension $parent
	 *	The parent extension instance, if any.
	 */
	protected function __construct(Extension $parent = null)
	{
		parent::__construct($parent);
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
			if (isset($this->initialize))
			{
				foreach ($this->preload as $component)
				{
					$this->getComponent($component, true);
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
	 * Defines the components to be initialized during this extension
	 * initialization.
	 *
	 * @param string[] $initialize
	 *	The names of the components to pre-initialize.
	 */
	public final function setPreload(array $components)
	{
		$this->preload = $components;
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
}


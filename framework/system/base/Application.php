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

/**
 * Application.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class Application extends Module
{
	
	/**
	 * The currently active application context, which can be used to resolve
	 * contextual routes.
	 *
	 * @type Context
	 */
	private $context;

	/**
	 * This method is invoked during the application construction procedure,
	 * before the configuration takes place.
	 *
	 * This method encapsulates the "construction" event.
	 *
	 * @return bool
	 *	Returns TRUE to continue with the event, FALSE to cancel it.
	 */
	protected function onConstruction()
	{
		if (parent::onConstruction())
		{		
			// Register the core components
			$this->setComponents
			(
				
				[
					// Handles all interactions between the application elements
					// and a SQL database.
					'database' => 
					[
						'class' => 'system\\sql\\Connection',
						'driver' => 'mysql'
					],
				
					// Handles translation of messages and number formating for
					// multi-locale application.
					'dictionary' => 
					[
						'class' => 'system\\i18n\\dictionary\\StaticDictionary',
						'locale' => 'en_GB'
					],
					
					// Manages cached entries in order to avoid running further
					// expensive deterministic procedures in future requests.
					'cache' => 
					[
						'class' => 'system\\cache\\DefaultCache'
					]
				
				]
			);
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Defines or updates the application context.
	 *
	 * @param Context $context
	 *	The application context, or NULL to remove.
	 */
	public final function setContext(Context $context = null)
	{
		$this->context = $context;
	}
	
	/**
	 * Returns the application context.
	 *
	 * @return Context
	 *	The application context which, if not defined, is the application
	 *	itself.
	 */
	public final function getContext()
	{
		if (isset($this->context))
		{
			return $this->context;
		}
		
		return $this;
	}
}


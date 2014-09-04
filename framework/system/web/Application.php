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

namespace system\web;

use \system\web\asset\AssetManager;
use \system\web\exception\HttpException;

/**
 * Application.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class Application extends \system\base\Application
{
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
					// Manages any published assets and allows the application
					// elements to publish their own from a protected directory
					// into the application web root.
					'assetManager' => 
					[
						'class' => 'system\\web\\asset\\AssetManager'
					],
					
					// Holds the meta data related to the document being served
					// to the client, as well as any registered scripts and
					// styles.
					'document' => 
					[
						'class' => 'system\\web\\Document',
					],
					
					// Creates URLs that link to a specific route and parses
					// the current request into a route .
					'router' => 
					[
						'class' => 'system\\web\\router\\DefaultRouter'
					],
					
					// Handles loading and saving of session data for the
					// current request.
					'session' => 
					[
						'class' => 'system\\web\\session\\DefaultSession'
					]
				]
			);
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns the asset manager component.
	 *
	 * @return AssetManager
	 *	The asset manager component.
	 */
	public final function getAssetManager()
	{
		return $this->getComponent('assetManager');
	}
	
	/**
	 * Returns the document component.
	 *
	 * @return Document
	 *	The document component.
	 */
	public final function getDocument()
	{
		return $this->getComponent('document');
	}
	
	/**
	 * Returns the router component.
	 *
	 * @return Router
	 *	The router component.
	 */
	public final function getRouter()
	{
		return $this->getComponent('router');
	}
	
	/**
	 * Returns the session component.
	 *
	 * @return Session
	 *	The session component.
	 */
	public final function getSession()
	{
		return $this->getSession();
	}
	
	/**
	 * Dispatches the current request based on the information provided by it
	 * and parsed by the router component.
	 *
	 * @throws HttpException
	 *	Thrown when the dispatch procedure fails.
	 */
	public function dispatchRequest()
	{
		$route = $this->getComponent('router')->getRequestRoute();
				
		if (!$this->dispatch($route[0], array_slice($route, 1)))
		{
			throw new HttpException(404, 'Document Not Found');
		}
	}
}


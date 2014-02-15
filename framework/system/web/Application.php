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

use \system\web\exception\HttpException;

/**
 * Application.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web
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
			$this->setComponents(array(
				
				'assetManager' => array(
					'class' => 'system\\web\\asset\\AssetManager'
				),
				
				'document' => array(
					'class' => 'system\\web\\Document',
				),
				
				'router' => array(
					'class' => 'system\\web\\router\\DefaultRouter'
				),
				
				'session' => array(
					'class' => 'system\\web\\session\\DefaultSession'
				)
				
			));
			
			return true;
		}
		
		return false;
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
		
		if (!$this->dispatch($route[0], $route[1]))
		{
			throw new HttpException(404, 'Document Not Found');
		}
	}
}


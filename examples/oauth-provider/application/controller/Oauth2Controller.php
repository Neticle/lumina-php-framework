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

namespace application\controller;

use \system\web\Controller;

/**
 * The OAuth controller.
 * This controller contains the two actions that act as the endpoints for
 * the provider component.
 * 
 * The names of the controller and actions are the same as the defaults the 
 * component routes to. If you want to have different names, you can configure
 * the routes on the component configuration.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class Oauth2Controller extends Controller
{

	public function actionAuthorization()
	{
		$this->getComponent('oauthProvider')
			->handleAuthorizationEndpoint();
	}
	
	public function actionToken()
	{
		$this->getComponent('oauthProvider')
			->handleTokenEndpoint();
	}
}

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

namespace system\web\authentication\oauth\server\flow;

use system\web\Request;
use system\web\Response;

/**
 * Description of ImplicitTokenFlow
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class ImplicitTokenFlow extends AuthorizationFlow
{
    
	private $state;
	
	private $endUser;
	
	private $client;
	
	protected function onBeforeGrant ()
	{
		
	}

	protected function onBeforeValidate ()
	{
		
	}
	
	public function prepare ()
	{
		$this->state = Request::getString('state', $_GET, false, null);
		$this->endUser = $this->getProvider()->getStorage()->fetchCurrentEndUser();
		$this->client = AuthorizationFlow::getRequestingClient($this->getProvider());
	}
	
	public function validate ()
	{
		$this->onBeforeValidate();
		
		if($this->endUser === null) {
			// redirect
			return;
		}
	}
	
	public function grant ()
	{
		$this->onBeforeGrant();
		
		$authServer = $this->getAuthorizationServer();
		
		$token = $authServer->grantImplicitAccessToken($this->endUser, $this->client);

		$this->onBeforeRedirect($token);
		
		$redirectURI = AuthorizationFlow::prepareRedirectionEndpointURI($client->getRedirectionEndpointURI(), array (
			'access_token' => $token->getToken(),
			'token_type' => '',
			//'expires_in' => '3600',
			'state' => $this->state
		), 'fragment');

		Response::setLocation($redirectURI);
	}

}

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

use system\web\authentication\oauth\server\exception\OAuthAuthorizationException;
use system\web\Request;
use system\web\Response;

/**
 * Description of AuthorizationCodeFlow
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class AuthorizationCodeFlow extends AuthorizationFlow
{

	private $state;
	
	private $endUser;
	
	private $client;
		
	protected function onBeforeValidate ()
	{
		
	}
	
	protected function onBeforeGrant ()
	{
		
	}
	
	protected function onBeforeRedirect ($code)
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
			throw new OAuthAuthorizationException
			(
				OAuthAuthorizationException::ERROR_UNAUTHENTICATED_ENDUSER,
				null,
				null
			);
		}
	}
	
	public function grant ()
	{
		$this->onBeforeGrant();
		
		$authServer = $this->getAuthorizationServer();
		
		$authCode = $authServer->grantAuthorizationCode($this->endUser, $this->client);
		
		$this->onBeforeRedirect($authCode);
		
		$redirectURI = AuthorizationFlow::prepareRedirectionEndpointURI($this->client->getRedirectionEndpointURI(), array (
			'code' => $authCode->getCode(),
			'state' => $this->state
		), 'query');

		Response::setLocation($redirectURI);
	}

}

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
 * Description of ClientCredentialsFlow.php
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class ClientCredentialsFlow extends AuthorizationFlow
{
    
	private $httpAuth;
		
	protected function onBeforeGrant ()
	{
		
	}

	protected function onBeforeValidate ()
	{
		
	}

	protected function onBeforeResponse ($token)
	{
		
	}
	
	protected function buildResponseObject ($token)
	{
		$now = new \DateTime('now');

		return array
		(
			'access_token' => $token->getToken(),
			'token_type' => $token->getType(),
			'expires_in' => $token->getExpirationDate()->getTimestamp() - $now->getTimestamp(),
			'refresh_token' => $token->getRefreshToken(),
			
			// this isn't standard on the specification, just provided as an helper
			'expiration_date' => $token->getExpirationDate()->format(\DateTime::W3C)
		);
	}
	
	public function prepare ()
	{
		$this->httpAuth = $this->getHTTPAuthorization();
	}

	public function validate ()
	{
		$this->onBeforeValidate();
		
		if($this->httpAuth === null || $this->httpAuth['type'] !== 'basic')
		{
			throw new OAuthAuthorizationException(
				OAuthAuthorizationException::ERROR_INVALID_REQUEST,
				null,
				null,
				'Client credentials must be passed using HTTP Basic Authorization'
			);
		}
	}

	public function grant ()
	{
		$this->onBeforeGrant();
		
		$token = $this->getAuthorizationServer()->
			grantAccessTokenByClientCredentials($this->httpAuth['credentials']);

		$this->onBeforeResponse($token);
		
		Response::setHeader('Content-Type', 'application/json;charset=UTF-8');
		Response::setHeader('Cache-Control', 'no-store');
		Response::setHeader('Pragma', 'no-cache');

		echo json_encode($this->buildResponseObject($token));
	}
	
}

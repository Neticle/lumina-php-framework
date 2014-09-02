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
use system\web\authentication\oauth\server\role\IClient;
use system\web\authentication\oauth\server\data\IAuthCode;
use system\web\authentication\oauth\server\data\IAccessToken;
use system\web\authentication\oauth\server\exception\OAuthTokenGrantException;

/**
 * Handles the flow for an exchange of an authorization code for an access 
 * token (grant type "authorization_code").
 * 
 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
 * 
 * The authorization server MUST:
 * 
 * - require client authentication for confidential clients or for any client
 * that was issued client credentials.
 * 
 * - authenticate the client if client authentication is included.
 * 
 * - ensure that the authorization was issued to the authenticated confidential
 * client, or if he client is public, ensure that the code was issued to "client_id"
 * in the request.
 *
 * - verify that the authorization code is valid, and
 * 
 * - ensure that the "redirect_uri" parameter is present if the "redirect_uri" 
 * parameter was included in he initial authorization request as described in 
 * Section 4.1.1 and if inclded ensure that their values are identical.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class AccessTokenByCodeFlow extends TokenFlow
{
    
	private $authorizationCode;
	
	private $client;
	
	protected function onBeforeResponse(IAccessToken $token)
	{
		return true;
	}
	
	public function prepare ()
	{
		if(Request::getMethod() !== 'POST')
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_REQUEST,
				null,
				'Invalid request method. Use "POST" instead.'
			);
		}
		
		$client = TokenFlow::getRequestingClient($this->getProvider());
		$authedClient = null;
		
		$httpAuth = $this->getHTTPAuthorization();
		if($httpAuth !== null && $httpAuth['type'] === 'basic') 
		{
			$authedClient = $this->getProvider()->getStorage()
				->fetchClientByCredentials($httpAuth['credentials']);
		}
		
		if($authedClient === null && $client === null)
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_CLIENT
			);
		}
		
		else if($authedClient !== null && $client === null)
		{
			$client = $authedClient;
		}
		
		else if($authedClient === null && $client !== null) 
		{
			if($client->getType() === IClient::TYPE_CONFIDENTIAL ||
				$client->getProfile() === IClient::PROFILE_WEB_APPLICATION)
			{
				throw new OAuthTokenGrantException
				(
					OAuthTokenGrantException::ERROR_INVALID_CLIENT,
					$client,
					'Confidential clients must authenticate using HTTP Basic Authorization.'
				);
			}
		}
		
		else if($authedClient->getIdentifier() !== $client->getIdentifier())
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_CLIENT,
				null,
				'Authentication mismatch. The credentials provided do not match the client_id.'
			);
		}
		
		$this->client = $client;
		
		$code = Request::getString('code', $_POST, false, null);
		
		if($code === null)
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_REQUEST,
				$this->client,
				'The authorization code must be provided using the "code" parameter'
			);
		}
		
		$codeObj = $this->getProvider()->getStorage()->fetchAuthorizationCode ($code);
		
		if($codeObj === null) 
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_GRANT,
				$this->client,
				'The provided authorization code is invalid.'
			);
		}
		
		$this->authorizationCode = $codeObj;
		
		return true;
	}
	
	public function validate ()
	{
		if(!$this->onBeforeValidate())
		{
			return false;
		}
		
		if($this->authorizationCode->getClientId() !== $this->client->getIdentifier())
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_GRANT,
				$this->client,
				'The provided authorization code does not belong to the requesting client.'
			);
		}
		
		if(!$this->authorizationCode->isValid() || 
			$this->authorizationCode->getStatus() !== IAuthCode::STATUS_UNUSED)
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_GRANT,
				$this->client,
				'The provided authorization code is either expired, revoked or already used.'
			);
		}
		
		return true;
	}
	
	protected function buildResponseObject (IAccessToken $token)
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
	
	public function grant ()
	{
		if(!$this->onBeforeGrant())
		{
			return false;
		}
		
		$token = $this->getAuthorizationServer()
			->grantAccessTokenForCode($this->authorizationCode);
		
		if(!$this->onBeforeResponse($token))
		{
			return false;
		}
		
		Response::setHeader('Content-Type', 'application/json;charset=UTF-8');
		Response::setHeader('Cache-Control', 'no-store');
		Response::setHeader('Pragma', 'no-cache');

		echo json_encode($this->buildResponseObject($token));
		
		return true;
	}

}

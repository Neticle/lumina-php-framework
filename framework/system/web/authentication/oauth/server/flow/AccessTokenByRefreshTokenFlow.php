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
 * Handles the flow for an exchange of a refresh token for a new access 
 * token (grant type "refresh_token").
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class AccessTokenByCodeFlow extends TokenFlow
{
    
	private $originalToken;
	
	private $client;
	
	private $refreshToken;
	
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
		
		$refreshToken = Request::getString('refresh_token', $_POST, false, null);
		
		if($refreshToken === null)
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_REQUEST,
				$this->client,
				'The refresh token must be provided using the "refresh_token" parameter'
			);
		}
		
		$this->refreshToken = $refreshToken;
		
		$originalToken = $this->getProvider()->getStorage()
			->fetchAccessTokenByRefreshToken($refreshToken);
				
		if($originalToken === null) 
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_GRANT,
				$this->client,
				'The refresh token is invalid.'
			);
		}
		
		$this->originalToken = $originalToken;
		
		return true;
	}
	
	public function validate ()
	{
		if(!$this->onBeforeValidate())
		{
			return false;
		}
		
		if($this->originalToken->getClientId() !== $this->client->getIdentifier())
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_GRANT,
				$this->client,
				'The original access token does not belong to the requesting client.'
			);
		}
		
		if($this->originalToken->getStatus() !== IAccessToken::STATUS_OK)
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_GRANT,
				$this->client,
				'The provided refresh token has already been used or the original access token has been revoked.'
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
			->refreshAccessToken($this->originalToken);
		
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

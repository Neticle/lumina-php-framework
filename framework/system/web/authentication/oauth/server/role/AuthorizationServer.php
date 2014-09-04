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

namespace system\web\authentication\oauth\server\role;

use DateTime;
use system\core\Express;
use system\web\authentication\oauth\server\data\AccessToken;
use system\web\authentication\oauth\server\data\AuthCode;
use system\web\authentication\oauth\server\data\IAccessToken;
use system\web\authentication\oauth\server\data\IAuthCode;
use system\web\authentication\oauth\server\data\IStorage;
use system\web\authentication\oauth\server\exception\OAuthAuthorizationException;
use system\web\authentication\oauth\server\exception\OAuthTokenGrantException;

/**
 * A simple implementation of the Authorization Server (as specified by the 
 * IAuthorizationServer interface).
 * 
 * This implementation should be enough for most use-cases. However, if you wish
 * to provide your own data classes, your own token generation algorithm, or 
 * perform additional verifications during code/token creation, you can extend
 * this class or create your own implementation of the IAuthorizationServer 
 * interfac instead. 
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 * @since 0.2.0
 */
class AuthorizationServer extends Express implements IAuthorizationServer
{

	/**
	 * The storage instance in use currently.
	 * 
	 * @type IStorage 
	 */
	private $storage;
	
	public function __construct (array $attributes)
	{
		parent::__construct($attributes);
	}
	
	/**
	 * Sets the storage instance to be used with this authorization server.
	 * 
	 * @param IStorage $storage
	 */
	public function setStorage (IStorage $storage)
	{
		$this->storage = $storage;
	}

	/**
	 * Gets the storage instance currently in use by this authorization server.
	 * 
	 * @return IStorage
	 */
	protected function getStorage ()
	{
		return $this->storage;
	}

	/**
	 * Generates a new random string, to be used as a token or code.
	 * 
	 * @param string $salt
	 * 
	 * @param int $length
	 * 
	 * @return string
	 *  The generated string.
	 */
	protected function generateToken ($salt = null, $length = 32)
	{
		// TODO: Implement an actual token generator
		return md5($salt . uniqid('', true));
	}

	/**
	 * Builds a new authorization code object, given a resource owner and client
	 * objects.
	 * 
	 * @param IResourceOwner $owner
	 *  The resource owner ("end-user") that has granted the authorization.
	 * 
	 * @param IClient $client
	 *  The client that is being granted the authorization.
	 * 
	 * @return AuthCode
	 *  The authorization code object newly created.
	 */
	protected function buildAuthorizationCode (IResourceOwner $owner, IClient $client)
	{
		$expiry = new DateTime('now');
		$expiry->modify('+5 minute');

		$code = new AuthCode(array (
			'client' => $client,
			'code' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'expirationDate' => $expiry,
			'owner' => $owner
		));

		return $code;
	}

	/**
	 * Grants a new authorization code, given a resource owner and a client objects.
	 * 
	 * This method will generate and store the authorization code object.
	 * 
	 * @param IResourceOwner $owner
	 *  The resource owner ("end-user") that has granted the authorization.
	 * 
	 * @param IClient $client
	 *  The client that is being granted the authorization.
	 * 
	 * @return AuthCode
	 *  The newly created and stored authorization code object.
	 */
	public final function grantAuthorizationCode (IResourceOwner $owner, IClient $client)
	{
		$code = $this->buildAuthorizationCode($owner, $client);

		$this->getStorage()->storeAuthorizationCode($code);

		return $code;
	}

	/**
	 * Builds a new access token object, being used in an implicit grant, given 
	 * a resource owner and client objects.
	 * 
	 * @param IResourceOwner $owner
	 *  The resource owner ("end-user") that has granted the token.
	 * 
	 * @param IClient $client
	 *  The client that is being granted the token.
	 * 
	 * @return AccessToken
	 *  The authorization code object newly created.
	 */
	protected function buildImplicitAccessToken (IResourceOwner $owner, IClient $client)
	{
		$expiry = new DateTime('now');
		$expiry->modify('+1 hour');

		$code = new AccessToken(array (
			'client' => $client,
			'token' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'expirationDate' => $expiry,
			'owner' => $owner,
			'context' => IAccessToken::CONTEXT_RESOURCE_OWNER_IMPLICIT_ACCESS_TOKEN
		));

		return $code;
	}

	/**
	 * Grants a new access token, being used in an implicit grant, given a 
	 * resource owner and a client objects.
	 * 
	 * This method will generate and store the access token object.
	 * 
	 * @param IResourceOwner $owner
	 *  The resource owner ("end-user") that has granted the token.
	 * 
	 * @param IClient $client
	 *  The client that is being granted the token.
	 * 
	 * @return AccessToken
	 *  The newly created and stored authorization code object.
	 */
	public final function grantImplicitAccessToken (IResourceOwner $owner, IClient $client)
	{
		if ($client->getType() === IClient::TYPE_CONFIDENTIAL || 
			$client->getProfile() == IClient::PROFILE_WEB_APPLICATION)
		{
			
			// Implicit tokens are meant for client-side or native applications,
			// if a client has means for a more secure authorization flow, then
			// that client is required to use the most secure option available to it.
			
			throw new OAuthAuthorizationException (
				OAuthAuthorizationException::ERROR_UNAUTHORIZED_CLIENT,
				null,
				$client,
				'Confidential clients or server-side web applications must use the proper authorization code grant method.'
			);
			
		}
		
		$token = $this->buildImplicitAccessToken($owner, $client);

		$this->getStorage()->storeAccessToken($token);

		return $token;
	}

	protected function buildClientAccessToken (IResourceOwner $owner, IClient $client)
	{
		$expiry = new DateTime('now');
		$expiry->modify('+1 hour');

		$code = new AccessToken(array (
			'client' => $client,
			'token' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'expirationDate' => $expiry,
			'owner' => $owner,
			'context' => IAccessToken::CONTEXT_CLIENT_ACCESS_TOKEN
		));

		return $code;
	}
	
	public final function grantAccessTokenByClientCredentials (array $credentials)
	{
		if(count($credentials) !== 2) {
			throw new OAuthAuthorizationException(
				OAuthAuthorizationException::ERROR_INVALID_REQUEST,
				null,
				'Client credentials are malformed.'
			);
		}
		
		$clientId = $credentials[0];
		$clientSecret = $credentials[1];
		
		$client = $this->getStorage()->fetchClient($clientId);
		
		if($client === null || $client->getSecret() !== $clientSecret)
		{
			throw new OAuthAuthorizationException(
				OAuthAuthorizationException::ERROR_ACCESS_DENIED,
				null,
				'Provided credentials do not match any known client.'
			);
		}
				
		if($client->getType() !== IClient::TYPE_CONFIDENTIAL)
		{
			throw new OAuthAuthorizationException(
				OAuthAuthorizationException::ERROR_UNAUTHORIZED_CLIENT,
				null,
				'Only confidential clients may use this method of authorization.'
			);
		}
		
		$token = $this->buildClientAccessToken ($client);
		
		$this->getStorage()->storeAccessToken ($token);
		
		return $token;
	}

	public function grantByResourceOwnerCredentials (array $credentials, IClient $client = null)
	{
		
	}

	protected function buildAccessToken (IAuthCode $code)
	{
		$expiry = new DateTime('now');
		$expiry->modify('+1 hour');

		$client = $code->getClient();
		$owner = $code->getOwner();
		
		$token = new AccessToken(array (
			'client' => $client,
			'token' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'refreshToken' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'expirationDate' => $expiry,
			'owner' => $owner,
			'context' => IAccessToken::CONTEXT_RESOURCE_OWNER_ACCESS_TOKEN
		));

		return $token;
	}
	
	protected function buildRefreshedAccessToken (IAccessToken $original)
	{
		$expiry = new DateTime('now');
		$expiry->modify('+1 hour');

		$client = $original->getClient();
		$owner = $original->getOwner();
		
		$token = new AccessToken(array (
			'client' => $client,
			'token' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'refreshToken' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'expirationDate' => $expiry,
			'owner' => $owner,
			'context' => IAccessToken::CONTEXT_RESOURCE_OWNER_ACCESS_TOKEN
		));

		return $token;
	}
	
	public function grantAccessTokenForCode (IAuthCode $code)
	{
		$status = $code->getStatus();
		if($status !== IAuthCode::STATUS_UNUSED)
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_GRANT,
				null,
				'The provided authorization code was ' . ($status === IAuthCode::STATUS_REVOKED ? 'revoked' : 'already used')
			);
		}
		
		$token = $this->buildAccessToken($code);
		
		$this->getStorage()->updateAuthCodeStatus($code, IAuthCode::STATUS_USED);
		
		$this->getStorage()->storeAccessToken($token);

		return $token;
	}
	
	public function refreshAccessToken (IAccessToken $original)
	{
		$status = $original->getStatus();
		if($status !== IAccessToken::STATUS_OK)
		{
			throw new OAuthTokenGrantException
			(
				OAuthTokenGrantException::ERROR_INVALID_GRANT,
				null,
				'The original access token was' . ($status === IAccessToken::STATUS_REVOKED ? 'revoked' : 'already refreshed once')
			);
		}
		
		$token = $this->buildRefreshedAccessToken($original);
		
		$this->getStorage()->updateAccessTokenStatus($original, IAccessToken::STATUS_REFRESHED);
		
		$this->getStorage()->storeAccessToken($token);

		return $token;
	}

	public function grantByClientCredentials ()
	{
		
	}

}

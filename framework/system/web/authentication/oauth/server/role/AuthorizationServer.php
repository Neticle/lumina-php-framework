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

use \system\core\Express;
use \system\web\authentication\oauth\server\data\AuthCode;
use \system\web\authentication\oauth\server\data\AccessToken;
use \system\web\authentication\oauth\server\data\IStorage;

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
		$expiry = new \DateTime('now');
		$expiry->modify('+5 minute');

		$code = new AuthCode(array (
			'clientId' => $client->getIdentifier(),
			'code' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'expirationDate' => $expiry,
			'ownerId' => $owner->getIdentifier()
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
		$expiry = new \DateTime('now');
		$expiry->modify('+1 hour');

		$code = new AccessToken(array (
			'clientId' => $client->getIdentifier(),
			'token' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'expirationDate' => $expiry,
			'ownerId' => $owner->getIdentifier()
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
		$token = $this->buildImplicitAccessToken($owner, $client);

		$this->getStorage()->storeAccessToken($token);

		return $token;
	}

	public function grantByClientCredentials ()
	{
		
	}

	public function grantByResourceOwnerCredentials (array $credentials, IClient $client = null)
	{
		
	}

}

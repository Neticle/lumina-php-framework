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

namespace application\oauth;

use application\model\User;
use DateTime;
use system\core\Element;
use system\sql\Connection;
use system\sql\Reader;
use system\web\authentication\oauth\server\data\AccessToken;
use system\web\authentication\oauth\server\data\AuthCode;
use system\web\authentication\oauth\server\data\IAccessToken;
use system\web\authentication\oauth\server\data\IAuthCode;
use system\web\authentication\oauth\server\data\IStorage;
use system\web\authentication\oauth\server\role\IClient;
use system\web\authentication\oauth\server\role\Client;
use system\web\authentication\oauth\server\exception\OAuthStorageException;

/**
 * An example Storage class for the OAuth 2.0 Provider using SQL.
 * 
 * For the sake of simplicity, we'll be building the query statements directly
 * and making use of the default data objects.
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class SqlStorage extends Element implements IStorage
{
    
	/**
	 * Contains the list of allowed clients.
	 * 
	 * This is hardcoded, you might want to store this information in the 
	 * database instead.
	 * 
	 * @type array
	 */
	protected static $clients = array(
		'ID_CLIENT_1' => array(
			'identifier' => 'ID_CLIENT_1',
			'secret' => 'IM_A_SECRET_TOKEN1',
			'type' => IClient::TYPE_CONFIDENTIAL,
			'profile' => IClient::PROFILE_WEB_APPLICATION,
			'redirectionEndpointURI' => 'https://thirdpartyapplication1/oauth/callback/'
		),

		'ID_CLIENT_2' => array(
			'identifier' => 'ID_CLIENT_2',
			'type' => IClient::TYPE_PUBLIC,
			'profile' => IClient::PROFILE_UA_BASED_APPLICATION,
			'redirectionEndpointURI' => 'https://thirdpartyapplication2/'
		),

		'ID_CLIENT_3' => array(
			'identifier' => 'ID_CLIENT_3',
			'type' => IClient::TYPE_PUBLIC,
			'profile' => IClient::PROFILE_NATIVE_APPLICATION,
			'redirectionEndpointURI' => 'https://thirdpartyapplication3/'
		)
	);
	
	/**
	 * Returns the database connection object.
	 * 
	 * @return Connection
	 */
	protected function getDatabase () 
	{
		return $this->getComponent('database');
	}
	
	/**
	 * Fetches an access token object, given it's representation as a string.
	 * 
	 * @param string $token
	 *  The access token.
	 * 
	 * @return AccessToken|null
	 *  The access token object, if any.
	 */
	public function fetchAccessToken ($token, $context)
	{
		$reader = $this->getDatabase()->select('oauth_access_token', array(
			'condition' => 'oauth_access_token.token = :token',
			'parameters' => array(':token' => $token)
		));
		
		if(($result = $reader->fetch(Reader::FETCH_ASSOC, true)) !== false)
		{
			return new AccessToken(array(
				'token' => $result['token'],
				'owner' => $result['id_user'],
				'client' => $result['id_client'],
				'expirationDate' => new DateTime($result['expiration_date']),
				'contextType' => intval($result['context_type']),
				'status' => intval($result['status']),
				'refreshToken' => $result['refresh_token']
			));
		}
		
		return null;
	}

	/**
	 * Fetches an authorization code object, given it's representation as a string.
	 * 
	 * @param string $code
	 *  The authorization code.
	 *
	 * @return AuthCode|null
	 *  The authorization code object, if any.
	 */
	public function fetchAuthorizationCode ($code, $returnOnlyValid = false)
	{
		$reader = $this->getDatabase()->select('oauth_authorization_code', array(
			'condition' => 'oauth_authorization_code.code = :code',
			'parameters' => array(':code' => $code)
		));
		
		if(($result = $reader->fetch(Reader::FETCH_ASSOC, true)) !== false)
		{
			return new AuthCode(array(
				'code' => $result['code'],
				'owner' => $result['id_user'],
				'client' => $result['id_client'],
				'expirationDate' => new DateTime($result['expiration_date']),
				'status' => intval($result['status'])
			));
		}
		
		return null;
	}

	/**
	 * Fetches a client matching the given client identifier.
	 * 
	 * @param string $clientId
	 *  The client identifier.
	 * 
	 * @return Client|null
	 *  The matching client, if any.
	 */
	public function fetchClient ($clientId)
	{
		if(isset(self::$clients[$clientId]))
		{
			return new Client(self::$clients[$clientId]);
		}
		
		return null;
	}

	/**
	 * Fetches a client matching a given set of credentials.
	 * 
	 * @param array $credentials
	 *  An array containing two elements, the first being the client identifier
	 *  and the second being the client secret.
	 * 
	 * @return Client|null
	 *  The matching client, if any.
	 */
	public function fetchClientByCredentials (array $credentials)
	{
		$client = $this->fetchClient($credentials[0]);
		
		if($client === null)
		{
			return null;
		}
		
		$secret = $client->getSecret();
		
		return ($secret !== null && $secret === $credentials[1]) ? 
			$client : null;
	}

	/**
	 * Fetches the currently authenticated user.
	 * 
	 * @return User|null
	 *  The user currently authenticated, if any.
	 */
	public function fetchCurrentEndUser ()
	{
		$userId = $this->getComponent('session')->read('id_user');
		
		if($userId !== null)
		{
			return $this->fetchResourceOwner($userId);
		}
		
		return null;
	}

	/**
	 * Fetches a resource owner (in the case of this example, an User), given
	 * it's identifier.
	 * 
	 * @param string $identifier
	 * 
	 * @return User|null
	 *  The matching user, if any.
	 */
	public function fetchResourceOwner ($identifier)
	{
		return User::model()->findByAttributes(array('id' => $identifier));
	}

	/**
	 * Stores an access token object.
	 * 
	 * @param IAccessToken $token
	 */
	public function storeAccessToken (IAccessToken $token)
	{
		if($token->getContextType() === IAccessToken::CONTEXT_CLIENT_ACCESS_TOKEN)
		{
			throw new OAuthStorageException
			(
				OAuthStorageException::ERROR_UNIMPLEMENTED_METHOD,
				'Granting of client access tokens is not allowed'
			);
		}
		
		$this->getDatabase()->insert('oauth_access_token', array(
			'token' => $token->getToken(),
			'code' => $token->getOriginatingCode(),
			'id_user' => $token->getOwner(true),
			'id_client' => $token->getClient(true),
			'expiration_date' => $token->getExpirationDate()->format('Y-m-d H:i:s'),
			'context_type' => $token->getContextType(),
			'status' => $token->getStatus(),
			'refresh_token' => $token->getRefreshToken()
		));
	}

	/**
	 * Stores an authorization code object.
	 * 
	 * @param IAuthCode $code
	 */
	public function storeAuthorizationCode (IAuthCode $code)
	{
		$this->getDatabase()->insert('oauth_authorization_code', array(
			'code' => $code->getCode(),
			'id_user' => $code->getOwner(true),
			'id_client' => $code->getClient(true),
			'expiration_date' => $code->getExpirationDate()->format('Y-m-d H:i:s'),
			'status' => $code->getStatus()
		));
	}

	/**
	 * Updates the status of an existing access token.
	 * 
	 * (See IAccessToken::STATUS_*)
	 * 
	 * @param string $tokenStr
	 *  The token to be updated, as a string.
	 * 
	 * @param int $status
	 *  The new status code.
	 */
	public function updateAccessTokenStatus ($tokenStr, $status)
	{
		$this->getDatabase()->update
		(
			'oauth_access_token', 
			array('status' => $status), 
			array(
				'condition' => 'oauth_access_token.token = :token',
				'parameters' => array(':token' => $tokenStr)
			)
		);
	}

	/**
	 * Updates the status of an existing authorization code.
	 * 
	 * (See IAuthCode::STATUS_*)
	 * 
	 * In case the status is being set to "revoked", this method will also revoke
	 * any access tokens granted with the given authorization code.
	 * 
	 * @param string $codeStr
	 *  The code to be updated, as a string.
	 * 
	 * @param int $status
	 *  The new status code.
	 */
	public function updateAuthorizationCodeStatus ($codeStr, $status)
	{
		$this->getDatabase()->update
		(
			'oauth_authorization_code', 
			array('status' => $status), 
			array(
				'condition' => 'oauth_authorization_code.code = :code',
				'parameters' => array(':code' => $codeStr)
			)
		);
		
		if($status === IAuthCode::STATUS_REVOKED)
		{
			// Although not required, it is recommended that in the event of an
			// authorization code getting revoked for security reasons, you revoke
			// any access token that was issued by it.
			
			$this->getDatabase()->update
			(
				'oauth_access_token', 
				array('status' => IAccessToken::STATUS_REVOKED), 
				array(
					'condition' => 'oauth_access_token.code = :code',
					'parameters' => array(':code' => $codeStr)
				)
			);
		}
	}

}

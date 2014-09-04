<?php

use system\core\exception\RuntimeException;
use system\web\authentication\oauth\server\data\IAccessToken;
use system\web\authentication\oauth\server\data\IAuthCode;

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

namespace system\web\authentication\oauth\server\data;

/**
 * The storage is used by the OAuth component and it's counterparts to read and
 * write data to a persistent base.
 * 
 * How the data is stored, where, and how it's organized is up to the person 
 * implementing the storage. 
 * It is, however, assumed by the component that this storage is persistent 
 * and the data will remain accessible later even after the script has 
 * stopped running.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 * @since 0.2.0
 */
interface IStorage
{

	public function fetchCurrentEndUser ();
	
	public function fetchResourceOwner ($identifier);
	
	/**
	 * Stores an authorization code object.
	 * 
	 * If an error ocurred while storing the data, an Exception must be raised.
	 * 
	 * @param IAuthCode $code
	 * 
	 * @throws RuntimeException
	 */
	public function storeAuthorizationCode (IAuthCode $code);
	
	/**
	 * Updates an existing authorization code's status.
	 * 
	 * If an error ocurred while storing the data, an Exception must be raised.
	 * 
	 * @param string $codeStr
	 * 
	 * @param int $status
	 */
	public function updateAuthorizationCodeStatus ($codeStr, $status);
	
	/**
	 * Fetches an authorization code object by it's string representation.
	 * 
	 * @param string $code
	 *  The code, as a string.
	 * 
	 * @param bool $returnOnlyValid
	 *  If FALSE, this method will return any matching authorization code object
	 *  found, if TRUE, only a matching AND valid code found will be returned.
	 * 
	 * @return IAuthCode
	 *  The matching Authorization Code object, if any, or NULL otherwise.
	 */
	public function fetchAuthorizationCode ($code, $returnOnlyValid = false);
	
	/**
	 * Stores an access token object.
	 * 
	 * If an error ocurred while storing the data, an Exception must be raised.
	 * 
	 * @param IAccessToken $token
	 * 
	 * @throws RuntimeException
	 */
	public function storeAccessToken (IAccessToken $token);
	
	/**
	 * Updates an existing access token's status.
	 * 
	 * If an error ocurred while storing the data, an Exception must be raised.
	 * 
	 * @param string $tokenStr
	 * 
	 * @param int $status
	 */
	public function updateAccessTokenStatus ($tokenStr, $status);
	
	/**
	 * Fetches an access token object by it's string representation.
	 * 
	 * @param string $token
	 *  The token, as a string.
	 * 
	 * @param bool $returnOnlyValid
	 *  If FALSE, this method will return any matching access token object
	 *  found, if TRUE, only a matching AND valid token found will be returned.
	 * 
	 * @param int $context
	 *  The context of the access token being requested. (See IAccessToken::CONTEXT_*)
	 * 
	 * @return IAccessToken
	 *  The matching Access Token object, if any, or NULL otherwise.
	 */
	public function fetchAccessToken ($token, $context);
	
	/**
	 * Fetches a client object, given its identifier string.
	 * 
	 * @param string $clientId
	 *  The client ID as a string.
	 * 
	 * @return IClient
	 *  The matching Client object, if any, or NULL otherwise.
	 */
	public function fetchClient ($clientId);
	
	public function fetchClientByCredentials (array $credentials);
	
}

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

namespace system\web\authentication\oauth\server\data;

/**
 * Represents an access token entry, that may be stored in a database and should
 * atleast be associated with a resource owner, a client, and have an expiration date.
 * 
 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
 * 
 * ACCESS TOKEN
 *  Access tokens are credentials used to access protected resources. An access token
 *  is a string representing an authorization issued to the client.
 *  Tokens represent specific scopes and durations of access, granted by the 
 *  resource owner, and enforced by the resource server and authorization server.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 * @since 0.2.0
 */
interface IAccessToken
{
	
	/**
	 * Token is in good standing, not revoked or refreshed.
	 */
	const STATUS_OK = 0;
	
	/**
	 * Token has been revoked by the authorization server and should no longer
	 * be considered valid.
	 */
	const STATUS_REVOKED = 1;
	
	/**
	 * The refresh token has been used to generate a new access token,
	 * therefore, no more refreshes shall be allowed using the same refresh token.
	 */
	const STATUS_REFRESHED = 2;
		
	/**
	 * Token was issued with an authorization code.
	 */
	const CONTEXT_RESOURCE_OWNER_ACCESS_TOKEN = 0;
	
	/**
	 * Token was issued through an implicit grant. (no authorization code)
	 */
	const CONTEXT_RESOURCE_OWNER_IMPLICIT_ACCESS_TOKEN = 1;
	
	/**
	 * Token was issued to a client. (no owner)
	 */
	const CONTEXT_CLIENT_ACCESS_TOKEN = 2;
	
	const TYPE_BEARER = 'bearer';
	
	const TYPE_MAC = 'mac';
	
	/**
	 * Gets the token as a string.
	 * 
	 * @return string
	 */
	public function getToken ();
	
	/**
	 * Gets the code used to generate this token.
	 * 
	 * @return string|null
	 *  The code, if any.
	 */
	public function getCode ();
	
	/**
	 * Gets the resource owner object that this access token belongs to.
	 * 
	 * @return IResourceOwner
	 *  The resource owner.
	 */
	public function getOwner ($returnId = false);
		
	/**
	 * Gets the client object that this access token belongs to.
	 * 
	 * @return IClient
	 *  The client.
	 */
	public function getClient ($returnId = false);
	
	/**
	 * Gets the date of expiration of this access token.
	 * 
	 * @return DateTime
	 *  The expiration date.
	 */
	public function getExpirationDate ();
	
	/**
	 * Gets the status of this token.
	 * 
	 * NOTE: The OK status itself does not determine whether a token is valid.
	 * You must also have the expiration date in consideration.
	 * 
	 * (See IAccessToken::STATUS_*)
	 * 
	 * @return int
	 *  The status of the token.
	 */
	public function getStatus ();
	
	/**
	 * Gets the refresh token for this access token.
	 * 
	 * @return string
	 *  The refresh token.
	 */
	public function getRefreshToken ();
	
	/**
	 * Checks whether or not this access token is still valid.
	 * Must verify the expiration date, but can also perform any additional checks
	 * if needed.
	 * 
	 * @return bool
	 *  Returns TRUE if valid, FALSE otherwise.
	 */
	public function isValid ();
	
	/**
	 * Gets the token type.
	 * 
	 * @return string
	 */
	public function getType ();
	
	/**
	 * Gets the token context type.
	 * 
	 * (See IAccessToken::CONTEXT_*)
	 * 
	 * @return int
	 */
	public function getContextType ();
	
}

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
interface IAuthCode
{
	
	/**
	 * Gets the authorization code string.
	 * 
	 * @return string
	 *  The authorization code as a string.
	 */
	public function getCode ();
	
	/**
	 * Gets the resource owner that this code belongs to.
	 * 
	 * @return IResourceOwner
	 *  The resource owner.
	 */
	public function getOwnerId ();
	
	/**
	 * Gets the client ID that this code belongs to.
	 * 
	 * @return string
	 *  The client id.
	 */
	public function getClientId ();
	
	/**
	 * Gets the date of expiration of this code.
	 * 
	 * @return DateTime
	 *  The expiration date.
	 */
	public function getExpirationDate ();
	
	/**
	 * Checks whether or not this code is still valid.
	 * Must verify the expiration date, but can also perform any additional checks
	 * if needed.
	 * 
	 * @return bool
	 *  Returns TRUE if valid, FALSE otherwise.
	 */
	public function isValid ();
	
}

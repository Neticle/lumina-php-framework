<?php

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
 */
interface IAccessToken {
	
	const TYPE_BEARER = 'bearer';
	const TYPE_MAC = 'mac';
	
	public function getToken ();
	
	/**
	 * Gets the resource owner that this access token belongs to.
	 * 
	 * @return IResourceOwner
	 *  The resource owner.
	 */
	public function getOwnerId ();
	
	/**
	 * Gets the client that this access token belongs to.
	 * 
	 * @return IClient
	 *  The client.
	 */
	public function getClientId ();
	
	/**
	 * Gets the date of expiration of this access token.
	 * 
	 * @return DateTime
	 *  The expiration date.
	 */
	public function getExpirationDate ();
	
	/**
	 * Checks whether or not this access token is still valid.
	 * Must verify the expiration date, but can also perform any additional checks
	 * if needed.
	 * 
	 * @return bool
	 *  Returns TRUE if valid, FALSE otherwise.
	 */
	public function isValid ();
	
	public function getType ();
}

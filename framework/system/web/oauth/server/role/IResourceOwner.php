<?php

namespace system\web\oauth\server\role;

/**
 * Represents a resource owner and must be implemented by any class that is to 
 * be used to get authenticated agaisnt the OAuth2 Provider.
 * 
 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
 * 
 * RESOURCE OWNER
 *  An entity capable of granting access to a protected resource.
 *  When the resource owner is a person, it is referred to an an end-user.
 */
interface IResourceOwner {
	
	public function getIdentifier ();
	
}

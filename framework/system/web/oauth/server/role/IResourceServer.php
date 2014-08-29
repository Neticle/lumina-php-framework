<?php

namespace system\web\oauth\server\role;

/**
 * Represents an entity that holds protected resources owned by a resource owner.
 * Whether this entity is the application itself or a separate application hosted
 * somewhere else is up to whoever implements this interface.
 * 
 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
 * 
 * RESOURCE SERVER
 *  The server hosting the protected resources, capable of accepting and responding
 *  to protected resource requests using access tokens.
 */
interface IResourceServer {
	//put your code here
}

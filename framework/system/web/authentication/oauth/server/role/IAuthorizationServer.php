<?php

namespace system\web\authentication\oauth\server\role;

/**
 * Represents an entity that is capable of authenticating a resource owner and 
 * issuing access tokens with the authorization of said resource owner.
 * 
 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
 * 
 * AUTHORIZATION SERVER
 *  The server issuing access tokens to the client after successfully authenticating
 *  the resource owner and obtaining authorization.
 * 
 * NOTE
 *  The interaction between the authorization server and resource server is beyond
 *  the scope of this specification. The authorization server may be the same server
 *  as the resource server or a separate entity.
 *  A single authorization server may issue access tokens accepted by multiple
 *  resource servers.
 */
interface IAuthorizationServer {
	
	/**
	 * Generates an authorization code that may be used by a client to send
	 * requests on behalf of the resource owner.
	 * 
	 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
	 * 
	 * AUTHORIZATION CODE
	 *  The authorization code is obtained by using an authorization server as
	 *  an intermediary between the client and resource owner. Instead of
	 *  requesting authorization directly from the resource owner, the client 
	 *  directs the resource owner to an authorization server (...) which in turn
	 *  directs the resource owner back to the client with the authorization code.
	 * 
	 * @param IResourceOwner $owner
	 *  The resource owner to whom resources the code gives access to.
	 * 
	 * @param IClient $client
	 *  The client that made the authorization request and that will later be
	 *  allowed to exchange this code for an access token.
	 * 
	 * @return IAccessCode
	 *  The generated access code.
	 */
	public function grantAuthorizationCode (IResourceOwner $owner, IClient $client);
	
	/**
	 * 
	 * @param \neticle\base\module\oauth2\role\IResourceOwner $owner
	 * @param \neticle\base\module\oauth2\role\IClient $client
	 */
	public function grantImplicitAccessToken (IResourceOwner $owner, IClient $client);
	
	/**
	 * Generates an access token that may be used to send requests on behalf of
	 * the resource owner.
	 * 
	 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
	 * 
	 * RESOURCE OWNER PASSWORD CREDENTIALS
	 *  The resource owner credentials (i.e., username and password can be used
	 *  directly as an authorization grant to obtain an access token. The credentials
	 *  should only be used when there is a high degree of trust between the resource
	 *  owner and the client, and when other authorization grant types are not available.
	 * 
	 * @param array $credentials
	 * @param \neticle\base\module\oauth2\role\IClient $client
	 */
	public function grantByResourceOwnerCredentials (array $credentials, IClient $client = null);
	
	public function grantByClientCredentials();
}

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

/**
 * Represents a client application and must be implemented by any class that 
 * represents a third-party entity that will be making requests to protected
 * resources while verified by the OAuth2 Provider.
 * 
 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
 * 
 * CLIENT
 *  An application making protected resource requests on behalf of the
 *  resource owner and with its authorization. The term "client" does not imply
 *  any particular implementation characteristics (e.g., whether the application 
 *  executes on a server, a desktop, or other devices).
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 * @since 0.2.0
 */
interface IClient
{
	
	/**
	 * Clients capable of maintaining the confidentiality of their credentials, 
	 * or capable of secure client authentication using other means.
	 */
	const TYPE_CONFIDENTIAL = 0;
	
	/**
	 * Clients incapable of maintaining the confidentiality of their credentials
	 * (e.g. clients executing on a device used by the resource owner, such as an 
	 * installed native application or a web browser-based application), and incapable
	 * of secure client authentication via any other means.
	 */
	const TYPE_PUBLIC = 1;
	
	/**
	 * A web application is a confidential client running on a web server. 
	 * The client credentials as well as any access tokens issued to the client
	 * are stored on the web server and are not exposed to or accessible by the
	 * resource owner.
	 */
	const PROFILE_WEB_APPLICATION = 0;
	
	/**
	 * A user-agent-based application is a public client in which the client code
	 * is downloaded from a web server and executes within an user-agent (e.g., a
	 * web browser) on the device used by the resource owner.
	 * Protocol data and credentials are easily accessible (and often visible) to
	 * the resource owner.
	 */
	const PROFILE_UA_BASED_APPLICATION = 1;
	
	/**
	 * A native application is a public client installed and executed on the device 
	 * used by the resource owner. Protocol data and credentials are accessible to
	 * the resource owner. It is assumed that any client authentication 
	 * credentials included in the application can be extracted.
	 */
	const PROFILE_NATIVE_APPLICATION = 2;
	
	/**
	 * Gets the client's unique identifier.
	 * 
	 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
	 * 
	 * CLIENT IDENTIFIER
	 *  The auhorization server issues the registered client a client 
	 *  identifier -- a unique string representing the registration information
	 *  provided by the client. The client identifier is not a secret; it is 
	 *  exposed to the resource owner and MUST NOT be used alone for client
	 *  authentication.
	 * 
	 * @return string
	 *  The client's unique identifier.
	 */
	public function getIdentifier ();
	
	/**
	 * Gets the client's type.
	 *
	 * This type is used to determine the level of safety the client is able to
	 * provide and therefore should return one of the values pre-defined as 
	 * constants on the IClient interface (TYPE_CONFIDENTIAL or TYPE_PUBLIC).
	 * 
	 * @return int
	 */
	public function getType ();
	
	/**
	 * Gets the client's profile type.
	 * 
	 * @return int
	 */
	public function getProfile ();
	
	/**
	 * Gets the client's redirection endpoint URI.
	 * 
	 * This is where the authorization server will redirect the end-user to, along 
	 * with the authorization code.
	 * 
	 * @return string
	 */
	public function getRedirectionEndpointURI ();
	
}

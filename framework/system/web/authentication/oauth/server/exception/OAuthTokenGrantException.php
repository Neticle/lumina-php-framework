<?php

use system\web\authentication\oauth\server\role\IClient;

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

namespace system\web\authentication\oauth\server\exception;

/**
 * The OAuthTokenGrantException is intended to be thrown within the token endpoint
 * logic of the OAuth Provider or the Authorization Server.
 * 
 * By making use of this exception class, the provider will be able to properly
 * handle any errors and report back to the client in a matter that conforms
 * with the standards defined by the OAuth 2.0 specification.
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class OAuthTokenGrantException extends Exception
{
	
	/**
	 * The request is missing a required parameter, includes an invalid parameter
	 * value, includes a parameter more than once, or is otherwise malformed.
	 */
	const ERROR_INVALID_REQUEST = 'invalid_request';

	/**
	 * Client authentication failed (e.g., unknown client, no client 
	 * authentication included, or unsupported authentication method).
	 * The authorization server MAY return an HTTP 401 (Unauthorized) status code 
	 * to indicate which HTTP authentication schemes are supported.  
	 * If the client attempted to authenticate via the "Authorization" request 
	 * header field, the authorization server MUST respond with an HTTP 401 
	 * (Unauthorized) status code and include the "WWW-Authenticate" response 
	 * header field matching the authentication scheme used by the client.
	 */
	const ERROR_INVALID_CLIENT = 'invalid_client';
	
	/**
	 * The provided authorization grant (e.g., authorization code, resource owner 
	 * credentials) or refresh token is invalid, expired, revoked, does not match 
	 * the redirection URI used in the authorization request, or was issued to 
	 * another client.
	 */
	const ERROR_INVALID_GRANT = 'invalid_grant';
	
	/**
	 * The client is not authorized to request an authorization code using this 
	 * method.
	 */
	const ERROR_UNAUTHORIZED_CLIENT = 'unauthorized_client';

	/**
	 * The authorization grant type is not supported by the authorization server.
	 */
	const ERROR_UNSUPPORTED_GRANT_TYPE = 'unsupported_grant_type';
	
	/**
	 * The requested scope is invalid, unknown, malformed, or exceeds the scope 
	 * granted by the resource owner.
	 */
	const ERROR_INVALID_SCOPE = 'invalid_scope';
	
	/**
	 * The code of the raised error.
	 * 
	 * @type string 
	 */
	private $errorCode;

	/**
	 * Human-readable ASCII text providing additional information, used to assist
	 * the client developer in understanding the error that ocurred.
	 * 
	 * OPTIONAL
	 * 
	 * @type string
	 */
	private $errorDescription;

	/**
	 * A URI indentifying a human-readable web page with information about the
	 * error, used to provide the clien developer with additional information 
	 * about the error.
	 * 
	 * OPTIONAL
	 * 
	 * @type string
	 */
	private $errorURI;
	
	/**
	 * The requesting client.
	 * 
	 * @type IClient;
	 */
	private $client;
	
	/**
	 * Constructor.
	 *
	 * @param string $message
	 * 	A human readable message describing the exception.
	 *
	 * @param PHPException $previous
	 * 	The previous exception instance, for chaining.
	 */
	public function __construct ($errorCode, $client = null, $errorDescription = null, $errorURI = null, $previous = null)
	{
		parent::__construct($errorCode, $previous);
		
		$this->errorCode = $errorCode;
		$this->client = $client;
		$this->errorDescription = $this->filterASCIISafeString($errorDescription);
		$this->errorURI = $errorURI;
	}

	/**
	 * Gets the error code.
	 * 
	 * (See OAuthAuthorizationException::ERROR_*)
	 * 
	 * @return string
	 */
	public function getErrorCode ()
	{
		return $this->errorCode;
	}
	
	/**
	 * Gets the requesting client, if any.
	 * 
	 * @return IClient
	 */
	public function getClient ()
	{
		return $this->client;
	}
	
	/**
	 * Gets the error description, if any.
	 * 
	 * @return string
	 */
	public function getErrorDescription ()
	{
		return $this->errorDescription;
	}

	/**
	 * Gets the error URI, if any.
	 * 
	 * @return string
	 */
	public function getErrorURI ()
	{
		return $this->errorURI;
	}

	private function filterASCIISafeString ($str)
	{
		$safe = '';

		$len = strlen($str);
		for ($i = 0; $i < $len; $i++)
		{
			$ord = ord($str[$i]);

			if ($ord < 32 || $ord > 126)
			{
				continue;
			}

			$safe .= chr($ord);
		}

		return $safe;
	}

}

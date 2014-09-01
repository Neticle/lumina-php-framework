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

namespace system\web\authentication\oauth\server\exception;

use \system\core\exception\Exception;

/**
 * The OAuthAuthorizationException is intended to be thrown within the authorization
 * logic of the OAuth Provider or the Authorization Server.
 * 
 * By making use of this exception class, the provider will be able to properly
 * handle any errors and report back to the client in a matter that conforms
 * with the standards defined by the OAuth 2.0 specification.
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class OAuthAuthorizationException extends Exception
{

	/**
	 * The request is missing a required parameter, includes an invalid parameter
	 * value, includes a parameter more than once, or is otherwise malformed.
	 */
	const ERROR_INVALID_REQUEST = 'invalid_request';

	/**
	 * The client is no authorized to request an authorization code using this 
	 * method.
	 */
	const ERROR_UNAUTHORIZED_CLIENT = 'unauthorized_client';

	/**
	 * The resource owner or authorization server denied the request.
	 */
	const ERROR_ACCESS_DENIED = 'access_denied';

	/**
	 * The authorization server does not support obtaining an authorization code 
	 * using this method.
	 */
	const ERROR_UNSUPPORTED_RESPONSE_TYPE = 'unsupported_response_type';

	/**
	 * The requested scope is invalid, unknown, or malformed.
	 */
	const ERROR_INVALID_SCOPE = 'invalid_scope';

	/**
	 * The authorization server encountered an unexpected condition that prevented
	 * it from fulfulling the request.
	 */
	const ERROR_SERVER_ERROR = 'server_error';

	/**
	 * The authorization server is currently unable to handle the request due to
	 * a emporary overloading or maintenance of the server.
	 */
	const ERROR_TEMPORARILY_UNAVAILABLE = 'temporarily_unavailable';

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
	 * Required if a "state" parameter was present in the client authorization
	 * request. The exact value received is to be sent back to the client.
	 * 
	 * @type string 
	 */
	private $state;
	
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
	public function __construct ($errorCode, $state = null, $client = null, $errorDescription = null, $errorURI = null, $previous = null)
	{
		parent::__construct($errorCode, $previous);
		
		$this->errorCode = $errorCode;
		$this->state = $state;
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
	 * Gets the provided state, if any.
	 * 
	 * @return string
	 */
	public function getState () 
	{
		return $this->state;
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

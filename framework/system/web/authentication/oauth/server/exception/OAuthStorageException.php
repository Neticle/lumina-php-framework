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

use system\core\exception\Exception;

/**
 * The OAuthStorageException is intended to be thrown within the storage
 * implementation in cases where certain methods were not implemented or purpose.
 * 
 * For anything else, a regular exception can be thrown and the component will 
 * handle it as an internal server error.
 * 
 * By making use of this exception class, the provider will be able to properly
 * handle any errors and report back to the client in a matter that conforms
 * with the standards defined by the OAuth 2.0 specification.
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class OAuthStorageException extends Exception
{
	
	/**
	 * The request is missing a required parameter, includes an invalid parameter
	 * value, includes a parameter more than once, or is otherwise malformed.
	 */
	const ERROR_UNIMPLEMENTED_METHOD = 'storage_unimplemented_method';
	
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
		
	public function __construct ($errorCode, $errorDescription = null, $errorURI = null, $previous = null)
	{
		parent::__construct($errorCode, $previous);
		
		$this->errorCode = $errorCode;
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

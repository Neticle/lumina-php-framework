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

namespace system\http;

use system\core\exception\RuntimeException;

/**
 * The HTTP Request class. Must be implemented by a proper wrapper class.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
abstract class Request extends Message implements IRequest
{
	
	/**
	 * The URI for the request.
	 * 
	 * @type URI
	 */
	private $URI;
	
	/**
	 * The request method.
	 * 
	 * (See IRequest::METHOD_*)
	 * 
	 * @type string
	 */
	private $method = self::METHOD_GET;
	
	/**
	 * Gets the request's method.
	 * 
	 * (See IRequest::METHOD_*)
	 * 
	 * @return string
	 */
	public function getMethod ()
	{
		return $this->method;
	}
	
	/**
	 * Sets the request's method.
	 * 
	 * (See IRequest::METHOD_*)
	 * 
	 * @param string $method
	 */
	public function setMethod ($method)
	{
		$this->method = $method;
	}
	
	/**
	 * Gets the request URI.
	 * 
	 * @param bool $asString
	 *  If true, the URI's string representation will be returned.
	 * 
	 * @return string|URI
	 *  The URI object or it's string representation, depending on the $asString flag.
	 */
	public function getURI ($asString = false)
	{
		return $asString ? $this->URI->toString() : $this->URI;
	}

	/**
	 * Sets the request URI.
	 * 
	 * @param string|array|URI $URI
	 *  The request URI, either as a string, a configuration array or as the object
	 *  itself.
	 * 
	 * @throws RuntimeException
	 */
	public function setURI ($URI)
	{
		if(is_string($URI))
		{
			$URI = URI::parse($URI);
		}
		else if(is_array($URI))
		{
			$URI = new URI($URI);
		}
		
		if(!($URI instanceof URI))
		{
			throw new RuntimeException('Incompatible type passed as URI. Use a string, array or URI object');
		}
		
		$this->URI = $URI;
	}
	
	/**
	 * Sets an header of the message, given it's key and value.
	 * 
	 * @param string $key
	 *  The header's key (name).
	 * 
	 * @param string $value
	 *  The header's value.
	 * 
	 * @param bool $replace
	 *  If false, any header that is already set wont be changed.
	 */
	public function setHeader ($key, $value, $replace = true)
	{
		return parent::setHeader($key, $value, $replace);
	}

	/**
	 * Adds headers to the message, given an array.
	 * 
	 * @param array $headers
	 *  The list of headers to be added, paired as key => value.
	 */
	public function addHeaders (array $headers)
	{
		return parent::addHeaders($headers);
	}
	
	/**
	 * Sets the headers of the message, erasing any present ones.
	 * 
	 * @param array $headers
	 *  The list of headers to be added, paired as key => value.
	 */	
	public function setHeaders (array $headers)
	{
		return parent::setHeaders($headers);
	}

	/**
	 * Sets the message's body contents.
	 * 
	 * @param string $body
	 */
	public function setBody ($body)
	{
		$this->setMethod(self::METHOD_POST);
		
		return parent::setBody($body);
	}

}
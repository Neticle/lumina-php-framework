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

use system\core\Express;

/**
 * The base for the http request and response.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class Message extends Express implements IMessage
{
	/**
	 * The HTTP headers, as an associative array.
	 * 
	 * @type array
	 */
	private $headers = [];
	
	/**
	 * The message body contents.
	 * 
	 * @type string
	 */
	private $body;
	
	/**
	 * Adds headers to the message, given an array.
	 * 
	 * @param array $headers
	 *  The list of headers to be added, paired as key => value.
	 */
	protected function addHeaders (array $headers)
	{
		foreach ($headers as $key => $value)
		{
			$this->setHeader($key, $value);
		}
	}
	
	/**
	 * Sets the headers of the message, erasing any present ones.
	 * 
	 * @param array $headers
	 *  The list of headers to be added, paired as key => value.
	 */
	protected function setHeaders (array $headers)
	{
		$this->headers = [];
		
		$this->addHeaders($headers);
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
	protected function setHeader ($key, $value, $replace = true)
	{
		if($replace === false && isset($this->headers[$key]))
		{
			return;
		}
		
		$this->headers[$key] = $value;
	}
	
	/**
	 * Gets an header's value, given it's key.
	 * 
	 * @param string $key
	 *  The header's key (name).
	 * 
	 * @return string|null
	 *  The header's value, if any.
	 */
	public function getHeader ($key)
	{
		return isset($this->headers[$key]) ? $this->headers[$key] : null;
	}

	/**
	 * Gets all set headers as an associative array paired as key => value.
	 * 
	 * @return array
	 */
	public function getHeaders ()
	{
		return $this->headers;
	}

	/**
	 * Sets the message's body contents.
	 * 
	 * @param string $body
	 */
	protected function setBody ($body)
	{
		$this->body = $body;
	}
	
	/**
	 * Gets the message's body contents, if any.
	 * 
	 * @return string|null
	 */
	public function getBody ()
	{
		return $this->body;
	}
	
	/**
	 * Gets the length of the body contents.
	 * 
	 * @return int
	 */
	public function getLength ()
	{
		return isset($this->body) ? strlen($this->body) : 0;
	}

}
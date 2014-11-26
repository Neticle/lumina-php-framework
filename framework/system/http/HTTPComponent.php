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

use system\base\Component;
use system\core\exception\RuntimeException;

/**
 * The HTTP component.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class HTTPComponent extends Component
{
	
	/**
	 * List of available predefined wrapper classes.
	 * 
	 * @type array
	 */
	private static $wrappers = [
		'curl' => [
			'requestClass' => 'system\\http\\wrapper\\curl\\Request'
		]
	];
	
	/**
	 * Configuration for the wrapper in use.
	 * 
	 * @type array
	 */
	private $wrapper;
	
	/**
	 * Gets the wrapper configuration.
	 * 
	 * @return array
	 */
	public function getWrapper ()
	{
		return $this->wrapper;
	}

	/**
	 * Sets the wrapper to be used.
	 * 
	 * @param string|array $wrapper
	 *  The name of a predefined wrapper or an array containing the configuration
	 *  for a custom one. If passing an array, it must contain a 'requestClass' member
	 *  referring the class to be used when creating requests.
	 * 
	 * @throws RuntimeException
	 */
	public function setWrapper ($wrapper)
	{
		if(is_string($wrapper) && isset(self::$wrappers[$wrapper]))
		{
			$this->wrapper = self::$wrappers[$wrapper];
		}
		else
		{
			if(!is_array($wrapper) || !isset($wrapper['requestClass']))
			{
				throw new RuntimeException('Wrapper definition must be either a predefined handle or an array containing the wrapper settings');
			}
			
			$this->wrapper = $wrapper;
		}
	}

	/**
	 * Gets the class to be used to create new HTTP requests.
	 * 
	 * @return string
	 */
	private function getRequestClass () 
	{
		return $this->wrapper['requestClass'];
	}
	
	/**
	 * Creates a new HTTP Request object.
	 * 
	 * @param array $configuration
	 *  The configuration for the new request. Must atleast contain the 'URI' setting.
	 * 
	 * @return \system\http\Request
	 *  The newly created request object.
	 */
	public function createRequest (array $configuration) 
	{
		$class = $this->getRequestClass();
		
		return new $class($configuration);
	}
	
	/**
	 * Creates a new GET request.
	 * 
	 * @param string|array|URI $URI
	 *  The URI for the request, either as a string, a configuration array or
	 *  the object itself.
	 * 
	 * @return \system\http\Request|\system\http\Response
	 *  Returns the newly created request object, configured with the given URI.
	 */
	public function get ($URI)
	{
		return $this->createRequest
		([
			'URI' => $URI
		]);
	}
	
	/**
	 * Creates a new POST request.
	 * 
	 * @param string|array|URI $URI
	 *  The URI for the request, either as a string, a configuration array or
	 *  the object itself.
	 * 
	 * @param string $body
	 *  The body contents for the request.
	 * 
	 * @return \system\http\Request|\system\http\Response
	 *  Returns the newly created request object, configured with the given URI and body contents.
	 */
	public function post ($URI, $body)
	{
		return $this->createRequest
		([
			'URI' => $URI,
			'method' => 'POST',
			'body' => $body
		]);
	}

}

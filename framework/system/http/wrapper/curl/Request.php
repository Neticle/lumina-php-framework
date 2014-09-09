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

namespace system\http\wrapper\curl;

use system\http\Response;
use system\core\exception\RuntimeException;

/**
 * Request wrapper for libcurl. Requires php5-curl package installed.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class Request extends \system\http\Request
{
	/**
	 * List of HTTP headers received from the response.
	 * 
	 * @type array
	 */
	private $responseHeaders = [];
	
	/**
	 * Handles the response headers received from curl.
	 * This method is used as a callback because curl doesn't provide any other
	 * reliable way to fetch the response headers.
	 * 
	 * @param resource $resource
	 * @param string $header
	 * 
	 * @return int
	 */
	private function parseResponseHeader ($resource, $header)
	{
		$this->responseHeaders[] = $header;
		
		return strlen($header);
	}
	
	/**
	 * Sends the request to the specified host.
	 * 
	 * @return \system\http\Response
	 *  Returns the response object received upon request.
	 * 
	 * @throws RuntimeException
	 */
	public function send ()
	{
		$this->responseHeaders = [];
		
		$resource = curl_init();
		
		// CURL requires the headers array to consist of the header entries already
		// composed, as opposed to an associative array organizaed with key and values.
		$reqHeaders = [];
		foreach($this->getHeaders() as $key => $value)
		{
			$reqHeaders[] = $key . ': ' . $value;
		}
		
		curl_setopt($resource, CURLOPT_URL, $this->getURI(true));
		curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($resource, CURLOPT_CUSTOMREQUEST, $this->getMethod());
		curl_setopt($resource, CURLOPT_HTTPHEADER, $reqHeaders);
		curl_setopt($resource, CURLOPT_HEADERFUNCTION, [$this, 'parseResponseHeader']);
		
		$body = $this->getBody();
		
		if($body !== null)
		{
			curl_setopt($resource, CURLOPT_POST, 1);
			curl_setopt($resource, CURLOPT_POSTFIELDS, $body);
		}
		
		$transfer = curl_exec($resource);
		
		if($transfer === false)
		{
			$error = curl_error($resource);
			$errorNo = curl_errno($resource);
			
			curl_close($resource);
			
			throw new RuntimeException($error . ' (' . $errorNo . ')');
		}
		
		// Extract the status code and parse the remaining headers
		$code = array_splice($this->responseHeaders, 0, 1);
		$code = explode(' ', $code[0]);

		$headersAssoc = [];
		
		foreach($this->responseHeaders as $header) 
		{
			$separator = strpos($header, ':');
			
			if($separator === false)
			{
				continue;
			}
			
			$key = trim(substr($header, 0, $separator));
			$value = trim(substr($header, $separator + 1));
			
			$headersAssoc[$key] = $value;
		}
		
		$response = new Response($code[1], $headersAssoc, $transfer);

		curl_close($resource);
		
		return $response;
	}

}
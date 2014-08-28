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

namespace system\web;

use \system\core\Lumina;
use \system\core\exception\RuntimeException;

/**
 * The Response provides you consistent static methods that allow you to
 * to easily manage the response headers and its contents.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class Response
{
	/**
	 * The default HTTP status messages, indexed by status code.
	 *
	 * @type array
	 */
	private static $httpStatusMessages = array(
		100 => 'Continue',
		
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		303 => 'See Other',
		307 => 'Temporary Redirect',
		
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Document Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable'		
	);

	/**
	 * Defines a new response header.
	 *
	 * This function wraps 'header' and will generate an error if the
	 * response headers have already been sent!
	 *
	 * @param string $name
	 *	The name of the header to define.
	 *
	 * @param string $content
	 *	The header raw content.
	 *
	 * @param bool $replace
	 *	When set to TRUE any previously matching header names will be
	 *	removed and replaced by this one.
	 */
	public static function setHeader($name, $content, $replace = false)
	{		
		header
		(
			$name . ': ' . str_replace([ "\t", "\r" ], ' ', str_replace("\n", "\n ", $content)), 
			$replace
		);
	}
	
	/**
	 * Defines a new response cookie header.
	 *
	 * This function wraps 'setcookie' and will generate an error if the
	 * response headers have already been sent!
	 *
	 * @param string $name
	 *	The name of the cookie to define.
	 *
	 * @param string $value
	 *	The raw cookie value.
	 *
	 * @param int $expiry
	 *	The number of seconds the cookie will expiry in since the moment
	 *	it was defined, or 0 (zero) for a session cookie.
	 *
	 * @param string $path
	 *	The cookie path. By default, the current request path will be used
	 *	if no value is specified.
	 *
	 * @param string $domain
	 *	The cookie domain. By default, the current domain will be used
	 *	if no value is specified.
	 *
	 * @param bool $secure
	 *	A flag indicating wether or not the cookie should only be included
	 *	in secure requests, through HTTPS/SSL.
	 *
	 * @param bool $http
	 *	A flag indicating wether or not the cookie should only be available
	 *	in the request headers. This means when it's set to TRUE (default),
	 *	the browser will not grant access to this cookie to any underlying
	 *	scripts (e.g.: javascript).
	 */
	public static function setCookie($name, $value, $expiry = 0, $path = null, 
		$domain = null, $secure = false, $http = true)
	{
		if ($expiry > 0)
		{
			$expiry += time();
		}
	
		setcookie($name, $value, $expiry, $path, $domain, $secure, $http);
	}
	
	/**
	 * Sets the response status.
	 *
	 * This function wraps 'header' and will generate an error if the
	 * response headers have already been sent!
	 *
	 * @throws RuntimeException
	 *	Thrown if no message is given and a default one is not defined for
	 *	the specified status code.
	 *
	 * @param int $code
	 *	The response status code.
	 *
	 * @param string $message
	 *	The response status message. If a message is not specified the default
	 *	message for the given code will be used.
	 */
	public static function setStatus($code, $message = null)
	{
		if (isset($message))
		{
			$message = str_replace
			(
				array("\t", "\r"), 
				' ', 
				str_replace("\n", "\n ", $message)
			);
		}
		
		else if (isset(self::$httpStatusMessages[$code]))
		{
			$message = self::$httpStatusMessages[$code];
		}
		
		else
		{
			throw new RuntimeException('Unable to determine "' . $code . '" status code message.'); 
		}
	
		header($_SERVER['SERVER_PROTOCOL'] . ' ' . $code . ' ' . $message, true);
	}
	
	/**
	 * Defines the redirect location.
	 *
	 * Please note the script WILL NOT BE terminated by this function and such
	 * behavior has to be explicitly implemented.
	 *
	 * If a route array is given as the intended location an absolute URL will
	 * be created through the application 'router' component.
	 *
	 * @param string|array $location
	 *	The location to redirect to as a string or a route array.
	 */
	public static function setLocation($location)
	{
		if (is_array($location))
		{
			$location = Lumina::getApplication()->getComponent('router')
				->createAbsoluteUrl($location[0], array_slice($location, 1));
		}
		
		header('Location: ' . $location);
	}

}


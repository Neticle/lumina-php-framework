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

use \system\core\exception\RuntimeException;
use \system\web\exception\HttpException;

/**
 * The Request provides you consistent static methods that allow you to
 * to easily pull information passed through it.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class Request 
{

	/**
	 * Abstracts the common behaviour for the get* and filter* methods.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and not defined in the current
	 *	request or source array, as well as when it doesn't match the
	 *	expected pattern.
	 *
	 * @param string $value
	 *	The value being extracted or filtered.
	 *
	 * @param string $result
	 *	The reference to the variable to define the value with if it is
	 *	not empty and matching the expected pattern.
	 *
	 * @param array $regex
	 *	A regular expression to validate the value with.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is not
	 *	defined in the current request or source array.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	private static function extract($value, &$result, $regex = null, $required = true) 
	{
		if (isset($value) && $value !== '') 
		{
			if ($regex) 
			{
				// Run the regular expression validation
				$matches = preg_match($regex, $value);
			
				if ($matches === false) 
				{	
					// An error ocurred while compiling the regular expression
					throw new RuntimeException('Invalid regular expression "' . $regex . '" specified.');
				}
				
				if ($matches < 1) 
				{
					// Invalid value format
					throw new HttpException(400, 'Bad Request');
				}
			}
			
			$result = $value;
			return true;
		}
		
		if ($required) 
		{
			// The variable is required but was empty
			throw new HttpException(400, 'Bad Request');	
		}
		
		return false;
	}
	
	/**
	 * Abstracts the common behaviour for the get* methods.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and not defined in the current
	 *	request or source array, as well as when it doesn't match the
	 *	expected pattern.
	 *
	 * @param string $key
	 *	The key for the value to be returned.
	 *
	 * @param string $result
	 *	The reference to the variable to define the value with if it is
	 *	defined in the current request or source array and matching the
	 *	expected pattern.
	 *
	 * @param array $regex
	 *	A regular expression to validate the value with.
	 *
	 * @param array $source
	 *	The source array, defaulting to $_GET.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is not
	 *	defined in the current request or source array.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	private static function get($key, &$result, $regex = null, $source = null, $required = true) 
	{	
		if (!isset($source)) 
		{
			$source = $_GET;
		}
		
		if (isset($source[$key])) 
		{
			return self::extract($source[$key], $result, $regex, $required);	
		}
		
		if ($required) 
		{
			throw new HttpException(400, 'Bad Request');	
		}
		
		return false;
	}
	
	/**
	 * Returns a value from the current request or a given associative array.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and not defined in the current
	 *	request or source array, as well as when it doesn't match the
	 *	expected pattern.
	 *
	 * @param string $key
	 *	The key for the value to be returned.
	 *
	 * @param array $source
	 *	The source array, defaulting to $_GET.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is not
	 *	defined in the current request or source array.
	 *
	 * @param mixed $default
	 *	The value to be returned by default.
	 *
	 * @return array|mixed
	 *	Returns the specified value or "$default".
	 */
	public static function getObject($key, $source = null, $required = true, $default = null) 
	{
		if (!isset($source)) 
		{
			$source = $_GET;
		}
		
		if (isset($source[$key]) && $source[$key] !== '') 
		{
			$value = $source[$key];
			
			if (!is_array($value)) 
			{
				throw new HttpException(400, 'Bad Request');
			}
			
			return $value;
			
		}
		
		if ($required) 
		{
			throw new HttpException(400, 'Bad Request');
		}
		
		return $default;
	
	}
	
	/**
	 * Returns a value from the current request or a given associative array.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and not defined in the current
	 *	request or source array, as well as when it doesn't match the
	 *	expected pattern.
	 *
	 * @param string $key
	 *	The key for the value to be returned.
	 *
	 * @param array $source
	 *	The source array, defaulting to $_GET.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is not
	 *	defined in the current request or source array.
	 *
	 * @param mixed $default
	 *	The value to be returned by default.
	 *
	 * @return int|mixed
	 *	Returns the specified value or "$default".
	 */
	public static function getInt($key, $source = null, $required = true, $default = 0) 
	{
		return self::get($key, $result, '/^\d+$/', $source, $required) ? 
			intval($result) : $default;
	}
	
	/**
	 * Returns a value from the current request or a given associative array.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and not defined in the current
	 *	request or source array, as well as when it doesn't match the
	 *	expected pattern.
	 *
	 * @param string $key
	 *	The key for the value to be returned.
	 *
	 * @param array $source
	 *	The source array, defaulting to $_GET.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is not
	 *	defined in the current request or source array.
	 *
	 * @param mixed $default
	 *	The value to be returned by default.
	 *
	 * @return float|mixed
	 *	Returns the specified value or "$default".
	 */
	public static function getDouble($key, $source = null, $required = true, $default = 0) 
	{
		return self::get($key, $result, '/^((\d+)|((\d+)?\.\d+))$/', $source, $required) ? 
			floatval($result) : $default;
	}
	
	/**
	 * Returns a value from the current request or a given associative array.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and not defined in the current
	 *	request or source array, as well as when it doesn't match the
	 *	expected pattern.
	 *
	 * @param string $key
	 *	The key for the value to be returned.
	 *
	 * @param array $source
	 *	The source array, defaulting to $_GET.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is not
	 *	defined in the current request or source array.
	 *
	 * @param mixed $default
	 *	The value to be returned by default.
	 *
	 * @return bool|mixed
	 *	Returns the specified value or "$default".
	 */
	public static function getBool($key, $source = null, $required = true, $default = false) 
	{
		return self::get($key, $result, '/^(true|false|1|0)$/', $source, $required) ? 
			($result === 'true' || $result === '1') : $default;
	}
	
	/**
	 * Returns a value from the current request or a given associative array.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and not defined in the current
	 *	request or source array, as well as when it doesn't match the
	 *	expected pattern.
	 *
	 * @param string $key
	 *	The key for the value to be returned.
	 *
	 * @param array $source
	 *	The source array, defaulting to $_GET.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is not
	 *	defined in the current request or source array.
	 *
	 * @param mixed $default
	 *	The value to be returned by default.
	 *
	 * @return string|mixed
	 *	Returns the specified value or "$default".
	 */
	public static function getString($key, $source = null, $required = true, $default = null) 
	{
		return self::get($key, $result, null, $source, $required) ? 
			$result : $default;
	}
		
	/**
	 * Filters a given value.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and empty or not matching the
	 *	expected pattern.
	 *
	 * @param string $value
	 *	The value to be filtered.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is empty.
	 *
	 * @param mixed $default
	 *	The value to be returned by default.
	 *
	 * @return int|mixed
	 *	Returns the specified value or "$default".
	 */
	public static function filterInt($value, $required = true, $default = 0) 
	{
		return self::extract($value, $result, '/^\d+$/', $required) ?
			intval($result) : $default;
	}
	
	/**
	 * Filters a given value.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and empty or not matching the
	 *	expected pattern.
	 *
	 * @param string $value
	 *	The value to be filtered.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is empty.
	 *
	 * @param mixed $default
	 *	The value to be returned by default.
	 *
	 * @return float|mixed
	 *	Returns the specified value or "$default".
	 */
	public static function filterDouble($value, $required = true, $default = 0) 
	{
		return self::extract($value, $result, '/^((\d+)|((\d+)?\.\d+))$/', $required) ?
			floatval($result) : $default;
	}
	
	/**
	 * Filters a given value.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and empty or not matching the
	 *	expected pattern.
	 *
	 * @param string $value
	 *	The value to be filtered.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is empty.
	 *
	 * @param mixed $default
	 *	The value to be returned by default.
	 *
	 * @return bool|mixed
	 *	Returns the specified value or "$default".
	 */
	public static function filterBool($value, $required = true, $default = false) 
	{
		return self::extract($value, $result, '/^(true|false|1|0)$/', $required) ?
			($result === 'true' || $result === '1') : $default;
	}
	
	/**
	 * Filters a given value.
	 *
	 * @throws HttpException
	 *	Thrown when the value is required and empty or not matching the
	 *	expected pattern.
	 *
	 * @param string $value
	 *	The value to be filtered.
	 *
	 * @param bool $required
	 *	When set to TRUE an exception will be thrown if the value is empty.
	 *
	 * @param mixed $default
	 *	The value to be returned by default.
	 *
	 * @return string|mixed
	 *	Returns the specified value or "$default".
	 */
	public static function filterString($value, $required = true, $default = null) 
	{
		return self::extract($value, $result, null, $required) ?
			$result : $default;
	}
	
	/**
	 * Returns a flag indicating wether or not the current request has been
	 * made using any known AJAX technics.
	 *
	 * @param bool $allowEmulation
	 *	When set to TRUE the request is also considered an AJAX request if the
	 *	value of 'ajax' is set to 'true' in the query string.
	 *
	 * @return bool
	 *	Returns TRUE if this is an AJAX request, FALSE otherwise.
	 */
	public static function isAjax($allowEmulation = true) 
	{
		return 
		(
			$allowEmulation && isset($_GET['ajax']) && 
			strtolower($_GET['ajax']) === 'true'
		) 
		|| 
		(
			isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
		);
	}

	/**
	 * Returns the request method.
	 *
	 * @return string
	 *	The request method.
	 */
	public static function getMethod() 
	{
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}

	public static function getHeaders()
	{
		// TODO: Find a suitable and portable solution.
		// apache_request_headers() - only works with apache or FastCGI (php > 5.4)
		// http_get_request_headers() - requires a pecl extension to be installed (pecl_http)
		// getallheaders() - is apparently an alias to apache_request_headers
		// ...
		
		return apache_request_headers();
	}
	
	public static function getHeader($key)
	{
		return self::get($key, $result, null, self::getHeaders(), false) ? 
			$result : null;
	}
	
	/**
	 * Returns the request's POST body raw contents, if any.
	 *
	 * @return string
	 *  The request body contents.
	 */
	public static function getBody()
	{
		return file_get_contents('php://input');
	}
	
}


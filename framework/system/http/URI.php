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
use system\core\exception\RuntimeException;

/**
 * A class representing an URI (Universal Resource Identifier).
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class URI extends Express 
{
	/**
	 * The schema (e.g. 'http').
	 * 
	 * @type string 
	 */
	private $scheme;
	
	/**
	 * The hostname or IP.
	 * 
	 * @type string 
	 */
	private $host;
	
	/**
	 * The port.
	 * 
	 * @type int 
	 */
	private $port;
	
	/**
	 * The username.
	 * 
	 * @type string 
	 */
	private $user;
	
	/**
	 * The password.
	 * 
	 * @type string 
	 */
	private $pass;
	
	/**
	 * The path.
	 * 
	 * @type string 
	 */
	private $path;
	
	/**
	 * The query string contents, as an associative array.
	 * 
	 * @type array 
	 */
	private $query;
	
	/**
	 * The fragment portion.
	 * 
	 * @type string 
	 */
	private $fragment;
	
	/**
	 * Creates a new URI.
	 * 
	 * 'hostname' must be provided in the configuration.
	 * 
	 * @param array $configuration
	 *  The configuration for the URI.
	 */
	public function __construct (array $configuration)
	{
		parent::__construct($configuration);
	}
	
	/**
	 * Gets the URI's scheme, if any.
	 * 
	 * @return string|null
	 */
	public function getScheme ()
	{
		return $this->scheme;
	}

	/**
	 * Gets the host name or IP address.
	 * 
	 * @return string
	 */
	public function getHost ()
	{
		return $this->host;
	}

	/**
	 * Gets the port number, if any.
	 * 
	 * @return int|null
	 */
	public function getPort ()
	{
		return $this->port;
	}

	/**
	 * Gets the username, if any.
	 * 
	 * @return string|null
	 */
	public function getUser ()
	{
		return $this->user;
	}

	/**
	 * Gets the password, if any.
	 * 
	 * @return string|null
	 */
	public function getPass ()
	{
		return $this->pass;
	}

	/**
	 * Gets the path, if any.
	 * 
	 * @return string|null
	 */
	public function getPath ()
	{
		return $this->path;
	}

	/**
	 * Gets the query string, if any.
	 * 
	 * @return string|null
	 */
	public function getQuery ()
	{
		return is_array($this->query) ? http_build_query($this->query) : null;
	}

	/**
	 * Gets the fragment portion, if any.
	 * 
	 * @return string|null
	 */
	public function getFragment ()
	{
		return $this->fragment;
	}

	/**
	 * Sets the scheme.
	 * 
	 * @param string $scheme
	 */
	public function setScheme ($scheme)
	{
		$this->scheme = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $scheme));
	}

	/**
	 * Sets the host.
	 * 
	 * @param string $host
	 *  The host name of IP address as a string.
	 * @throws RuntimeException
	 */
	public function setHost ($host)
	{
		// check if host matches a hostname / domain name or IP address
		if(preg_match('/^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$/', $host) !== 1 &&
			preg_match('/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $host) !== 1)
		{
			throw new RuntimeException('Provided hostname is invalid');
		}
		
		$this->host = $host;
	}

	/**
	 * Ses the port number.
	 * 
	 * @param int $port
	 */
	public function setPort ($port)
	{
		$this->port = intval($port);
	}

	/**
	 * Sets the user name.
	 * 
	 * @param string $user
	 */
	public function setUser ($user)
	{
		$this->user = $user;
	}

	/**
	 * Sets the password.
	 * 
	 * @param string $pass
	 */
	public function setPass ($pass)
	{
		$this->pass = $pass;
	}

	/**
	 * Sets the path.
	 * 
	 * @param string $path
	 */
	public function setPath ($path)
	{
		$this->path = ltrim($path, '/');
	}

	/**
	 * Sets the query string contents.
	 * 
	 * @param string|array $query
	 *  If a string is provided, it will be parsed into an associative array, provided
	 *  that the string has a valid format.
	 */
	public function setQuery ($query)
	{
		if(!is_array($query))
		{
			parse_str(ltrim($query, '?'), $query);
		}
		
		$this->query = $query;
	}

	/**
	 * Sets an attribute on the URI's query string.
	 * 
	 * @param string $key
	 *  The attribute's name / key.
	 * 
	 * @param string $value
	 *  The attribute's value.
	 * 
	 * @param bool $replace
	 *  If set to false it won't set any attribute that already exists.
	 */
	public function setQueryAttribute ($key, $value, $replace = true)
	{
		if($replace === false && isset($this->query[$key]))
		{
			return;
		}
		
		$this->query[$key] = $value;
	}
	
	/**
	 * Checks if the URI's query string contains a given attribute.
	 * 
	 * @param string $key
	 *  The attribute's name / key.
	 * 
	 * @return bool
	 */
	public function hasQueryAttribute ($key)
	{
		return isset($this->query[$key]);
	}
	
	/**
	 * Removes a given attribute from the URI's query string.
	 * 
	 * @param type $key
	 *  The attribute's name / key.
	 */
	public function removeQueryAttribute ($key)
	{
		if($this->hasQueryAttribute($key))
		{
			unset($this->query[$key]);
		}
	}
	
	/**
	 * Sets the fragment portion of the URI.
	 * 
	 * @param string $fragment
	 */
	public function setFragment ($fragment)
	{
		$this->fragment = $fragment;
	}

	/**
	 * Generates the string representation of the URI.
	 * 
	 * @return string
	 * @throws RuntimeException
	 */
	public function toString ()
	{
		$URI = '';
		
		if(isset($this->scheme))
		{
			$URI .= $this->scheme . ':';
		}
		
		$URI .= '//';
		
		if(isset($this->user))
		{
			$URI .= $this->user;
			
			if(isset($this->pass))
			{
				$URI .= ':' . $this->pass . '@';
			}
			else
			{
				$URI .= '@';
			}
		}
	
		if(!isset($this->host))
		{
			throw new RuntimeException('Hostname is not defined');
		}
		
		$URI .= $this->host . '/';
		
		if(isset($this->path))
		{
			$URI .= $this->path;
		}
		
		if(isset($this->query))
		{
			$URI .= '?' . $this->getQuery();
		}
		
		if(isset($this->fragment))
		{
			$URI .= '#' . $this->fragment;
		}
		
		return $URI;
	}
	
	/**
	 * Generates the string representation of the URI.
	 * 
	 * @return string
	 * @throws RuntimeException
	 */
	public function __toString ()
	{
		return $this->toString();
	}
	
	/**
	 * Parses a string containing a valid URI.
	 * 
	 * @param string $URI
	 *  The string to be parsed.
	 * 
	 * @return \system\http\URI
	 * @throws RuntimeException
	 */
	public static function parse ($URI)
	{
		$URI = parse_url($URI);
		
		if($URI === false)
		{
			throw new RuntimeException('Could not parse the provided string as an URI');
		}
		
		return new URI($URI);
	}
	
}
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

namespace system\web\router;

use \system\web\router\Router;

/**
 * The router is responsible for parsing the information contained in the
 * current request into a route array and generate URLs that link to a specific
 * controller action.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class DefaultRouter extends Router
{
	/**
	 * The query string variable holding the route to link to.
	 *
	 * @type string	
	 */
	private $key = 'action';
	
	/**
	 * The route token delimiter.
	 *
	 * @type string
	 */
	private $delimiter = '.';
	
	/**
	 * The script used to load the application and dispatch based on the
	 * current request, relative to the application base URL.
	 *
	 * @type string
	 */
	private $script;
	
	/**
	 * Returns the requested route and action parameters as a numeric array
	 * containing those two indexes.
	 *
	 * @return array
	 *	The requested route as an array.
	 */
	public function getRequestRoute()
	{
		$query = $_GET;
		
		if (isset($query[$this->key]))
		{
			$route = implode('/', 
				preg_split('/(\s*' . preg_quote($this->delimiter, '/'). '\s*)/', 
					$query[$this->key], -1, PREG_SPLIT_NO_EMPTY)
			);
			
			unset($query[$this->key]);
		}
		else
		{
			$route = null;
		}
		
		return [ $route, $query ];
	}
	
	/**
	 * Returns the script used to load the application and dispatch based on the
	 * current request, relative to the application base URL.
	 *
	 * If the script hasn't been previously defined, it will be determined
	 * automatically based on the request uri.
	 *
	 * @return string
	 *	The application script.
	 */
	public function getScript()
	{
		if (!isset($this->script))
		{
			$uri = isset($_SERVER['REQUEST_URI']) ?
				$_SERVER['REQUEST_URI'] : '/';
			
			$script = substr($uri, strrpos($uri, '/') + 1);
			
			if (($index = strpos($script, '?')) !== false)
			{
				$script = substr($script, 0, $index);
			}
			
			$this->script = empty($script) ?
				'index.php' : $script;
		}
		
		return $this->script;
	}

	/**
	 * Determines and returns the default base URL.
	 *
	 * @return string
	 *	The Web Application base URL.
	 */
	public function getDefaultBaseUrl()
	{
		if (!isset($_SERVER['HTTP_HOST']))
		{
			throw new RuntimeException('Value of "Host" header is not defined.');
		}
	
		$url = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') ?
			'https://' : 'http://';
			
		$url .= $_SERVER['HTTP_HOST'];
		
		$url .= $_SERVER['REQUEST_URI'] ? 
			(substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1)) : '/';
		
		return $url;
	}

	/**
	 * Creates a URL relative to the application base URL.
	 *
	 * @param string $route
	 *	A route resolving to a controller action.
	 *
	 * @param array $parameters
	 *	An associative array defining the parameters to be bound
	 *	to the action method.
	 *
	 * @return string
	 *	The created URL.
	 */
	public function createRouteUrl($route = null, array $parameters = null)
	{
		$url = $this->getScript();
		$query = [];
		
		if (isset($route))
		{
			$query[$this->key] = str_replace('/', $this->delimiter, $route);
		}
		
		if (isset($parameters))
		{
			$query += $parameters;
		}
		
		if (!empty($query))
		{
			$url .= '?' . http_build_query($query);
		}
		
		return $url;
	}

}

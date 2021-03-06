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

use \system\base\Component;

/**
 * The router is responsible for parsing the information contained in the
 * current request into a route array and generate URLs that link to a specific
 * controller action.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
abstract class Router extends Component
{
	/**
	 * The application base URL.
	 *
	 * @type string
	 */
	private $baseUrl;
	
	/**
	 * Defines the application base URL.
	 *
	 * @param string $baseUrl
	 *	The application base URL, which must end with a slash ("/").
	 */
	public function setBaseUrl($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}
	
	/**
	 * Returns the application base URL.
	 *
	 * If a base URL was not previously defined it will be determined from
	 * the current request by the router specific implementation.
	 *
	 * @return string
	 *	The application base URL.
	 */
	public function getBaseUrl()
	{
		if (!isset($this->baseUrl))
		{
			$this->baseUrl = $this->getDefaultBaseUrl();
		}
		
		return $this->baseUrl;
	}

	/**
	 * Determines and returns the default base URL.
	 *
	 * @return string
	 *	The Web Application base URL.
	 */
	public abstract function getDefaultBaseUrl();
	
	/**
	 * Returns the requested route and action parameters as a numeric array
	 * containing those two indexes.
	 *
	 * @return array
	 *	The requested route as an array.
	 */
	public abstract function getRequestRoute();
	
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
	public final function createUrl($route = null, array $parameters = null)
	{
		if(is_array($route))
		{
			$parameters = array_slice($route, 1);
			$route = $route[0];
		}
		
		return $this->createRouteUrl
		(
			$this->getApplication()->getContext()->getResolvedContextRoute($route),
			$parameters
		);
	}
	
	/**
	 * Creates an absolute URL.
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
	public final function createAbsoluteUrl($route = null, array $parameters = null)
	{
		if(is_array($route))
		{
			$parameters = array_slice($route, 1);
			$route = $route[0];
		}
		
		return $this->createAbsoluteRouteUrl
		(
			$this->getApplication()->getContext()->getResolvedContextRoute($route),
			$parameters
		);
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
	public abstract function createRouteUrl($route = null, array $parameters = null);
	
	/**
	 * Creates an absolute URL.
	 *
	 * An absolute URL results from the concatenation of the application
	 * base URL and the relative URL returned by "createUrl".
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
	public function createAbsoluteRouteUrl($route = null, array $parameters = null)
	{
		if(is_array($route))
		{
			$parameters = array_slice($route, 1);
			$route = $route[0];
		}
	
		return $this->getBaseUrl() . $this->createRouteUrl($route, $parameters);
	}

}

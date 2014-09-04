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

namespace system\web\authentication\oauth\server\flow;

use system\core\exception\RuntimeException;
use system\web\authentication\oauth\server\component\OAuth2Provider;
use system\web\authentication\oauth\server\exception\OAuthAuthorizationException;
use system\web\exception\HttpException;
use system\web\Request;

/**
 * Description of AuthorizationFlow.php
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
abstract class AuthorizationFlow extends Flow
{
    
	/**
	 * Gets the currently requesting client.
	 * 
	 * This method parses the current request and finds the "client_id" argument.
	 * It then uses the Storage to fetch the object corresponding to the given ID.
	 * It will throw an exception either if no "client_id" argument is present or
	 * if it is present but there is no known client found with that ID.
	 * 
	 * @return IClient
	 *  The requesting client.
	 * 
	 * @throws HttpException
	 */
	public static function getRequestingClient (OAuth2Provider $provider, $raiseException = true)
	{
		// the client identifier must always be supplied when requesting authorization
		$clientId = Request::getString('client_id', $_GET, false, null);

		if ($clientId === null)
		{
			if ($raiseException) 
			{
				throw new OAuthAuthorizationException(
					OAuthAuthorizationException::ERROR_INVALID_REQUEST,
					null,
					null,
					'client_id must be specified when requesting an authorization grant'
				);
			}
			
			return null;
		}

		$client = $provider->getStorage()->fetchClient($clientId);

		if ($client === null)
		{
			if ($raiseException) 
			{
				throw new OAuthAuthorizationException(
					OAuthAuthorizationException::ERROR_ACCESS_DENIED,
					null,
					null,
					'The specified client_id is not registered as an authorized client'
				);
			}
		}

		return $client;
	}
	
	/**
	 * Prepares a given URI (the client's redirection endpoint) and adds the given
	 * arguments to it.
	 * 
	 * @param string $URI
	 *  The original URI to be prepared.
	 * 
	 * @param array $arguments
	 *  An associative array containing the arguments to be added to the URI.
	 * 
	 * @param string $holder
	 *  Defines the holder part of the URI that will contain the arguments 
	 *  ("query" for the querystring, "fragment" for the fragment).
	 * 
	 * @return string
	 *	The prepared URI as a string.
	 * 
	 * @throws RuntimeException
	 */
	public static function prepareRedirectionEndpointURI ($URI, array $arguments, $holder = 'query')
	{
		$URIComponents = parse_url($URI);

		if ($URIComponents === false)
		{
			throw new RuntimeException('URI is malformed and couldn\'t be parsed.');
		}

		if (!isset($URIComponents[$holder]))
		{
			$URIComponents[$holder] = '';
		}

		$queryString = array ();
		parse_str($URIComponents[$holder], $queryString);

		$URIComponents[$holder] = http_build_query(array_merge($queryString, $arguments));

		$URI = $URIComponents['scheme'] . '://' . $URIComponents['host'];

		if (isset($URIComponents['port']))
		{
			$URI .= ':' . $URIComponents['port'];
		}

		if (isset($URIComponents['path']))
		{
			$URI .= $URIComponents['path'];
		}
		else
		{
			$URI .= '/';
		}

		if (isset($URIComponents['query']))
		{
			$URI .= '?' . $URIComponents['query'];
		}

		if (isset($URIComponents['fragment']))
		{
			$URI .= '#' . $URIComponents['fragment'];
		}

		return $URI;
	}
	
	protected abstract function onBeforeValidate ();
	
	protected abstract function onBeforeGrant ();
	
	public abstract function prepare ();
	
	public abstract function validate ();
	
	public abstract function grant ();
	
	public final function handle () {
		$this->prepare();
		
		$this->validate();
		
		$this->grant();
	}
	
}

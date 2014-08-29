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

namespace system\web\authentication\oauth\server\component;

use \system\base\Component;
use \system\web\Request;
use \system\web\Response;
use \system\core\exception\RuntimeException;
use \system\web\exception\HttpException;

use \system\web\authentication\oauth\server\data\ISession;
use \system\web\authentication\oauth\server\role\IClient;
use \system\web\authentication\oauth\server\role\IResourceOwner;
use \system\web\authentication\oauth\server\exception\OAuthAuthorizationException;

/**
 * The OAuth2Provider component implements the OAuth 2.0 Authorization Framework 
 * as described on the official protocol specification (RFC 6749). 
 * 
 * This component provides a basic implementation of the authentication and
 * authorization system. It is meant to be used as a server, on which a set of
 * known third-party applications ("clients") are able to send requests on behalf
 * of a resource owner ("end-user").
 * 
 * At a bare minimum, all that needs to be set up on the application side is the
 * storage class (IStorage).
 * You can of course constomize the component further (see component's setters 
 * documentation for more information on what can be configured). 
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 * @since 0.2.0
 */
class OAuth2Provider extends Component 
{
	
	/**
	 * Route to the action responsible to act as the authorization endpoint.
	 * 
	 * @type array
	 */
	private $authorizationEndpoint = ['/oauth2/authorization'];
	
	/**
	 * Route to the action responsible to act as the token endpoint.
	 * 
	 * @type array
	 */
	private $tokenEndpoint = ['/oauth2/token'];
	
	/**
	 * Route to the action responsible for prompting the user for the authentication
	 * credentials.
	 * 
	 * @type array
	 */
	private $authenticationEndpoint = ['/oauth2/login'];
	
	/**
	 * The session component instance.
	 * 
	 * @type ISession
	 */
	private $session;
	
	/**
	 * The authorization server default class.
	 * 
	 * If you want to implement your own variation of the authorization server, you can
	 * specify it's class name here. 
	 * Make sure your class implements IAuthorizationServer.
	 * 
	 * @type string
	 */	
	private $authorizationServerDefaultClass = 'system\\web\\authentication\\oauth\\server\\role\\AuthorizationServer';
	
	/**
	 * The authorization server instance.
	 * 
	 * @type IAuthorizationServer
	 */
	private $authorizationServer;
	
	/**
	 * The storage default class.
	 * 
	 * You must specify here the class name to your implementation of IStorage.
	 * This is required in order for the Authorization Server to be able to
	 * save and retrieve data.
	 * 
	 * @type string
	 */
	private $storageDefaultClass;
	
	/**
	 * The storage instance.
	 * 
	 * @type IStorage
	 */
	private $storage;
	
	/**
	 * Gets the authorization endpoint route.
	 * 
	 * @return array
	 *  The authorization endpoint route.
	 */
	public function getAuthorizationEndpoint () 
	{
		return $this->authorizationEndpoint;
	}
	
	/**
	 * Gets the token endpoint route.
	 * 
	 * @return array
	 *  The token endpoint route.
	 */
	public function getTokenEndpoint () 
	{
		return $this->tokenEndpoint;
	}
	
	/**
	 * Gets the authentication endpoint route.
	 * 
	 * @return array
	 *  The authentication endpoint route.
	 */
	public function getAuthenticationEndpoint () 
	{
		return $this->authenticationEndpoint;
	}
	
	/**
	 * Gets the default class name for the Authorization Server in use by the 
	 * component.
	 * 
	 * @return string
	 *  The class name for the Authorization Server.
	 */
	public function getAuthorizationServerDefaultClass () 
	{
		return $this->authorizationServerDefaultClass;
	}
	
	/**
	 * Gets the default class name for the Storage in use by the 
	 * component.
	 * 
	 * @return string
	 *  The class name for the Storage.
	 */
	public function getStorageDefaultClass () 
	{
		return $this->storageDefaultClass;
	}
	
	/**
	 * Sets the route to the authorization endpoint action.
	 * 
	 * @param array $authorizationEndpoint
	 */
	public function setAuthorizationEndpoint (array $authorizationEndpoint) 
	{
		$this->authorizationEndpoint = $authorizationEndpoint;
	}
	
	/**
	 * Sets the route to the token endpoint action.
	 * 
	 * @param array $tokenEndpoint
	 */
	public function setTokenEndpoint (array $tokenEndpoint) 
	{
		$this->tokenEndpoint = $tokenEndpoint;
	}
	
	/**
	 * Sets the route to the authentication endpoint action.
	 * 
	 * @param array $authenticationEndpoint
	 */
	public function setAuthenticationEndpoint (array $authenticationEndpoint) 
	{
		$this->authenticationEndpoint = $authenticationEndpoint;
	}
	
	/**
	 * Sets the default class name for the Authorization Server to be used.
	 * 
	 * @param string $authorizationServerDefaultClass
	 */
	public function setAuthorizationServerDefaultClass ($authorizationServerDefaultClass) 
	{
		$this->authorizationServerDefaultClass = $authorizationServerDefaultClass;
	}
	
	/**
	 * Sets the default class name for the Storage to be used.
	 * 
	 * @param string $storageDefaultClass
	 */
	public function setStorageDefaultClass ($storageDefaultClass) 
	{
		$this->storageDefaultClass = $storageDefaultClass;
	}
	
	/**
	 * Gets the current session component.
	 * 
	 * In order for this component to work with full functionality, the session
	 * component in use by the application must implement the ISession interface.
	 * 
	 * @return ISession
	 *  The current session component.
	 */
	public final function getSession () 
	{
		if($this->session === null) 
		{
			$session = $this->getComponent($this->getSessionComponentName());
			
			if($session instanceof ISession) 
			{
				return $this->session = $session;
			}
			
			throw new RuntimeException('OAuth2Provider requires session component to implement the "system\\web\\authentication\\oauth2\\data\\ISession" interface');
		}
		
		return $this->session;
	}
	
	/**
	 * Gets the Storage object currently in use by this component.
	 * 
	 * @return IStorage
	 *  The current storage object.
	 */
	public final function getStorage () 
	{
		if($this->storage === null) 
		{
			$class = $this->getStorageDefaultClass();

			$this->storage = new $class();
		}
		
		return $this->storage;
	}
	
	/**
	 * Gets the Authorization Server currently in use by this component.
	 * 
	 * @return IAuthorizationServer
	 *  The current authorization server object.
	 */
	public final function getAuthorizationServer () 
	{
		if($this->authorizationServer === null) 
		{
			$class = $this->getAuthorizationServerDefaultClass();

			$this->authorizationServer = new $class(array(
				'storage' => $this->getStorage()
			));
		}
		
		return $this->authorizationServer;
	}
	
	/**
	 * Gets the currently authenticated end-user ("resource owner") from the session.
	 * 
	 * @return IResourceOwner
	 *  The currently authenticated end-user if any, or NULL otherwise.
	 */
	public final function getEndUser ()
	{
		return $this->getSession()->getEndUser();
	}
	
	/**
	 * Verifies if there is an end-user currently authenticated.
	 * 
	 * NOTE: Even if the redirect flag is set to TRUE and there is no end-user,
	 * the redirect doesn't stop the script from running. You are responsible for
	 * taking the return value into consideration and stopping the script if 
	 * necessary.
	 * 
	 * @param bool $redirect
	 *  If set to TRUE, there will be issued a response redirection to the authentication
	 *  endpoint in case there is no authenticated end-user.
	 * 
	 * @return bool
	 *  Returns TRUE if there is an end-user currently authenticated, FALSE otherwise.
	 */
	public function endUserAuthenticated ($redirect = false)
	{
		if ($this->getEndUser() === null)
		{
			if ($redirect)
			{
				Response::setLocation($this->getAuthenticationEndpoint());
			}

			return false;
		}

		return true;
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
	public function prepareRedirectionEndpointURI ($URI, array $arguments, $holder = 'query')
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
	public function getRequestingClient ()
	{
		// the client identifier must always be supplied when requesting authorization
		$clientId = Request::getString('client_id', $_GET, false, null);

		if ($clientId === null)
		{
			throw new HttpException(400, 'client_id must be specified when requesting an authorization grant');
		}

		$client = $this->getStorage()->fetchClient($clientId);

		if ($client === null)
		{
			throw new HttpException(400, 'The specified client_id is not registered as an authorized client');
		}

		return $client;
	}

	/**
	 * This method contains all the logic necessary to grant an authorization
	 * code to an application, given a resource owner.
	 * 
	 * You should make sure the given resource owner (end-user) is authenticated
	 * and authorized the grant before calling this method.
	 * 
	 * Once called, this method will handle everything from getting the necessary
	 * data to redirect the end-user to the client redirection endpoint.
	 * 
	 * @param IResourceOwner $endUser
	 *  The end-user ("resource owner") that is granting authorization.
	 * 
	 * @param IClient $client
	 *  The client that requested and is being granted authorization.
	 */
	public final function grantClientAuthorization (IResourceOwner $endUser, IClient $client)
	{
		$authServer = $this->getAuthorizationServer();

		// response_type specifies the type of grant being request,
		// this endpoint supports both "code" and "token" (implicit request),
		// as it is specified on the OAuth2 specs.
		$responseType = Request::getString('response_type', $_GET, false, null);

		// the state is optional and is not handled by the authorization server
		// the contents of state are simply returned back to the client when
		// redirecting the end-user
		$state = Request::getString('state', $_GET, false, null);

		// response type must always be provided
		if ($responseType === null)
		{
			throw new OAuthAuthorizationException
			(
				OAuthAuthorizationException::ERROR_INVALID_REQUEST,
				$state,
				'Response type is required. (Pass response type using the "response_type" parameter)'
			);
		}

		// handle request for an authorization code
		if ($responseType === 'code')
		{
			$authCode = $authServer->grantAuthorizationCode($endUser, $client);

			$redirectURI = $this->prepareRedirectionEndpointURI($client->getRedirectionEndpointURI(), array (
				'code' => $authCode->getCode(),
				'state' => $state
			), 'query');

			Response::setLocation($redirectURI);

			return;
		}

		// handle request for an access token (implicit request)
		else if ($responseType === 'token')
		{
			$token = $authServer->grantImplicitAccessToken($endUser, $client);

			$redirectURI = $this->prepareRedirectionEndpointURI($client->getRedirectionEndpointURI(), array (
				'access_token' => $token->getToken(),
				'token_type' => '',
				//'expires_in' => '3600',
				'state' => $state
			), 'fragment');

			Response::setLocation($redirectURI);

			return;
		}
		
		// response type unknown / unimplemented
		else
		{
			throw new OAuthAuthorizationException
			(
				OAuthAuthorizationException::ERROR_UNSUPPORTED_RESPONSE_TYPE,
				$state,
				'Response type "' . $responseType . '" is unsupported/unknown. Currently implemented response types: "code", "token"'
			);
		}
	}

	/**
	 * Handles a given exception and prepares the redirection URI to then
	 * redirect and report back the error to the client.
	 * 
	 * @param OAuthAuthorizationException $e
	 *  The exception being handled.
	 * 
	 * @param IClient $client
	 *  The requesting client that will receive the error response.
	 */
	public final function handleAuthorizationException (OAuthAuthorizationException $e, IClient $client) {
		$parameters = array
		(
			'error' => $e->getErrorCode()
		);

		if($e->getErrorDescription() !== null)
		{
			$parameters['error_description'] = $e->getErrorDescription();
		}

		if($e->getErrorURI() !== null)
		{
			$parameters['error_uri'] = $e->getErrorURI();
		}

		if($e->getState() !== null)
		{
			$parameters['state'] = $e->getState();
		}
		else
		{
			$state = Request::getString('state', $_GET, false, null);
			
			if($state !== null)
			{
				$parameters['state'] = $state;
			}
		}
		
		$redirectURI = $this->prepareRedirectionEndpointURI
		(
			$client->getRedirectionEndpointURI(),
			$parameters,
			'query'
		);

		Response::setLocation($redirectURI);
	}
	
	/**
	 * This method contains all the logic behind the authorization endpoint.
	 * You can call this method directly into the controller action that is set
	 * as the endpoint and the component will take care of the rest.
	 * 
	 * NOTE: This implementation does not ask the end-user for permission before
	 * granting the authorization! If you want to do so, you must implement that part
	 * of the code on the action and then, if the user authorized the grant, call
	 * the grantClientAuthorization method yourself.
	 * 
	 * You can also write your own implementation and ignore this method, if 
	 * you wish.
	 * 
	 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
	 * 
	 * AUTHORIZATION ENDPOINT
	 *  Used by the client to obtain authorization from the resource owner via 
	 *  user-agent redirection.
	 */
	public function handleAuthorizationEndpoint ()
	{
		// check if there is an end-user currently authenticated.
		// if not, we have to redirect to the authentication promp so that the
		// end-user can authenticate itself
		if (!$this->endUserAuthenticated(true))
		{
			return;
		}

		// get the current end-user and requesting client
		$endUser = $this->getEndUser();
		$client = $this->getRequestingClient();

		try
		{
			$this->grantClientAuthorization($endUser, $client);
		} 
		
		// catch any authorization exceptions
		catch (OAuthAuthorizationException $e) 
		{
			$this->handleAuthorizationException($e, $client);
		}
		
		// turn any other exception into a server error authorization exception
		catch (\Exception $e)
		{
			$this->handleAuthorizationException
			(
				new OAuthAuthorizationException
				(
					OAuthAuthorizationException::ERROR_SERVER_ERROR, 
					null, 
					'The server encountered an unexpected condition that prevented it from fulfilling the request.', 
					null, 
					$e
				),
				
				$client
				
			);
		}
	}

}

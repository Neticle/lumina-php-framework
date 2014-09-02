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

use system\base\Component;
use system\web\Request;
use system\web\Response;
use system\core\exception\RuntimeException;
use system\web\exception\HttpException;

use system\web\authentication\oauth\server\data\ISession;
use system\web\authentication\oauth\server\role\IClient;
use system\web\authentication\oauth\server\role\IResourceOwner;
use system\web\authentication\oauth\server\flow\AuthorizationFlow;
use system\web\authentication\oauth\server\exception\OAuthAuthorizationException;


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
	
	private $authorizationFlows = [
		'code' => [
			'class' => 'system\\web\\authentication\\oauth\\server\\flow\\AuthorizationCodeFlow'
		],
		
		'token' => [
			'class' => 'system\\web\\authentication\\oauth\\server\\flow\\ImplicitTokenFlow'
		],
		
		'client_credentials' => [
			'class' => 'system\\web\\authentication\\oauth\\server\\flow\\ClientCredentialsFlow'
		]
	];
	
	public function setAuthorizationFlows (array $authorizationFlows)
	{
		foreach ($authorizationFlows as $responseType => $flow) 
		{
			if(!is_array($flow) || !isset($flow['class']))
			{
				continue;
			}
						
			$this->setAuthorizationFlow($responseType, $flow);
		}
	}
	
	public function setAuthorizationFlow ($responseType, array $flow)
	{
		$this->authorizationFlows[$responseType] = $flow;
	}
		
	public function getAuthorizationFlowInstance ($responseType)
	{
		$flow = isset($this->authorizationFlows[$responseType]) ? $this->authorizationFlows[$responseType] : null;
		
		if($flow === null || !is_array($flow) || !isset($flow['class']))
		{
			return null;
		}
		
		$class = $flow['class'];
		
		$flow = array_merge($flow, array(
			'provider' => $this,
			'authorizationServer' => $this->getAuthorizationServer()
		));
		
		unset($flow['class']);
		
		return new $class($flow);
	}
	
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
	 * This method contains all the logic necessary to grant an authorization
	 * code to an application, given a resource owner.
	 * 
	 * You should make sure the given resource owner (end-user) is authenticated
	 * and authorized the grant before calling this method.
	 * 
	 * Once called, this method will handle everything from getting the necessary
	 * data to redirect the end-user to the client redirection endpoint.
	 * 
	 * @throws OAuthAuthorizationException
	 */
	public final function getAuthorizationFlowHandler ()
	{
		// response_type specifies the type of grant being request,
		// this endpoint supports both "code" and "token" (implicit request),
		// as it is specified on the OAuth2 specs.
		$responseType = Request::getString('response_type', $_GET, false, null);

		// response type must always be provided
		if ($responseType === null)
		{
			throw new OAuthAuthorizationException
			(
				OAuthAuthorizationException::ERROR_INVALID_REQUEST,
				null,
				AuthorizationFlow::getRequestingClient($this, false),
				'Response type is required. (Pass response type using the "response_type" parameter)'
			);
		}

		$flowHandler = $this->getAuthorizationFlowInstance($responseType);
		
		if($flowHandler === null)
		{
			$implemented = implode(', ', array_keys($this->authorizationFlows));
			
			throw new OAuthAuthorizationException
			(
				OAuthAuthorizationException::ERROR_UNSUPPORTED_RESPONSE_TYPE,
				null,
				AuthorizationFlow::getRequestingClient($this, false),
				'Response type "' . $responseType . '" is unsupported/unknown. Currently implemented response types: ' . $implemented
			);
		}
		
		return $flowHandler;
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
	 *  If none provided, the error will be presented on the response body
	 *  instead, in JSON format.
	 */
	public final function handleAuthorizationException (OAuthAuthorizationException $e) 
	{
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
		
		if($e->getClient() !== null) 
		{
			$redirectURI = AuthorizationFlow::prepareRedirectionEndpointURI
			(
				$e->getClient()->getRedirectionEndpointURI(),
				$parameters,
				'query'
			);

			Response::setLocation($redirectURI);
		}
		else
		{
			Response::setStatus(400);
			Response::setHeader('Content-Type', 'application/json;charset=UTF-8');
			
			echo json_encode($parameters);
		}
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
		try
		{
			$handler = $this->getAuthorizationFlowHandler();
			
			$handler->handle();
		} 
		
		// catch any authorization exceptions
		catch (OAuthAuthorizationException $e) 
		{
			$this->handleAuthorizationException($e);
		}
		
		// turn any other exception into a server error authorization exception
		/*catch (\Exception $e)
		{
			$this->handleAuthorizationException
			(
				new OAuthAuthorizationException
				(
					OAuthAuthorizationException::ERROR_SERVER_ERROR, 
					null, 
					AuthorizationFlow::getRequestingClient($this, false),
					'The server encountered an unexpected condition that prevented it from fulfilling the request. ' . $e->getMessage(), 
					null, 
					$e
				)
			);
		}*/
	}

}

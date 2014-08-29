<?php

namespace system\web\authentication\oauth\server\component;

use \system\base\Component;
use \system\web\Request;
use \system\web\Response;
use \system\core\exception\RuntimeException;
use \system\web\exception\HttpException;

use \system\web\authentication\oauth\server\data\ISession;
use \system\web\authentication\oauth\server\role\IClient;
use \system\web\authentication\oauth\server\role\IResourceOwner;

class OAuth2Provider extends Component {
	
	/**
	 * Route to the action responsible to act as the authorization endpoint
	 * 
	 * @var array 
	 */
	private $authorizationEndpoint = array('/oauth2/authorization');
	
	/**
	 * Route to the action responsible to act as the token endpoint
	 * 
	 * @var array 
	 */
	private $tokenEndpoint = array('/oauth2/token');
	
	/**
	 * Route to the action responsible for prompting the user for the authentication
	 * credentials.
	 * 
	 * @var type 
	 */
	private $authenticationEndpoint = array('/oauth2/login');
	
	/**
	 * The session component instance.
	 */
	private $session = null;
	
	/**
	 * The authorization server default class.
	 * If you want to implement your own variation of the authorization, you can
	 * specify it's class name here. Make sure your class implements IAuthorizationServer.
	 */	
	private $authorizationServerDefaultClass = 'system\\web\\authentication\\oauth\\server\\role\\AuthorizationServer';
	
	/**
	 * The authorization server instance
	 */
	private $authorizationServer = null;
	
	/**
	 * The storage default class.
	 * Specify here the class name to your implementation of IStorage.
	 */
	private $storageDefaultClass;
	
	/**
	 * The storage instance.
	 */
	private $storage;
	
	public function getAuthorizationEndpoint () {
		return $this->authorizationEndpoint;
	}
	
	public function getTokenEndpoint () {
		return $this->tokenEndpoint;
	}
	
	public function getAuthenticationEndpoint () {
		return $this->authenticationEndpoint;
	}
	
	public function getSessionComponentName () {
		return $this->sessionComponentName;
	}
	
	public function getAuthorizationServerDefaultClass () {
		return $this->authorizationServerDefaultClass;
	}
	
	public function getStorageDefaultClass () {
		return $this->storageDefaultClass;
	}
	
	public function setAuthorizationEndpoint ($authorizationEndpoint) {
		return $this->authorizationEndpoint = $authorizationEndpoint;
	}
	
	public function setTokenEndpoint ($tokenEndpoint) {
		return $this->tokenEndpoint = $tokenEndpoint;
	}
	
	public function setAuthenticationEndpoint ($authenticationEndpoint) {
		$this->authenticationEndpoint = $authenticationEndpoint;
	}
	
	public function setSessionComponentName ($sessionComponentName) {
		$this->sessionComponentName = $sessionComponentName;
	}
	
	public function setAuthorizationServerDefaultClass ($authorizationServerDefaultClass) {
		$this->authorizationServerDefaultClass = $authorizationServerDefaultClass;
	}
	
	public function setStorageDefaultClass ($storageDefaultClass) {
		$this->storageDefaultClass = $storageDefaultClass;
	}
	
	/**
	 * Gets the current session component.
	 * In order for this component to work with full functionality, the session
	 * component in use by the application must implement the ISession interface.
	 * 
	 * @return ISession
	 *  The current session component.
	 */
	public final function getSession () {
		if($this->session === null) {
			$session = $this->getComponent($this->getSessionComponentName());
			
			if(!($session instanceof ISession)) {
				throw new RuntimeException('OAuth2Provider requires session component to implement the "system\\web\\authentication\\oauth2\\data\\ISession" interface');
			}
			
			$this->session = $session;
		}
		
		return $this->session;
	}
	
	public final function getStorage () {
		if($this->storage === null) {
			$class = $this->getStorageDefaultClass();

			$this->storage = new $class();
		}
		
		return $this->storage;
	}
	
	public final function getAuthorizationServer () {
		if($this->authorizationServer === null) {
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
	public final function getEndUser () {
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
	 *  If set to TRUE, there will be issue a redirect to the authentication
	 *  endpoint in case there is no authenticated end-user.
	 * 
	 * @return bool
	 *  Returns TRUE if there is an end-user currently authenticated, FALSE otherwise.
	 */
	public function endUserAuthenticated ($redirect = false) {
		if($this->getEndUser() === null) {
			if($redirect) {
				Response::setLocation($this->getAuthenticationEndpoint());
			}
			
			return false;
		}
		
		return true;
	}
		
	public function prepareRedirectionEndpointURI ($URI, array $arguments, $holder = 'query') {
		$URIComponents = parse_url($URI);

		if($URIComponents === false) {
			throw new RuntimeException('URI is malformed and couldn\'t be parsed.');
		}
		
		if(!isset($URIComponents[$holder])) {
			$URIComponents[$holder] = '';
		}
		
		$queryString = array();
		parse_str($URIComponents[$holder], $queryString);

		$URIComponents[$holder] = http_build_query(array_merge($queryString, $arguments));

		$URI = $URIComponents['scheme'] . '://' . $URIComponents['host'];
		
		if(isset($URIComponents['port'])) {
			$URI .= ':' . $URIComponents['port'];
		}
		
		if(isset($URIComponents['path'])) {
			$URI .= $URIComponents['path'];
		} else {
			$URI .= '/';
		}
		
		if(isset($URIComponents['query'])) {
			$URI .= '?' . $URIComponents['query'];
		}
		
		if(isset($URIComponents['fragment'])) {
			$URI .= '#' . $URIComponents['fragment'];
		}
		
		return $URI;
	}
	
	public function getRequestingClient () {
		// the client identifier must always be supplied when requesting authorization
		$clientId = Request::getString('client_id', $_GET, false, null);
		
		if($clientId === null) {
			throw new HttpException(400, 'client_id must be specified when requesting an authorization grant');
		}
		
		$client = $this->getStorage()->fetchClient($clientId);
		
		if($client === null) {
			throw new HttpException(400, 'The specified client_id is not registered as an authorized client');
		}
		
		return $client;
	}
	
	/**
	 * This method contains all the logic necessary to grant an authorization
	 * code to an application, given a resource owner.
	 * You should make sure the given resource owner (end-user) is authenticated
	 * and authorized the grant before calling this method.
	 * Once called, this method will handle everything from getting the necessary
	 * data from the request to redirect the end-user to the client endpoint.
	 * 
	 */
	public function grantClientAuthorization (IResourceOwner $endUser, IClient $client) {
		$authServer = $this->getAuthorizationServer();
		
		// response_type specifies the type of grant being request,
		// this endpoint supports both "code" and "token" (implicit request),
		// as it is specified on the OAuth2 specs.
		$responseType = Request::getString('response_type', $_GET, false, null);
		
		// the state is optional and is not handled by the authorization server
		// the contents of state are simply returned back to the client when
		// redirecting the end-user
		$state = Request::getString('state', $_GET, false, null);
		
		if($responseType === null) {
			// redirect back to client - add &error=invalid_request
			$redirectURI = $this->prepareRedirectionEndpointURI($client->getRedirectionEndpointURI(), array(
				'error' => 'invalid_request'
			));
			
			Response::setLocation($redirectURI);
			
			return;
		}
		
		// handle request for an authorization code
		if($responseType === 'code') {
			$authCode = $authServer->grantAuthorizationCode($endUser, $client);
			
			$redirectURI = $this->prepareRedirectionEndpointURI ($client->getRedirectionEndpointURI(), array(
				'code' => $authCode->getCode(),
				'state' => $state
			), 'query');

			Response::setLocation($redirectURI);
			
			return;
		}
		
		// handle request for an access token (implicit request)
		else if($responseType === 'token') {
			$token = $authServer->grantImplicitAccessToken($endUser, $client);
			
			$redirectURI = $this->prepareRedirectionEndpointURI ($client->getRedirectionEndpointURI(), array(
				'access_token' => $token->getToken(),
				'token_type' => '',
				//'expires_in' => '3600',
				'state' => $state
			), 'fragment');
			
			Response::setLocation($redirectURI);
			
			return;
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
	public function handleAuthorizationEndpoint () {
		// check if there is an end-user currently authenticated.
		// if not, we have to redirect to the authentication promp so that the
		// end-user can authenticate itself
		if(!$this->endUserAuthenticated(true)) {
			return;
		}
		
		// get the current end-user and requesting client
		$endUser = $this->getEndUser();
		$client = $this->getRequestingClient();
		
		try {
			$this->grantClientAuthorization($endUser, $client);
		} /*catch (OAuthAuthorizationException $e) {
			$redirectURI = $this->prepareRedirectionEndpointURI ($client->getRedirectionEndpointURI(), array(
				'error' => $e->getOAuthErrorToken(),
				'errorMsg' => $e->getOAuthErrorMessage()
			), 'query');

			Response::setLocation($redirectURI);
		}*/ catch (\Exception $e) {
			$redirectURI = $this->prepareRedirectionEndpointURI ($client->getRedirectionEndpointURI(), array(
				'error' => 'server_error',
				'errorMsg' => 'The server encountered an internal problem an cannot fulfill the request at the moment.'
			), 'query');

			Response::setLocation($redirectURI);
		}
	}
	
}

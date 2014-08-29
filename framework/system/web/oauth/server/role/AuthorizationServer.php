<?php

namespace system\web\oauth\server\role;

use \system\core\Express;
use \system\web\oauth\server\data\AuthCode;
use \system\web\oauth\server\data\AccessToken;
use \system\web\oauth\server\data\IStorage;

class AuthorizationServer extends Express implements IAuthorizationServer {
	
	private $storage;
	
	public function __construct(array $attributes = null) {
		parent::__construct($attributes);
	}
	
	public function setStorage (IStorage $storage) {
		$this->storage = $storage;
	}
	
	protected function getStorage () {
		return $this->storage;
	}
	
	protected function generateToken ($salt = null, $length = 32) {
		// TODO: Implement an actual token generator
		return md5($salt . uniqid('', true));
	}
	
	protected function buildAuthorizationCode (IResourceOwner $owner, IClient $client) {
		$expiry = new \DateTime('now');
		$expiry->modify('+5 minute');
		
		$code = new AuthCode(array(
			'clientId' => $client->getIdentifier(),
			'code' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'expirationDate' => $expiry,
			'ownerId' => $owner->getIdentifier()
		));
		
		return $code;
	}
	
	public final function grantAuthorizationCode(IResourceOwner $owner, IClient $client) {
		$code = $this->buildAuthorizationCode($owner, $client);
		
		$this->getStorage()->storeAuthorizationCode($code);
		
		return $code;
	}

	protected function buildImplicitAccessToken (IResourceOwner $owner, IClient $client) {
		$expiry = new \DateTime('now');
		$expiry->modify('+1 hour');
		
		$code = new AccessToken(array(
			'clientId' => $client->getIdentifier(),
			'token' => $this->generateToken($client->getIdentifier() . $owner->getIdentifier()),
			'expirationDate' => $expiry,
			'ownerId' => $owner->getIdentifier()
		));
		
		return $code;
	}
	
	public function grantImplicitAccessToken(IResourceOwner $owner, IClient $client) {
		$token = $this->buildImplicitAccessToken($owner, $client);
		
		$this->getStorage()->storeAccessToken($token);
		
		return $token;
	}
	
	public function grantByClientCredentials() {
		
	}

	public function grantByResourceOwnerCredentials(array $credentials, IClient $client = null) {
		
	}

}

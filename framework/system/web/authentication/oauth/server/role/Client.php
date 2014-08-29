<?php

namespace system\web\authentication\oauth\server\role;

use \system\core\Express;

class Client extends Express implements IClient {
	
	private $identifier;
	
	private $type;
	
	private $profile;
	
	private $redirectionEndpointURI;
	
	public function __construct(array $attributes = null) {
		parent::__construct($attributes);
	}
	
	public function getIdentifier () {
		return $this->identifier;
	}
	
	public function getType () {
		return $this->type;
	}
	
	public function getProfile () {
		return $this->profile;
	}
	
	public function getRedirectionEndpointURI () {
		return $this->redirectionEndpointURI;
	}
	
	public function setIdentifier ($identifier) {
		$this->identifier = $identifier;
	}
	
	public function setType ($type) {
		$this->type = $type;
	}
	
	public function setProfile ($profile) {
		$this->profile = $profile;
	}
	
	public function setRedirectionEndpointURI ($redirectionEndpointURI) {
		$this->redirectionEndpointURI = $redirectionEndpointURI;
	}
	
}

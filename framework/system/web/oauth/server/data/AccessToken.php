<?php

namespace system\web\oauth\server\data;

use \system\core\Express;

class AccessToken extends Express implements IAccessToken {
	
	private $token;
	
	private $ownerId;
	
	private $clientId;
	
	private $expirationDate;
	
	public function getToken() {
		return $this->token;
	}

	public function getOwnerId() {
		return $this->ownerId;
	}

	public function getClientId() {
		return $this->clientId;
	}

	public function getExpirationDate() {
		return $this->expirationDate;
	}

	public function setToken($token) {
		$this->token = $token;
	}

	public function setOwnerId($ownerId) {
		$this->ownerId = $ownerId;
	}

	public function setClientId($clientId) {
		$this->clientId = $clientId;
	}

	public function setExpirationDate(\DateTime $expirationDate) {
		$this->expirationDate = $expirationDate;
	}

	public function isValid() {
		$expiration = $this->getExpirationDate();
		
		return $expiration < new \DateTime('now');
	}
	
	public function getType () {
		return IAccessToken::TYPE_BEARER;
	}
	
}

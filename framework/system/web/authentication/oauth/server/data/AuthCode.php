<?php

namespace system\web\authentication\oauth\server\data;

use \system\core\Express;

class AuthCode extends Express implements IAuthCode {
	
	private $clientId;
	private $code;
	private $expirationDate;
	private $ownerId;
	
	public function getClientId() {
		return $this->clientId;
	}

	public function getCode() {
		return $this->code;
	}

	public function getExpirationDate() {
		return $this->expirationDate;
	}

	public function getOwnerId() {
		return $this->ownerId;
	}

	public function setClientId($clientId) {
		$this->clientId = $clientId;
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public function setExpirationDate(\DateTime $expirationDate) {
		$this->expirationDate = $expirationDate;
	}

	public function setOwnerId($ownerId) {
		$this->ownerId = $ownerId;
	}
	
	public function isValid() {
		$expiration = $this->getExpirationDate();
		
		return $expiration < new \DateTime('now');
	}

}

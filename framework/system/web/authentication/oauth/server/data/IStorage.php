<?php

namespace system\web\authentication\oauth\server\data;

interface IStorage {
	
	public function storeAuthorizationCode (IAuthCode $code);
	
	public function fetchAuthorizationCode ($code);
	
	public function storeAccessToken (IAccessToken $token);
	
	public function fetchAccessToken ($token);
	
	public function fetchClient ($clientId);
	
}

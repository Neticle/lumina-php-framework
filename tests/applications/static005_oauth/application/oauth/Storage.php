<?php

namespace application\oauth;

use \system\web\oauth\server\data\IStorage;
use \system\web\oauth\server\data\IAuthCode;
use \system\web\oauth\server\data\IAccessToken;
use \system\web\oauth\server\role\IClient;
use \system\web\oauth\server\role\Client;

class Storage implements IStorage {

	private static function getHardcodedClients () {
		return array(
	
			'ID_CLIENT_1' => array(
				'identifier' => 'ID_CLIENT_1',
				'type' => IClient::TYPE_CONFIDENTIAL,
				'profile' => IClient::PROFILE_WEB_APPLICATION,
				'redirectionEndpointURI' => 'https://thirdpartyapplication1/oauth/callback/'
			),
		
			'ID_CLIENT_2' => array(
				'identifier' => 'ID_CLIENT_2',
				'type' => IClient::TYPE_PUBLIC,
				'profile' => IClient::PROFILE_UA_BASED_APPLICATION,
				'redirectionEndpointURI' => 'https://thirdpartyapplication2/'
			),
		
			'ID_CLIENT_3' => array(
				'identifier' => 'ID_CLIENT_3',
				'type' => IClient::TYPE_PUBLIC,
				'profile' => IClient::PROFILE_NATIVE_APPLICATION,
				'redirectionEndpointURI' => 'https://thirdpartyapplication3/'
			)
			
		);
	}
	
	public function storeAuthorizationCode (IAuthCode $code) {
		
	}
	
	public function fetchAuthorizationCode ($code) {
	
	}
	
	public function storeAccessToken (IAccessToken $token) {
	
	}
	
	public function fetchAccessToken ($token) {
		
	}
	
	public function fetchClient ($clientId) {
		$clients = self::getHardCodedClients();
		
		return isset($clients[$clientId]) ? 
			new Client($clients[$clientId]) : null;
	}	
}

?>

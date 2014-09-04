<?php

namespace application\controller;

use \system\web\Controller;

class OauthController extends Controller {

	public function actionAuthorization () {
		// to test:
		// index.php?action=oauth.authorization&client_id=ID_CLIENT_1&response_type=code&state=xyz
		
		// should redirect to something like:
		// https://thirdpartyapplication1/oauth/callback/?code=ID_CLIENT_1154008576828c18.80171297&state=xyz
		
		$this->getComponent('oauthProvider')
			->handleAuthorizationEndpoint();
	}
	
	public function actionToken () {
		/*$this->getComponent('oauthProvider')
			->handleTokenEndpoint();*/
	}

}

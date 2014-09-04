<?php

namespace application\session;

use \system\web\oauth\server\data\ISession;
use application\model\User;

class Session extends \system\web\session\DefaultSession implements ISession {

	public function getEndUser () {
		// Our dummy session will always have an hard-coded user logged in
		// for testing purposes
		
		return new User('default', array(
			'id' => '1',
			'name' => 'John Doe'
		));
	}
	
}

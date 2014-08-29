<?php

namespace application\model;

use \system\data\Model;
use \system\web\oauth\server\role\IResourceOwner;

class User extends Model implements IResourceOwner {
	
	public function getIdentifier () {
		return $this->getAttribute('id');
	}
	
}

<?php

// =============================================================================
//
// Copyright 2013 Neticle
// http://lumina.neticle.com
//
// This file is part of "Lumina/PHP Framework", hereafter referred to as 
// "Lumina".
//
// Lumina is free software: you can redistribute it and/or modify it under the 
// terms of the GNU General Public License as published by the Free Software 
// Foundation, either version 3 of the License, or (at your option) any later
// version.
//
// Lumina is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
// A PARTICULAR PURPOSE. See theGNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License along with
// "Lumina". If not, see <http://www.gnu.org/licenses/>.
//
// =============================================================================

namespace application\model;

use \system\sql\data\Record;
use \system\web\authentication\oauth\server\role\IResourceOwner;

class User extends Record implements IResourceOwner
{
	/**
	 * Returns the base User model instance in the specified context.
	 *
	 * @param string $context
	 *	The context to return the model in.
	 *
	 * @return User
	 *	The user model instance.
	 */
	public static function model ($context = 'default')
	{
		return parent::getBaseModel(__CLASS__, $context);
	}
	
	protected function getTableName ()
	{
		return 'user';
	}
	
	public function getIdentifier ()
	{
		return $this->getAttribute('id');
	}
	
	public function findByCredentials ($username, $password)
	{
		if($username === 'igorazevedo' && $password === 'luminatest')
		{
			return new User('default', array(
				'id' => '1',
				'username' => 'igorazevedo',
				'name' => 'Igor Azevedo'
			));
		}
		
		return null;
		
		// this is totally insecure, hash your passwords properly!
		// for testing purposes only.
		/*return $this->findByAttributes(array(
			'username' => $username,
			'password' => sha1($password)
		));*/
	}

}


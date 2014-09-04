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

/**
 * The User record. This is a simplistic implementation for demonstration purposes,
 * does not contain validations or anything else.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class User extends Record implements IResourceOwner
{

	public static function model ($context = 'default')
	{
		return parent::getBaseModel(__CLASS__, $context);
	}
	
	protected function getTableName ()
	{
		return 'user';
	}
	
	public function getOAuthIdentifier ()
	{
		return $this->getAttribute('id');
	}
	
	/**
	 * Finds a user record, given a set of credentials.
	 * 
	 * @param string $username
	 *  The username.
	 * 
	 * @param string $password
	 *  The password.
	 * 
	 * @return User
	 *  The matching user record, if any.
	 */
	public function findByCredentials ($username, $password)
	{
		$user = $this->findByAttributes(array(
			'username' => $username,
			'active' => 1
		));
		
		if($user !== null)
		{
			$digest = $this->getComponent('passwordDigest');
						
			if($digest->compare($password, $user->password))
			{
				// NOTE: For a proper blowfish hashing solution, you might also want 
				// to check the hash cost here and update it if necessary.
				
				return $user;
			}
		}
		
		return null;
	}

}


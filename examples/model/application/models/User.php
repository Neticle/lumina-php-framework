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

namespace application\models;

use \system\data\Model;

/**
 * The user model.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package application.controllers
 * @since 0.2.0
 */
class User extends Model
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
	public static function model($context = 'default')
	{
		return parent::getBaseModel(__CLASS__, $context);
	}
	
	/**
	 * Returns the validation rules.
	 *
	 * @return array
	 *	The model validation rules.
	 */
	protected function getValidationRules()
	{
		return array(
		
			// These attributes are always required
			array('required', 'username, email, age'),
			
			// Passwords are only required when creating the user
			array('required', 'password', 'context' => 'create'),
			
			// The length of username and password must be between 4 and 12
			array('length', 'username, password', 'minimum' => 4, 'maximum' => 12),
			
			// The length of email must be between 8 and 128
			array('length', 'email', 'minimum' => 8, 'maximum' => 128),
			
			// The email... is an email
			array('email', 'email'),
			
			// The age must be an unsigned integer, ranging from 13 to 125
			array('range', 'age', 'integer' => true, 'minimum' => 13, 'maximum' => 125)
			
		);
	}
	
	/**
	 * This method encapsulates the 'afterValidation' event.
	 *
	 * @param string[] $attributes
	 *	The name of the attributes being validated.
	 *
	 * @return bool
	 *	Returns FALSE to cancel the validation event, TRUE otherwise.
	 */
	protected function onAfterValidation(array $attributes)
	{
		// This shows you how you can extend validation
		if (in_array('email', $attributes) && !$this->hasAttributeErrors('email'))
		{
			$email = $this->getAttribute('email');
			
			if (substr($email, strrpos($email, '@')) !== '@neticle.com')
			{
				$this->addAttributeError('email', 'Email address is not on Neticle\'s domain.');		
			}
		}
		
		return true;
	}
}


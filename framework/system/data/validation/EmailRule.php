<?php

// =============================================================================
//
// Copyright 2013 Neticle, Igor Azevedo
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

namespace system\data\validation;

use \system\data\validation\Rule;

/**
 * EmailRule.
 *
 * @author Neticle Portugal <lumina@incubator.neticle.com>
 * @revision 201310141922AZOT
 * @since 0.1.0
 */
class EmailRule extends Rule {

	/**
	 * Regular expressions describing email patterns, indexed by name.
	 *
	 * @type array
	 */
	private static $patterns = array(
		'basic' => '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/i'
	);
	
	/**
	 * The error message to be reported to the model when the attribute fails
	 * validation.
	 *
	 * @type int
	 */
	protected $message = 'The value of "{attribute}" is not an acceptable email address.';

	/**
	 * The name of the pre-defined pattern to match the email against, or a 
	 * regex to be used during validation.
	 *
	 * @type string
	 */
	private $pattern = 'basic';
	
	/**
	 * Defines the name of the pre-defined pattern to match the email against,
	 * or a regex to be used during validation.
	 *
	 * @param string $pattern
	 *	The pattern to be used for email validation.
	 */
	public function setPattern($pattern) {
		$this->pattern = $pattern;
	}
	
	/**
	 * Returns the pattern name or regular expression to be used when validating
	 * email addresses.
	 *
	 * @return string
	 *	The email address validation pattern.
	 */
	public function getPattern() {
		return $this->pattern;
	}

	/**
	 * Validates the given attribute for the specified model instance.
	 *
	 * @param Model $model
	 *	The instance of the model being validated.
	 *
	 * @param string $attribute
	 *	The name of the attribute to be validated.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	protected function validateAttributeValue($model, $attribute, $value) {
		
		if (parent::validateAttributeValue($model, $attribute, $value))
		{
			// Get the email pattern alias or regex to be used
			$pattern = $this->pattern;
		
			if(isset(self::$patterns[$pattern]))
			{
				$pattern = self::$patterns[$pattern];
			}
					
			if(preg_match($pattern, $value)) 
			{
				return true;
			}
		}
		
		$this->report($model, $attribute);
		return false;
	}

}


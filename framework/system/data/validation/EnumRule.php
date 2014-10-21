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

namespace system\data\validation;

use \system\data\IValidatableDataContainer;
use \system\data\validation\Rule;

/**
 * Validates a string by making sure its value matches one of the
 * enumerated options.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.validation
 * @since 0.2.0
 */
class EnumRule extends Rule {
	
	/**
	 * The error message to be reported to the model when the attribute fails
	 * validation.
	 *
	 * @type int
	 */
	protected $message = 'The value of "{attribute}" is not acceptable.';

	/**
	 * The minimum length for a value to be considered valid.
	 *
	 * @type int
	 */
	private $options = null;
	
	/**
	 * Defines the values an attribute can have in order to
	 * pass validation.
	 *
	 * @param string|string[] $options
	 *	An array or CSV string defining the valid values.
	 */
	public function setOptions($options) 
	{
		if (is_string($options)) 
		{
			$options = preg_split('/(\s*\,\s*)/', $options, -1, PREG_SPLIT_NO_EMPTY);
		}
	
		$this->options = $options;
	}
	
	/**
	 * Returns the values an attribute can have in order to
	 * pass validation.
	 *
	 * @return string[]
	 *	An array of valid values.
	 */
	public function getOptions() 
	{
		return $this->options;
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
	public function validateAttributeValue(IValidatableDataContainer $model, $attribute, $value) 
	{		
		if (!in_array($value, $this->options)) 
		{
			// Register the error message
			$this->report($model, $attribute);			
			return false;		
		}
		
		return true;
	}

}


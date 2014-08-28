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

use \system\data\Model;
use \system\data\validation\Rule;

/**
 * Validates a string by making sure it matches a required minimum and/or
 * maximum length.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.validation
 * @since 0.2.0
 */
class LengthRule extends Rule 
{	
	/**
	 * The error message to be reported to the model when the attribute fails
	 * validation.
	 *
	 * @type int
	 */
	protected $message = 'The value of "{attribute}" does not meet the required ([{minimum}..{maximum}]) length.';

	/**
	 * The minimum length for a value to be considered valid.
	 *
	 * @type int
	 */
	private $minimum = 0;
	
	/**
	 * The maximum length for a value to be considered valid.
	 *
	 * @type int
	 */
	private $maximum = 255;
	
	/**
	 * Defines the minimum length for a value to be considered valid.
	 *
	 * @param int $minimum
	 *	The minimum length requirement.
	 */
	public function setMinimum($minimum) 
	{
		$this->minimum = $minimum;
	}
	
	/**
	 * Returns the minimum length for a value to be considered valid.
	 *
	 * @return int
	 *	The minimum length requirement.
	 */
	public function getMinimum() 
	{
		return $this->minimum;
	}
	
	/**
	 * Defines the maximum length for a value to be considered valid.
	 *
	 * @param int $maximum
	 *	The maximum length requirement.
	 */
	public function setMaximum($maximum)
	{
		$this->maximum = $maximum;
	}
	
	/**
	 * Returns the maximum length for a value to be considered valid.
	 *
	 * @return int
	 *	The maximum length requirement.
	 */
	public function getMaximum() 
	{
		return $this->maximum;
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
	public function validateAttributeValue(Model $model, $attribute, $value) 
	{		
		$length = strlen($value);
		
		if($length < $this->minimum || $length > $this->maximum) 
		{
			$this->report
			(
				$model, 
				$attribute,
				[
					'minimum' => $this->minimum,
					'maximum' => $this->maximum
				]
			);
			
			return false;
		}
		
		return true;
	}

}


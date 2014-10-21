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
use \system\data\validation\NumericRule;

/**
 * Validates an attribute by making sure it's a number between
 * a certain range.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.validation
 * @since 0.2.0
 */
class RangeRule extends NumericRule {

	/**
	 * The minimum accepted value.
	 *
	 * @type int
	 */
	private $minimum = 0;
	
	/**
	 * The maximum accepted value.
	 *
	 * @type int
	 */
	private $maximum = 0;
	
	/**
	 * The message to be reported back to the model when one of the attributes
	 * fails validation due to it being empty when a value is required.
	 *
	 * @type string
	 */
	protected $message = 'Value of "{attribute}" is not a valid number in the required range.';
	
	/**
	 * Defines the minimum required for a value to be considered valid.
	 *
	 * @param int $minimum
	 *	The minimum requirement.
	 */
	public function setMinimum($minimum)
	{
		$this->minimum = $minimum;
	}
	
	/**
	 * Returns the minimum required for a value to be considered valid.
	 *
	 * @return int
	 *	The minimum requirement.
	 */
	public function getMinimum()
	{
		return $this->minimum;
	}
	
	/**
	 * Defines the maximum required for a value to be considered valid.
	 *
	 * @param int $maximum
	 *	The maximum requirement.
	 */
	public function setMaximum($maximum)
	{
		$this->maximum = $maximum;
	}
	
	/**
	 * Returns the maximum required for a value to be considered valid.
	 *
	 * @return int
	 *	The maximum requirement.
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
	public function validateAttributeValue(IValidatableDataContainer $model, $attribute, $value)
	{
		if (parent::validateAttributeValue($model, $attribute, $value))
		{
			if ($value < $this->minimum || $value > $this->maximum)
			{
				// Register the error message
				$this->report($model, $attribute);
				return false;
			}
			
			return true;
		}
		
		return false;
	}

}


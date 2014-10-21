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
 * Validates an attribute by making sure it's a number.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.validation
 * @since 0.2.0
 */
class NumericRule extends Rule {

	/**
	 * The minimum length for a value to be considered valid.
	 *
	 * @type bool
	 */
	private $integer = false;
	
	/**
	 * A flag indicating wether an only an unsigned value is accepted.
	 *
	 * @type bool
	 */
	private $unsigned = false;
	
	/**
	 * A flag indicating wether the attribute should be converted to its
	 * matching PHP type when validation succeeds.
	 *
	 * @type bool
	 */
	private $convert = true;
	
	/**
	 * The error message to be reported to the model when the attribute fails
	 * validation.
	 *
	 * @type int
	 */
	protected $message = 'The value of "{attribute}" is not a valid number.';
	
	/**
	 * Defines a flag indicating wether or not only integer numbers are
	 * considered valid.
	 *
	 * @param bool $integer
	 *	A flag indicating the numeric requirement.
	 */
	public function setInteger($integer) 
	{
		$this->integer = $integer;
	}
	
	/**
	 * Returns a flag indicating wether or not only integer numbers are
	 * considered valid.
	 *
	 * @return bool
	 *	A flag indicating the numeric requirement.
	 */
	public function isInteger() 
	{
		return $this->integer;
	}
	
	/**
	 * Defines a flag indicating wether or not only unsigned numbers are
	 * considered valid.
	 *
	 * @param bool $unsigned
	 *	A flag indicating the numeric requirement.
	 */
	public function setUnsigned($unsigned) 
	{
		$this->unsigned = $unsigned;
	}
	
	/**
	 * Returns a flag indicating wether or not only unsigned numbers are
	 * considered valid.
	 *
	 * @return bool
	 *	A flag indicating the numeric requirement.
	 */
	public function isUnsigned() 
	{
		return $this->unsigned;
	}
	
	/**
	 * Defines a flag indicating wether the attribute should be converted to its
	 * matching PHP type when validation succeeds.
	 *
	 * @param bool $convert
	 *	When set to TRUE the value will be converted to its matching type.
	 */
	public function setConvert($convert)
	{
		$this->convert = $convert;
	}
	
	/**
	 * Returns a flag indicating wether the attribute should be converted to its
	 * matching PHP type when validation succeeds.
	 *
	 * @return bool
	 *	Returns TRUE when the value is to be converted to its matching type.
	 */
	public function isConvert()
	{
		return $this->convert;
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
		
		// Determine the regex to use
		$regex = $this->integer ? '/^\d+$/' : '/^((\d+(\.\d+)?)|(\.\d+))$/';
		
		if (preg_match($regex, $value) < 1 || ($this->unsigned && $value < 0)) 
		{
		
			// Register the error message
			$this->report($model, $attribute);
			return false;
		
		}
		
		if ($this->convert)
		{
			$value = $this->integer ?
				((int) $value) : ((float) $value);
				
			$model->setAttribute($attribute, $value);
		}
		
		return true;
	}

}


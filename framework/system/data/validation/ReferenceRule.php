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
use \system\sql\Criteria;

/**
 * Validates an attribute by making sure it's value references an
 * existant table record.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.validation
 * @since 0.2.0
 */
class ReferenceRule extends Rule {

	/**
	 * The message to report when the attribute fails validation.
	 *
	 * @type string
	 */
	protected $message = 'The value of "{attribute}" is not valid.';
	
	/**
	 * The table to verify referenced values from.
	 *
	 * @type string
	 */
	private $table;
	
	/**
	 * The column to verify referenced values from.
	 *
	 * @type string
	 */
	private $column;
	
	/**
	 * Defines the table to verify referenced values from.
	 *
	 * @param string $table
	 *	The table to verify referenced values from.
	 */
	public function setTable($table)
	{
		$this->table = $table;
	}
	
	/**
	 * Returns the table to verify referenced values from.
	 *
	 * @return string
	 *	The table to verify referenced values from.
	 */
	public function getTable()
	{
		return $this->table;
	}
	
	/**
	 * Defines the column to verify referenced values from.
	 *
	 * @param string $column
	 *	The column to verify referenced values from.
	 */
	public function setColumn($column)
	{
		$this->column = $column;
	}
	
	/**
	 * Returns the column to verify referenced values from.
	 *
	 * @return string
	 *	The column to verify referenced values from.
	 */
	public function getColumn()
	{
		return $this->column;
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
		if (!$this->column || !$this->table)
		{
			throw new RuntimeException('Value of "column" and "table" properties can not be empty.');
		}
	
		$criteria = new Criteria();
		$criteria->setAlias('t');
		$criteria->setSelect('COUNT(*)');
		$criteria->setCondition($this->column . '=:value');
		$criteria->setParameter(':value', $value);
		
		// Perform the count statement
		$db = $this->getComponent('database');
		$count = (int) $db->select($this->table, $criteria, true);
		
		if ($count < 1) 
		{
			$this->report($model, $attribute);
			return false;
		}
		
		return true;
		
	}

}


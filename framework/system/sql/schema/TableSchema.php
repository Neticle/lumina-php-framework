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

namespace system\sql\schema;

use \system\sql\schema\Schema;
use \system\core\exception\RuntimeException;

/**
 * The table schema object.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql.schema
 * @since 0.2.0
 */
class TableSchema extends Schema
{
	/**
	 * The table columns, indexed by name.
	 *
	 * @type array
	 */
	private $columns = array();
	
	/**
	 * The names of the columns composing the table primary key.
	 *
	 * @type string[]
	 */
	private $primaryKey;

	/**
	 * Constructor.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct(array $configuration = null)
	{
		parent::__construct($configuration);
	}
	
	/**
	 * Defines the database columns.
	 *
	 * @param array $columns
	 *	An array defining the table columns. Each column may be an instance
	 *	or an express configuration array.
	 *
	 * @param bool $merge
	 *	When set to TRUE the new columns will be merged with the already
	 *	existent ones, replacing any columns with a matching name.
	 */
	public function setColumns(array $columns, $merge = true)
	{
		$collection = array();
		
		foreach ($columns as $column)
		{
			if (is_array($column))
			{
				$column = new ColumnSchema($column);
			}
			
			$collection[$column->getName()] = $column;
		}
		
		$this->columns = $merge ?
			array_replace($this->columns, $collection) : $collection;
	}
	
	/**
	 * Returns a table schema.
	 *
	 * @throws RuntimeException
	 *	Thrown if the table schema is not defined.
	 *
	 * @param string $table
	 *	The name of the table to get the schema for.
	 *
	 * @return TableSchema
	 *	The table schema.
	 */
	public function getColumn($column)
	{
		if (isset($this->columns[$column]))
		{
			return $this->columns[$column];
		}
		
		throw new RuntimeException('Column "' . $column . '" is not defined.');
	}
	
	/**
	 * Adds a new column.
	 *
	 * @param Column|array $column
	 *	The column to be added as either an instance or an express
	 *	configuration array.
	 */
	public function addColumn($column)
	{
		if (is_array($column))
		{
			$column = new ColumnSchema($column);
		}
		
		$this->columns[$column->getName()] = $column;
	}
	
	/**
	 * Checks wether or not a column is defined.
	 *
	 * @param string $column
	 *	The name of the column to be verified.
	 *
	 * @return bool
	 *	Returns TRUE if the column is defined, FALSE otherwise.
	 */
	public function hasColumn($column)
	{
		return isset($this->columns[$column]);
	}
	
	/**
	 * Returns an array containing all table columns, indexed by name.
	 *
	 * @return array
	 *	The database columns.
	 */
	public function getColumns()
	{
		return $this->columns;
	}
	
	/**
	 * Defines the table primary key.
	 *
	 * @param string[] $primaryKey
	 *	The names of the columns composing the table primary key.
	 */
	public function setPrimaryKey(array $primaryKey)
	{
		$this->primaryKey = $primaryKey;
	}
	
	/**
	 * Returns the table primary key.
	 *
	 * @return string[]
	 *	The names of the columns composing the table primary key.
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}
}


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

/**
 * The database schema object.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql.schema
 * @since 0.2.0
 */
class DatabaseSchema extends Schema
{
	/**
	 * The database tables, indexed by name.
	 *
	 * @type array
	 */
	private $tables = array();

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
	 * Defines the database tables.
	 *
	 * @param array $tables
	 *	An array defining the database tables. Each table may be an instance
	 *	or an express configuration array.
	 *
	 * @param bool $merge
	 *	When set to TRUE the new tables will be merged with the already
	 *	existent ones, replacing any tables with a matching name.
	 */
	public function setTables(array $tables, $merge = true)
	{
		$collection = array();
		
		foreach ($tables as $table)
		{
			if (is_array($table))
			{
				$table = new TableSchema($table);
			}
			
			$collection[$table->getName()] = $table;
		}
		
		$this->tables = $merge ?
			array_replace($this->tables, $collection) : $collection;
	}
	
	/**
	 * Adds a new table.
	 *
	 * @param Table|array $table
	 *	The table to be added as either an instance or an express
	 *	configuration array.
	 */
	public function addTable($table)
	{
		if (is_array($table))
		{
			$table = new TableSchema($table);
		}
		
		$this->tables[$table->getName()] = $table;
	}
	
	/**
	 * Checks wether or not a table is defined.
	 *
	 * @param string $table
	 *	The name of the table to be verified.
	 *
	 * @return bool
	 *	Returns TRUE if the table is defined, FALSE otherwise.
	 */
	public function hasTable($table)
	{
		return isset($this->tables[$table]);
	}
	
	/**
	 * Returns an array containing all database tables, indexed by name.
	 *
	 * @return array
	 *	The database tables.
	 */
	public function getTables()
	{
		return $this->tables;
	}
}


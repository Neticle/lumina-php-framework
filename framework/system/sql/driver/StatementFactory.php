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

namespace system\sql\driver;

use \system\sql\Statement;
use \system\sql\driver\Driver;

/**
 * An abstract database driver to provide access to the driver-specific
 * database schema and statement factory instances.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql.driver
 * @since 0.2.0
 */
abstract class StatementFactory
{
	/**
	 * The Driver this statement factory belongs to.
	 *
	 * @type Driver
	 */
	private $driver;
	
	/**
	 * Constructor.
	 *
	 * @param Driver $driver
	 *	The driver this statement factory belongs to.
	 */
	public function __construct(Driver $driver)
	{
		$this->driver = $driver;
	}
	
	/**
	 * Returns the driver this statement factory belongs to.
	 *
	 * @return Driver
	 *	The parent driver instance.
	 */
	public function getDriver()
	{
		return $this->driver;
	}
	
	/**
	 * Returns the connection of the driver this statement factory
	 * belongs to.
	 *
	 * @return Connection
	 *	The connection the parent driver belongs to.
	 */
	public function getDriverConnection()
	{
		return $this->driver->getParent();
	}
	
	/**
	 * Prepares a new statement for execution.
	 *
	 * @param string $statement
	 *	The SQL statement to prepare.
	 *
	 * @param array $parameters
	 *	An associative array holding the values to be bound to the
	 *	statement parameters.
	 *
	 */
	protected function prepare($statement, array $parameters = null)
	{
		$statement = new Statement($this->driver->getParent(), $statement);
		
		if (isset($parameters))
		{
			$statement->bindAll($parameters);
		}
		
		return $statement;
	}
	
	/**
	 * Quotes the given field or table name.
	 *
	 * @param string $name
	 *	The name to quote, which may be prefixed with a table and/or
	 *	database name.
	 *
	 * @return string
	 *	The quoted name.
	 */
	protected function quote($name)
	{
		return $this->driver->quote($name);
	}
	
	/**
	 * Creates a new SELECT statement.
	 *
	 * @param string $table
	 *	The name of the table to select from.
	 *
	 * @param Criteria|array $criteria
	 *	An instance of Criteria or it's express configuration array.
	 *
	 * @return Statement
	 *	The created Statement instance, ready for use.
	 */
	public abstract function createSelectStatement($table, $criteria = null);
	
	/**
	 * Creates a new DELETE statement.
	 *
	 * @param string $table
	 *	The name of the table to select from.
	 *
	 * @param Criteria|array $criteria
	 *	An instance of Criteria or it's express configuration array.
	 *
	 * @return Statement
	 *	The created Statement instance, ready for use.
	 */
	public abstract function createDeleteStatement($table, $criteria = null);
	
	/**
	 * Creates a new UPDATE statement.
	 *
	 * @param string $table
	 *	The name of the table to select from.
	 *
	 * @param array $fields
	 *	An associative array holding the values to define, indexed by
	 *	field name.
	 *
	 * @param Criteria|array $criteria
	 *	An instance of Criteria or it's express configuration array.
	 *
	 * @return Statement
	 *	The created Statement instance, ready for use.
	 */
	public abstract function createUpdateStatement($table, array $fields, $criteria = null);
	
	/**
	 * Creates a new UPDATE statement.
	 *
	 * @param string $table
	 *	The name of the table to select from.
	 *
	 * @param array $fields
	 *	An associative array holding the values to define, indexed by
	 *	field name.
	 *
	 * @return Statement
	 *	The created Statement instance, ready for use.
	 */
	public abstract function createInsertStatement($table, array $fields);
}


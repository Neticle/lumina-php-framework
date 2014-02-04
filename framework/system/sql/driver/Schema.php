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

use \system\core\Extension;
use \system\sql\Connection;

/**
 * Provides access to the database schema.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql.schema
 * @since 0.2.0
 */
abstract class Schema extends Extension
{
	/**
	 * The connection instance to use.
	 *
	 * @type Connection
	 */
	private $connection;

	/**
	 * Constructor.
	 *
	 * @param Driver $driver
	 *	The parent driver instance.
	 */
	public function __construct(Driver $driver)
	{
		parent::__construct($driver);
		$this->connection = $driver->getConnection();
	}
	
	/**
	 * Returns the connection instance to use.
	 *
	 * @return Connection
	 *	The connection instance to use.
	 */
	public function getConnection()
	{
		return $this->connection;
	}
	
	/**
	 * Returns the database schema.
	 *
	 * @param bool $refresh
	 *	When set to TRUE any previously cached schema information will be
	 *	invalidated and re-fetched.
	 *
	 * @return DatabaseSchema
	 *	The database schema.
	 */
	public function getDatabaseSchema($refresh = false)
	{
		$cache = $this->getComponent('cache');
		$connection = $this->getConnection();
		$dsn = $connection->getDsn();
		$key = 'sql.schema:' . $dsn;
		
		if ($refresh || !($result = $cache->read($key)))
		{
			$result = $this->fetchDatabaseSchema();
			$cache->write($key, $result);
		}
		
		return $result;
	}
	
	/**
	 * Returns the table schema.
	 *
	 * @param string $table
	 *	The table to fetch the schema from.
	 *
	 * @param bool $refresh
	 *	When set to TRUE any previously cached schema information will be
	 *	invalidated and re-fetched.
	 *
	 * @return TableSchema
	 *	The table schema.
	 */
	public function getTableSchema($table, $refresh = false)
	{
		$cache = $this->getComponent('cache');
		$connection = $this->getConnection();
		$dsn = $connection->getDsn();
		$key = 'sql.schema.table:' . $table . ';' . $dsn;
		
		if ($refresh || !($result = $cache->read($key)))
		{
			$result = $this->fetchTableSchema($table);
			$cache->write($key, $result);
		}
		
		return $result;
	}
	
	/**
	 * Returns the column schema.
	 *
	 * @param string $table
	 *	The table to fetch the schema from.
	 *
	 * @param string $column
	 *	The column to fetch the schema from.
	 *
	 * @param bool $refresh
	 *	When set to TRUE any previously cached schema information will be
	 *	invalidated and re-fetched.
	 *
	 * @return ColumnSchema
	 *	The column schema.
	 */
	public function getColumnSchema($table, $column, $refresh = false)
	{
		$cache = $this->getComponent('cache');
		$connection = $this->getConnection();
		$dsn = $connection->getDsn();
		$key = 'sql.schema.table:' . $table . ';' . $column . ';' . $dsn;
		
		if ($refresh || !($result = $cache->read($key)))
		{
			$result = $this->fetchColumnSchema($table, $column);
			$cache->write($key, $result);
		}
		
		return $result;
	}
	
	/**
	 * Fetches the database schema.
	 *
	 * @return DatabaseSchema
	 *	The database schema.
	 */
	protected abstract function fetchDatabaseSchema();
	
	/**
	 * Fetches the table schema.
	 *
	 * @param string $table
	 *	The table to fetch the schema from.
	 *
	 * @return TableSchema
	 *	The table schema.
	 */
	protected abstract function fetchTableSchema($table);
	
	/**
	 * Fetches the table schema.
	 *
	 * @param string $table
	 *	The table to fetch the schema from.
	 *
	 * @param string $column
	 *	The column to fetch the schema from.
	 *
	 * @return ColumnSchema
	 *	The column schema.
	 */
	protected abstract function fetchColumnSchema($table, $column);
}


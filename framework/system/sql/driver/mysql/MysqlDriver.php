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

namespace system\sql\driver\mysql;

use \system\sql\driver\Driver;
use \system\sql\driver\mysql\MysqlSchema;
use \system\sql\driver\mysql\MysqlStatementFactory;

/**
 * An abstract database driver to provide access to the driver-specific
 * database schema and statement factory instances.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql.driver
 * @since 0.2.0
 */
class MysqlDriver extends Driver
{
	/**
	 * The driver specific schema implementation.
	 *
	 * @type Schema
	 */
	private $schema;
	
	/**
	 * The driver specific statement factory.
	 *
	 * @type StatementFactory
	 */
	private $statementFactory;

	/**
	 * Returns the driver specific statement factory implementation.
	 *
	 * @return StatementFactory
	 *	The driver statement factory implementation.
	 */
	public function getStatementFactory()
	{
		if (!isset($this->statementFactory))
		{
			$this->statementFactory = new MysqlStatementFactory($this);
		}
		
		return $this->statementFactory;
	}
	
	/**
	 * Returns the driver specific schema implementation.
	 *
	 * @return Schema
	 *	The driver schema implementation.
	 */
	public function getSchema()
	{
		if (!isset($this->schema))
		{
			$this->schema = new MysqlSchema($this);
		}
		
		return $this->schema;
	}
	
	/**
	 * Returns the driver name as a lowercased string.
	 *
	 * @return string
	 *	The driver name.
	 */
	public function getName()
	{
		return 'mysql';
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
	public function quote($name)
	{
		return '`' . str_replace(array('`', '.'), array('\\\`', '`.`'), $name) . '`';
	}
	
	/**
	 * Creates the schema instance.
	 *
	 * @param array $schema
	 *	The MySQL schema associative array.
	 *
	 * @return DatabaseSchema
	 *	The schema instance.
	 */
	private function createDatabaseSchema($schema)
	{
		$database = new DatabaseSchema();
		$database->setName($schema['SCHEMA_NAME']);
		$database->setCharset($schema['DEFAULT_CHARACTER_SET_NAME']);
		return $database;
	}
	
	/**
	 * Locks the specified tables.
	 *
	 * @throws RuntimeException
	 *	Thrown when the table fails to be locked.
	 *
	 * @param string[] $tables
	 *	The name(s) of the table(s) to lock.
	 *
	 * @param int $type
	 *	The lock type, as defined by Schema::LOCK_* constants.
	 */
	public function setTableLocks(array $tables, $type = self::LOCK_WRITE)
	{
		switch ($type)
		{
			case self::LOCK_WRITE:
				$type = 'WRITE';
				break;
			
			case self::LOCK_READ:
				$type = 'READ';
				break;
				
			default:
				$type = 'WRITE';
				break;
		}
		
		foreach ($tables as $i => $table)
		{
			$tables[$i] = $this->quote($table) . ' ' . $type;
		}
		
		$this->getConnection()->run('LOCK TABLES ' . implode(', ', $tables));
	}
	
	/**
	 * Releases any existing table locks.
	 *
	 * @throws RuntimeException
	 *	Thrown when the tables fail to be release.
	 */
	public function releaseAllLocks()
	{
		$this->getConnection()->run('UNLOCK TABLES');
	}	
}


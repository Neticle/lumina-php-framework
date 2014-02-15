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

use \system\core\exception\RuntimeException;
use \system\sql\Reader;
use \system\sql\driver\Driver;
use \system\sql\driver\Schema;
use \system\sql\schema\DatabaseSchema;
use \system\sql\schema\TableSchema;
use \system\sql\schema\ColumnSchema;

/**
 * Provides access to the database schema.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql.schema
 * @since 0.2.0
 */
class MysqlSchema extends Schema
{
	/**
	 * Constructor.
	 *
	 * @param Driver $driver
	 *	The parent driver instance.
	 */
	public function __construct(Driver $driver)
	{
		parent::__construct($driver);
	}
	
	/**
	 * Fetches the database schema.
	 *
	 * @return DatabaseSchema
	 *	The database schema.
	 */
	protected function fetchDatabaseSchema()
	{
		$connection = $this->getConnection();
		
		// Get the database schema
		$statement = 'SELECT * FROM information_schema.SCHEMATA ' .
			'WHERE SCHEMA_NAME=DATABASE() LIMIT 1';
		
		$schema = $connection->query($statement)->fetch(Reader::FETCH_ASSOC, true);
		
		$database = $this->createDatabaseSchema($schema);
		
		// Fetch the schema for all tables
		$statement = 'SELECT TABLE_NAME FROM information_schema.TABLES ' .
			'WHERE TABLE_SCHEMA=DATABASE()';
			
		$tables = $connection->query($statement)->fetchColumn(0);
		
		foreach ($tables as $table)
		{
			$database->addTable($this->fetchTableSchema($table));
		}
		
		return $database;		
	}
	
	/**
	 * Fetches the table schema.
	 *
	 * @param string $table
	 *	The table to fetch the schema from.
	 *
	 * @return TableSchema
	 *	The table schema.
	 */
	protected function fetchTableSchema($table)
	{
		$connection = $this->getConnection();
		
		// Fetch the table schema
		$statement = 'SELECT * FROM information_schema.TABLES ' .
			'WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=:table LIMIT 1';
		
		$parameters = array(
			':table' => $table
		);
		
		$schema = $connection->query($statement, $parameters)
			->fetch(Reader::FETCH_ASSOC, true);
			
		if (!$schema)
		{
			throw new RuntimeException('Table "' . $table . '" schema not found.');
		}
		
		$table = $this->createTableSchema($schema);
		$primaryKey = array();
		
		// Fetch the columns schema
		$statement = 'SELECT * FROM information_schema.COLUMNS ' .
			'WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=:table';
		
		$reader = $connection->query($statement, $parameters);
		
		while ($schema = $reader->fetch(Reader::FETCH_ASSOC))
		{
			$table->addColumn($this->createColumnSchema($schema));
			
			if ($schema['COLUMN_KEY'] === 'PRI')
			{
				$primaryKey[] = $schema['COLUMN_NAME'];
			}
			
		}
		
		if (isset($primaryKey[0]))
		{
			$table->setPrimaryKey($primaryKey);
		}
		
		return $table;			
	}
	
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
	protected function fetchColumnSchema($table, $column)
	{
		$connection = $this->getConnection();
		
		$statement = 'SELECT * FROM information_schema.COLUMNS ' .
			'WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=:table ' .
			'AND COLUMN_NAME=:column';
		
		$schema = $connection->query($statement, array(
			':table' => $table,
			':column' => $column
		));
		
		return $this->createColumnSchema($schema);
	}
	
	/**
	 * Creates the schema instance.
	 *
	 * @param array $schema
	 *	The MySQL schema associative array.
	 *
	 * @return TableSchema
	 *	The schema instance.
	 */
	private function createTableSchema($schema)
	{
		$table = new TableSchema();
		$table->setName($schema['TABLE_NAME']);
		$table->setCharset($schema['TABLE_COLLATION']);
		return $table;
	}
	
	/**
	 * Creates the schema instance.
	 *
	 * @param array $schema
	 *	The MySQL schema associative array.
	 *
	 * @return ColumnSchema
	 *	The schema instance.
	 */
	private function createColumnSchema($schema)
	{	
		$column = new ColumnSchema();
		$column->setName($schema['COLUMN_NAME']);
		$column->setCharset($schema['CHARACTER_SET_NAME']);
		$column->setRequired($schema['IS_NULLABLE'] === 'NO');
		$column->setAutoIncrementable($schema['EXTRA'] === 'auto_increment');
	
		// Determine the column data type and size
		switch (strtolower($schema['DATA_TYPE']))
		{
			// char/strings
			case 'char':
			case 'varchar':
				$size = (int) $schema['CHARACTER_MAXIMUM_LENGTH'];
				$column->setType($size === 1 ? 'char' : 'string');
				$column->setSize($size);
				break;
			
			case 'tinytext':
			case 'text':
			case 'mediumtext':
			case 'longtext':
				$column->setType('string');
				$column->setSize($schema['CHARACTER_MAXIMUM_LENGTH']);
				break;
			
			// binary
			case 'binary':
			case 'varbinary':
			case 'tinyblob':
			case 'blob':
			case 'mediumblob':
			case 'longblob':
				$column->setType('binary');
				$column->setSize($schema['CHARACTER_OCTET_LENGTH']);
				break;
			
			// integers
			case 'integer':
			case 'int':
			case 'smallint':
			case 'tinyint':
			case 'mediumint':
			case 'bigint':
				$column->setType('int');
				$column->setSize($schema['NUMERIC_SCALE']);
				break;
				
			// floats
			case 'numeric':
			case 'decimal':
			case 'float':
			case 'double':
				$column->setType('float');
				$column->setSize($schema['NUMERIC_SCALE'] + (((int) $schema['NUMERIC_PRECISION']) / 100));
				break;
				
			// enum
			case 'enum':
				$column->setType('enum');
				$column->setSize($schema['CHARACTER_MAXIMUM_LENGTH']);
				// TODO parse enum options
				break;
			
			// timestamp
			case 'timestamp':
				$column->setType('timestamp');
				break;
		}
		
		return $column;
	}	
}


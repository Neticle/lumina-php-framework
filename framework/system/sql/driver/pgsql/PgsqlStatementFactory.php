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

namespace system\sql\driver\pgsql;

use \system\sql\Criteria;
use \system\sql\Expression;
use \system\sql\driver\StatementFactory;
use \system\sql\driver\pgsql\PgsqlDriver;

/**
 * An abstract database driver to provide access to the driver-specific
 * database schema and statement factory instances.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class PgsqlStatementFactory extends StatementFactory
{
	/**
	 * Constructor.
	 *
	 * @param Driver $driver
	 *	The driver this statement factory belongs to.
	 */
	public function __construct(PgsqlDriver $driver)
	{
		parent::__construct($driver);
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
	public function createSelectStatement($table, $criteria = null)
	{
		$table = $this->quote($table);
	
		if (isset($criteria))
		{
			if (is_array($criteria))
			{
				$criteria = new Criteria($criteria);
			}
			
			$criteria = $criteria->toArray();
			$statement = 'SELECT ';
			
			if ($criteria['distinct'])
			{
				$statement .= 'DISTINCT ';
			}
			
			// SELECT {fields}
			$statement .= $criteria['select'] ?
				$criteria['select'] : '*';
				
			// ... FROM {table} {alias}
			$statement .= ' FROM ' . $table;
			
			if ($criteria['alias'])
			{
				$statement .= ' ' . $this->quote($criteria['alias']);
			}
			
			// ... JOIN
			if ($criteria['join'])
			{
				$statement .= ' ' . $criteria['join'];
			}
			
			// ... WHERE {condition}
			if ($criteria['condition'])
			{
				$statement .= ' WHERE ' . $criteria['condition'];
			}
			
			// ... GROUP BY
			if ($criteria['group'])
			{
				$statement .= ' GROUP BY ' . $criteria['group'];
			}
			
			// ... ORDER BY
			if ($criteria['sort'])
			{
				$statement .= ' ORDER BY ' . $criteria['sort'];
			}
			
			// ... LIMIT
			if ($criteria['limit'])
			{
				$statement .= ' LIMIT ' . $criteria['limit'];
			}
			
			// ... OFFSET
			if ($criteria['offset'])
			{
				$statement .= ' OFFSET ' . $criteria['offset'];
			}
			
			$parameters = $criteria['parameters'];
			
		}
		else
		{
			$statement = 'SELECT * FROM ' . $table;
			$parameters = null;
		}
		
		return $this->prepare($statement, $parameters);
	}
	
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
	public function createDeleteStatement($table, $criteria = null)
	{
		$table = $this->quote($table);
	
		if (isset($criteria))
		{
			if (is_array($criteria))
			{
				$criteria = new Criteria($criteria);
			}
			
			$criteria = $criteria->toArray();
			$alias = $criteria['alias'] ? $this->quote($criteria['alias']) : false;
			
			// DELETE {table|alias} 
			$statement = 'DELETE ' . ($alias ? $alias : $table);
				
			// ... FROM {table} {alias}
			$statement .= ' FROM ' . $table;
			
			if ($alias)
			{
				$statement .= ' ' . $alias;
			}
			
			// ... JOIN
			if ($criteria['join'])
			{
				$statement .= ' ' . $criteria['join'];
			}
			
			// ... WHERE {condition}
			if ($criteria['condition'])
			{
				$statement .= ' WHERE ' . $criteria['condition'];
			}
			
			$parameters = $criteria['parameters'];
			
		}
		else
		{
			$statement = 'DELETE FROM ' . $table;
			$parameters = null;
		}
		
		return $this->prepare($statement, $parameters);
	}
	
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
	public function createUpdateStatement($table, array $fields, $criteria = null)
	{
		$definitions = array();
		$parameters = array();
		$pid = 0;
		
		foreach ($fields as $field => $value)
		{	
			if ($value instanceof Expression)
			{
				$value = $value->toString();
			}
			else
			{
				$key = ':sfcp_' . ++$pid;
				$parameters[$value] = $value;
				$value = $key;
			}
			
			$definitions[] = $this->quote($field) . '=' . $value;
		}
		
		$table = $this->quote($table);
		$definitions = implode(', ', $definitions);
		
		if (isset($criteria))
		{
			if (is_array($criteria))
			{
				$criteria = new Criteria($criteria);
			}
			
			$criteria = $criteria->toArray();
			
			// UPDATE {table} {alias}
			$statement = 'UPDATE ' . $table;
			
			if ($criteria['alias'])
			{
				$statement .= ' ' . $criteria['alias'];
			}
			
			// ... JOIN
			if ($criteria['join'])
			{
				$statement .= ' ' . $criteria['join'];
			}
			
			// ... WHERE {condition}
			if ($criteria['condition'])
			{
				$statement .= ' WHERE ' . $criteria['condition'];
			}
			
			if ($criteria['parameters'])
			{
				$parameters += $criteria['parameters'];
			}
		}
		else
		{
			$statement = 'UPDATE ' . $table . ' SET ' . $definitions;
		}
		
		return $this->prepare($statement, $parameters);
	}
	
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
	public function createInsertStatement($table, array $fields)
	{
		$names = array();
		$values = array();
		$parameters = array();
		$pid = 0;
		
		foreach ($fields as $name => $value)
		{
			if ($value instanceof Expression)
			{
				$value = $value->toString();
			}
			else
			{
				$key = ':sfcp_' . ++$pid;
				$parameters[$key] = $value;
				$value = $key;
			}
		
			$names[] = $this->quote($name);
			$values[] = $value;
		}
		
		$statement = 'INSERT INTO ' . $this->quote($table) 
			. ' (' . implode(', ', $names) . ') VALUES ('
			. implode(', ', $values) . ')';
			
		return $this->prepare($statement, $parameters);		
	}
}


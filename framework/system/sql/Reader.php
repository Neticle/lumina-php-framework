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

namespace system\sql;

use \system\base\Extension;
use \system\sql\Reader;

/**
 * Reader.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql
 * @since 0.2.0
 */
class Reader
{
	const FETCH_ASSOC = \PDO::FETCH_ASSOC;
	const FETCH_NUM = \PDO::FETCH_NUM;
	const FETCH_BOTH = \PDO::FETCH_BOTH;
	const FETCH_OBJ = \PDO::FETCH_OBJ;
	const FETCH_NAMED = \PDO::FETCH_NAMED;

	/**
	 * The parent statement instance.
	 *
	 * @type Statement
	 */
	private $statement;
	
	/**
	 * The underlying PDOStatement instance.
	 *
	 * @type \PDOStatement
	 */
	private $pdoStatement;
	
	/**
	 * Constructor.
	 *
	 * @throws \PDOException
	 *	Thrown when the statement fails to execute.
	 *
	 * @param Statement $statement
	 *	The parent statement instance.
	 */
	public function __construct(Statement $statement)
	{
		$this->statement = $statement;
		$this->pdoStatement = $statement->getPDOStatement();
		$this->pdoStatement->execute();
	}
	
	/**
	 * Returns the parent statement instance.
	 *
	 * @return Statement
	 *	The parent statement instance.
	 */
	public function getStatement()
	{
		return $this->statement;
	}
	
	/**
	 * Fetches the next row in the current result set.
	 *
	 * @throws \PDOException
	 *	Thrown when the fetch operation fails due to an unexpected error.
	 *
	 * @param int $mode
	 *	The fetch mode identifier, as defined by the Reader::FETCH_* constants.
	 *
	 * @param bool $close
	 *	When set to TRUE the underlying cursor will be closed before the row
	 *	is returned for use.
	 *
	 * @return mixed
	 *	The next row according to the specified fetch mode or FALSE, if there
	 *	are no more rows available.
	 */
	public function fetch($mode = self::FETCH_BOTH, $close = false)
	{
		$result = $this->pdoStatement->fetch($mode);
		
		if ($close)
		{
			$this->close();
		}
		
		return $result;
	}
	
	/**
	 * Fetches the remaining rows in the current result set.
	 *
	 * @throws \PDOException
	 *	Thrown when the fetch operation fails due to an unexpected error.
	 *
	 * @param int $mode
	 *	The fetch mode identifier, as defined by the Reader::FETCH_* constants.
	 *
	 * @param bool $close
	 *	When set to TRUE the underlying cursor will be closed before the rows
	 *	are returned for use.
	 *
	 * @return mixed[]
	 *	The rows according to the specified fetch mode.
	 */
	public function fetchAll($mode = self::FETCH_BOTH, $close = true)
	{
		$result = $this->pdoStatement->fetchAll($mode);
		
		if ($close)
		{
			$this->close();
		}
		
		return $result;
	}
	
	/**
	 * Fetches the first cell of the next row available in the current
	 * result set.
	 *
	 * @param bool $close
	 *	When set to TRUE the underlying cursor will be closed before the cell
	 *	value is returned for use.
	 *
	 * @return mixed
	 *	Returns the cell value or NULL if there are no more rows available.
	 */
	public function fetchScalar($close = true)
	{
		$row = $this->pdoStatement->fetch(self::FETCH_NUM);
		$result = isset($row[0]) ? $row[0] : null;
		
		if ($close)
		{
			$this->close();
		}
		
		return $result;
	}
	
	/**
	 * Fetches the remaining cells in the current result set.
	 *
	 * @throws \PDOException
	 *	Thrown when the fetch operation fails due to an unexpected error.
	 *
	 * @param int $index
	 *	The zero-based index of the column to fetch.
	 *
	 * @param bool $close
	 *	When set to TRUE the underlying cursor will be closed before the rows
	 *	are returned for use.
	 *
	 * @return array
	 *	The remaining cells.
	 */
	public function fetchColumn($index = 0, $close = true)
	{
		$result = $this->pdoStatement->fetchAll(\PDO::FETCH_COLUMN, $index);
		
		if ($close)
		{
			$this->close();
		}
		
		return $result;
	}
	
	/**
	 * Advances the underlying cursor the next result set.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE if there's no more result sets available.
	 */
	public function nextResult()
	{
		return $this->pdoStatement->nextRowset();
	}
	
	/**
	 * Disposes all result sets and closes the underlying cursor.
	 *
	 * @throws \PDOException
	 *	Thrown when the underlying cursor fails to close.
	 */
	public function close()
	{
		while ($this->pdoStatement->nextRowset());
		$this->pdoStatement->closeCursor();
	}
	
}


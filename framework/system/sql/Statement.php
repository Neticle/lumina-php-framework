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

use \system\core\Element;
use \system\sql\Reader;

/**
 * Statement.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class Statement extends Element
{
	/**
	 * Defines the type for a boolean parameter.
	 *
	 * @type int
	 */
	const PARAM_BOOL = \PDO::PARAM_BOOL;
	
	/**
	 * Defines the type for a null parameter.
	 *
	 * @type int
	 */
	const PARAM_NULL = \PDO::PARAM_NULL;
	
	/**
	 * Defines the type for an integer parameter.
	 *
	 * @type int
	 */
	const PARAM_INT = \PDO::PARAM_INT;
	
	/**
	 * Defines the type for a string parameter.
	 *
	 * @type int
	 */
	const PARAM_STR = \PDO::PARAM_STR;
	
	/**
	 * Defines the type for a binary parameter.
	 *
	 * @type int
	 */
	const PARAM_LOB = \PDO::PARAM_LOB;
	
	/**
	 * The database connection wrapper to be used by this statement.
	 *
	 * @type Connection
	 */
	private $connection;
	
	/**
	 * The underlying PDO statement wrapper.
	 *
	 * @type \PDOStatement
	 */
	private $pdoStatement;
	
	/**
	 * The original SQL statement.
	 *
	 * @type string
	 */
	private $sqlStatement;
	
	/**
	 * Constructor.
	 *
	 * @throws \PDOException
	 *	Thrown when PDO fails to prepare the statement.
	 *
	 * @param Connection $connection
	 *	The parent connection instance.
	 *
	 * @param string $statement
	 *	The SQL statement to prepare.
	 */
	public function __construct(Connection $connection, $statement)
	{
		parent::__construct(null);
		
		$pdo = $connection->getPDO();
		
		$this->connection = $connection;
		$this->sqlStatement = $statement;
		$this->pdoStatement = $pdo->prepare($statement);
	}
	
	/**
	 * Returns the connection this statement belongs to.
	 *
	 * @return Connection
	 *	The parent connection instance.
	 */
	public function getConnection()
	{
		return $this->connection;
	}
	
	/**
	 * Returns the underlying PDOStatement instance.
	 *
	 * @return PDOStatement
	 *	The underlying PDOStatement handle.
	 */
	public function getPDOStatement()
	{
		return $this->pdoStatement;
	}
	
	/**
	 * Returns the underlying SQL statement.
	 *
	 * @return string
	 *	The underlying SQL statement.
	 */
	public function getSQLStatement()
	{
		return $this->sqlStatement;
	}
	
	/**
	 * Binds a value to a parameter.
	 *
	 * @throws \PDOException
	 *	Thrown the parameter fails to bind.
	 *
	 * @param string $name
	 *	The name of the parameter to bind to.
	 *
	 * @param mixed $value
	 *	The value to bind.
	 *
	 * @param int $type
	 *	The explicit type for the parameter value, as defined by the
	 *	Statement::PARAM_* constants.
	 */
	public function bind($name, $value, $type = self::PARAM_STR)
	{
		$this->pdoStatement->bindValue($name, $value, $type);
	}
	
	/**
	 * Binds a value to a parameter, by reference.
	 *
	 * @throws \PDOException
	 *	Thrown the parameter fails to bind.
	 *
	 * @param string $name
	 *	The name of the parameter to bind to.
	 *
	 * @param mixed $value
	 *	The value to bind.
	 *
	 * @param int $type
	 *	The explicit type for the parameter value, as defined by the
	 *	Statement::PARAM_* constants.
	 */
	public function bindReference($name, &$reference, $type = self::PARAM_STR)
	{
		$this->pdoStatement->bindParam($name, $reference, $type);
	}
	
	/**
	 * Binds a set of parameters.
	 *
	 * @throws \PDOException
	 *	Thrown when one of the parameters fails to bind.
	 *
	 * @param array $parameters
	 *	The values to be bound, indexed by parameter name.
	 */
	public function bindAll(array $parameters)
	{
		foreach ($parameters as $key => $value)
		{
			$this->pdoStatement->bindValue($key, $value, self::PARAM_STR);
		}
	}
	
	/**
	 * Executes this query statement against the database.
	 *
	 * @return Reader
	 *	The connection data reader instance.
	 */
	public function query()
	{
		$this->raiseArray('statement', array($this));
		return new Reader($this);
	}
	
	/**
	 * Executes this query statement against the database, returning the
	 * first cell of the result set.
	 *
	 * @return mixed
	 *	The first cell of the result set, or NULL.
	 */
	public function scalar()
	{
		return $this->query()->fetchScalar();
	}
	
	/**
	 * Executes the update statement.
	 *
	 * @return int
	 *	The number of rows affected.
	 */
	public function execute()
	{
		$this->raiseArray('statement', array($this));
		$this->pdoStatement->execute();
		return $this->pdoStatement->rowCount();
	}
}

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

use \system\base\Component;

/**
 * Connection.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql
 * @since 0.2.0
 */
class Connection extends Component
{
	/**
	 * An associative array defining the classes of all core connection
	 * drivers, indexed by name.
	 *
	 * @type array
	 */
	private static $driverClasses = array(
		'mysql' => 'system\\sql\\driver\\mysql\\MysqlDriver',
		'pgsql' => 'system\\sql\\driver\\pgsql\\PgsqlDriver'
	);
	
	/**
	 * The username to use for authentication.
	 *
	 * @type string
	 */
	private $user;
	
	/**
	 * The password to use for authentication.
	 *
	 * @type string
	 */
	private $password;
	
	/**
	 * The database DSN connection string.
	 *
	 * @type string
	 */
	private $dsn;
	
	/**
	 * The connection driver instance.
	 *
	 * @type Driver
	 */
	private $driver;
	
	/**
	 * This method is invoked during the extension initialization procedure,
	 * before the child extensions get loaded -- when applicable.
	 *
	 * This method encapsulates the "initialize" event.
	 *
	 * @return bool
	 *	Returns TRUE to continue with the event, FALSE to cancel it.
	 */
	protected function onInitialize()
	{
		if (parent::onInitialize())
		{
			$this->open();
			return true;
		}
		
		return false;
	}
	
	/**
	 * Defines the user for authentication.
	 *
	 * @param string $user
	 *	The user to authenticate with.
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}
	
	/**
	 * Returns the user for authentication.
	 *
	 * @return string
	 *	The user to authenticate with.
	 */
	public function getUser()
	{
		return $this->user;
	}
	
	/**
	 * Defines the password to use for authentication.
	 *
	 * @param string $password
	 *	The password to use for authentication.
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}
	
	/**
	 * Returns a flag indicating wether or not a password is defined
	 * for authentication.
	 *
	 * @return bool
	 *	Returns TRUE if a password is defined, FALSE otherwise.
	 */
	public function hasPassword()
	{
		return isset($this->password);
	}
	
	/**
	 * Defines the database connection string.
	 *
	 * @param string $dsn
	 *	The database DSN connection string.
	 */
	public function setDsn($dsn)
	{
		$this->dsn = $dsn;
	}
	
	/**
	 * Returns the database connection string.
	 *
	 * @return string
	 *	The database DSN connection string.
	 */
	public function getDsn()
	{
		return $this->dsn;
	}
	
	/**
	 * Opens the database connection.
	 *
	 * @throws \PDOException
	 *	Thrown when the connection fails to stablish.
	 *
	 * @throws RuntimeException
	 *	Thrown when a driver is not defined.
	 */
	public function open()
	{
		if (!isset($this->driver))
		{
			throw new RuntimeException('A connection driver is not defined.');
		}
	
		$dsn = $this->driver->getName() . ':' . $this->dsn;
		
		$this->pdo = new \PDO($dsn, $this->user, $this->password);
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$this->pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
	}
	
	/**
	 * Closes the database connection.
	 */
	public function close()
	{
		$this->pdo = null;
	}
	
	/**
	 * Checks wether or not the connection is closed.
	 *
	 * @return bool
	 *	Returns TRUE if the connection is open, FALSE otherwise.
	 */
	public function isClosed()
	{
		return !$this->pdo;
	}
	
	/**
	 * Returns the underlying PDO connection handle.
	 *
	 * @return \PDO
	 *	The PDO connection handle.
	 */
	public function getPDO()
	{
		return $this->pdo;
	}
	
	/**
	 * Defines the driver to use for this connection.
	 *
	 * @param Driver|string $driver
	 *	The instance, name or class of the driver to use.
	 */
	public function setDriver($driver)
	{
		if (isset($this->pdo))
		{
			throw new RuntimeException('The driver must not change during an active connection.');
		}
		
		if (is_string($driver))
		{
			$class = isset(self::$driverClasses[$driver]) ? 
				self::$driverClasses[$driver] : $driver;
		
			$driver = new $class($this);
		}
		
		$this->driver = $driver;
	}
	
	/**
	 * Returns the currently active driver instance.
	 *
	 * @return Driver
	 *	The active driver instance.
	 */
	public function getDriver()
	{
		return $this->driver;
	}
	
	/**
	 * Prepares a statement for execution.
	 *
	 * @param string|Statement $statement
	 *	The statement to be prepared, either as a string or an already
	 *	prepared statement instance.
	 *
	 * @param array $parameters
	 *	The parameters to be bound to the statement.
	 *
	 * @return Statement
	 *	The statement instance.
	 */
	public function prepare($statement, array $parameters = null)
	{
		if (is_string($statement))
		{
			$statement = new Statement($this, $statement);
		}
		
		if (isset($parameters))
		{
			$statement->bindAll($parameters);
		}
		
		return $statement;
	}
	
	/**
	 * Prepares and executes a query statement.
	 *
	 * @throws \PDOException
	 *	Thrown when the statement fails to be prepared or executed.
	 *
	 * @param string|Statement $statement
	 *	The statement to be prepared, either as a string or an already
	 *	prepared statement instance.
	 *
	 * @param array $parameters
	 *	The parameters to be bound to the statement.
	 *
	 * @return Reader
	 *	The result set reader.
	 */
	public function query($statement, array $parameters = null)
	{
		return $this->prepare($statement, $parameters)->query();
	}
	
	/**
	 * Prepares and executes a query statement, returning the first cell
	 * of the result set.
	 *
	 * @throws \PDOException
	 *	Thrown when the statement fails to be prepared or executed.
	 *
	 * @param string|Statement $statement
	 *	The statement to be prepared, either as a string or an already
	 *	prepared statement instance.
	 *
	 * @param array $parameters
	 *	The parameters to be bound to the statement.
	 *
	 * @return mixed
	 *	The first cell of the result set, or NULL.
	 */
	public function scalar($statement, array $parameters = null)
	{
		return $this->prepare($statement, $parameters)->scalar();
	}
	
	/**
	 * Prepares and executes an update statement.
	 *
	 * @throws \PDOException
	 *	Thrown when the statement fails to be prepared or executed.
	 *
	 * @param string|Statement $statement
	 *	The statement to be prepared, either as a string or an already
	 *	prepared statement instance.
	 *
	 * @param array $parameters
	 *	The parameters to be bound to the statement.
	 *
	 * @return int
	 *	The number of affected rows.
	 */
	public function execute($statement, array $parameters = null)
	{
		return $this->prepare($statement, $parameters)->execute();
	}
	
	/**
	 * Executes a command without any preparation.
	 *
	 * @throws \PDOException
	 *	When the command fails to be executed.
	 *
	 * @param string $command
	 *	The command to be executed.
	 *
	 * @return int
	 *	The number of affected rows.
	 */
	public function run($command)
	{
		return $this->exec($command);
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
		return $this->driver->quote($name);
	}
}


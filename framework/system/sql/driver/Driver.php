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
 * An abstract database driver to provide access to the driver-specific
 * database schema and statement factory instances.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql.driver
 * @since 0.2.0
 */
abstract class Driver extends Extension
{
	/**
	 * Specifies a table, column or row should be LOCK for READING, thus
	 * making sure it's data does not change during a transaction.
	 *
	 * Once this lock is set the table/row/column becomes READ-ONLY and any
	 * inserts from this or other connection sessions will hang until the lock
	 * is released.
	 *
	 * @type int
	 */
	const LOCK_READ = 1;
	
	/**
	 * Specifies a table, column or row should be LOCK for WRITTING, thus
	 * making sure it's data can not be read while it's still being updated.
	 *
	 * Once this lock is set any queries made to the table/row/column will
	 * hang, unless made from the current connection session.
	 *
	 * @type int
	 */
	const LOCK_WRITE = 2;
	
	/**
	 * Constructor.
	 *
	 * @param Connection $connection
	 *	The parent connection instance.
	 */
	public final function __construct(Connection $connection, array $configuration = null)
	{
		parent::__construct($connection);
	}

	/**
	 * Returns the driver specific statement factory implementation.
	 *
	 * @return StatementFactory
	 *	The driver statement factory implementation.
	 */
	public abstract function getStatementFactory();
	
	/**
	 * Returns the driver specific schema implementation.
	 *
	 * @return Schema
	 *	The driver schema implementation.
	 */
	public abstract function getSchema();
	
	/**
	 * Returns the driver name as a lowercased string.
	 *
	 * @return string
	 *	The driver name.
	 */
	public abstract function getName();
	
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
	public abstract function quote($name);
	
	/**
	 * Returns the driver connection instance.
	 *
	 * This function is an alias of Extension::getParent.
	 *
	 * @return Connection
	 *	The parent connection instance.
	 */
	public function getConnection()
	{
		return $this->getParent();
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
	public abstract function setTableLocks(array $tables, $type = self::LOCK_WRITE);
	
	/**
	 * Releases any existing table locks.
	 *
	 * @throws RuntimeException
	 *	Thrown when the tables fail to be release.
	 */
	public abstract function releaseAllLocks();
}


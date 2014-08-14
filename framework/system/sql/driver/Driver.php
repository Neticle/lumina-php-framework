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
 * @since 0.2.0
 */
abstract class Driver extends Extension
{
	/**
	 * The defines future transaction isolation 
	 * levels as 'REPEATABLE READ'.
	 *
	 * @see http://dev.mysql.com/doc/refman/5.6/en/set-transaction.html
	 * @type string
	 */
	const TRANSACTION_REPEATABLE_READ = 'REPEATABLE READ';
	
	/**
	 * The defines future transaction isolation 
	 * levels as 'READ COMMITTED'.
	 *
	 * @see http://dev.mysql.com/doc/refman/5.6/en/set-transaction.html
	 * @type string
	 */
	const TRANSACTION_READ_COMMITTED = 'READ COMMITTED';
	
	/**
	 * The defines future transaction isolation 
	 * levels as 'READ UNCOMMITED'.
	 *
	 * @see http://dev.mysql.com/doc/refman/5.6/en/set-transaction.html
	 * @type string
	 */
	const TRANSACTION_READ_UNCOMMITTED = 'READ UNCOMMITED';
	
	/**
	 * The defines future transaction isolation 
	 * levels as 'SERIALIZABLE'.
	 *
	 * This is the default transaction level for transactions started
	 * through Lumina API and any subsequent ones.
	 *
	 * @see http://dev.mysql.com/doc/refman/5.6/en/set-transaction.html
	 * @type string
	 */
	const TRANSACTION_SERIALIZABLE = 'SERIALIZABLE';
	
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
	 * Runs a SQL command that defines the transaction isolation level
	 * acording to the given value.
	 *
	 * This isolation level will apply to all transactions started after
	 * its definition, for the current session.
	 *
	 * @param int $level
	 *	The transaction isolation level, as defined by the
	 *	Driver::TRANSACTION_* constants.
	 */
	public abstract function setTransactionIsolationLevel($level);
	
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
}


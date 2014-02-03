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
	 * Constructor.
	 *
	 * @param Connection $connection
	 *	The parent connection instance.
	 */
	public final function __construct(Connection $connection, array $configuration = null)
	{
		parent::__construct($connection);
		$this->construct($configuration);
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
}


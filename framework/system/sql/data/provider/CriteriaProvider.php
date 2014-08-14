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

namespace system\sql\data\provider;

use \system\data\provider\Provider;
use \system\sql\Criteria;
use \system\sql\Connection;
use \system\sql\data\provider\paginator\CriteriaPaginator;
use \system\sql\data\provider\sorter\CriteriaSorter;

/**
 * A provider that works through a criteria instance and changes it's
 * offset, limit and sort properties in order to provide pagination and
 * sorting through queries that make use of it.
 *
 * This class can't be used directly and, instead, you should use SelectProvider
 * or RecordProvider according to your needs.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
abstract class CriteriaProvider extends Provider
{
	/**
	 * The underlying criteria instance.
	 *
	 * @type Criteria
	 */
	private $criteria;
	
	/**
	 * The connection handle being used to fetch the data from the
	 * database.
	 *
	 * @type Connection
	 */
	private $connection;

	/**
	 * Constructor.
	 *
	 * @param array $configuration
	 *	The provider express configuration array.
	 */
	public function __construct(array $configuration = null)
	{
		parent::__construct($configuration);
	}
	
	/**
	 * Returns the underlying connection handle.
	 *
	 * If a connection hasn't been previously defined, the 'database'
	 * application component will be returned instead.
	 *
	 * @return Connection
	 *	The connection handle being used to fetch the data from the
	 *	database.
	 */
	public function getConnection()
	{
		if (!isset($this->connection))
		{
			$this->connection = $this->getComponent('database');
		}
		
		return $this->connection;
	}
	
	/**
	 * Defines the underlying connection handle.
	 *
	 * @return Connection
	 *	The connection handle being used to fetch the data from the
	 *	database.
	 */
	public function setConnection(Connection $connection)
	{
		$this->connection = $connection;
	}
	
	/**
	 * Returns the underlying Criteria instance.
	 *
	 * If a Criteria instance hasn't been previously defined a new
	 * one will be created, registered and returned.
	 *
	 * @return Criteria
	 *	The underlying criteria instance.
	 */
	public function getCriteria()
	{
		if (!isset($this->criteria))
		{
			$this->criteria = new Criteria();
		}
		
		return $this->criteria;
	}
	
	/**
	 * Defines the underlying Criteria configuration.
	 *
	 * @param Criteria|array $criteria
	 *	A Criteria instance or an express configuration array to
	 *	create one with.
	 */
	public function setCriteria($criteria)
	{
		if (!($criteria instanceof Criteria))
		{
			$criteria = new Criteria($criteria);
		}
		
		$this->criteria = $criteria;
	}
	
	/**
	 * Defines the paginator handle to be used by this provider instance.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified value is not compatible with the final
	 *	provider implementation.
	 *
	 * @param CriteriaPaginator|array $paginator
	 *	An instance of a Paginator handle matching the final provider
	 *	implementation, or an express configuration array to build one with.
	 */
	public function setPaginator($paginator)
	{
		if (!($paginator instanceof CriteriaPaginator))
		{
			$paginator = new CriteriaPaginator($this, $paginator);
		}
		else
		{
			$paginator->setProvider($this);
		}
		
		parent::setPaginator($paginator);
	}
	
	/**
	 * Defines the data sorter handle to be used by this provider instance.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified value is not compatible with the final
	 *	provider implementation.
	 *
	 * @param CriteriaSorter|array $sorter
	 *	An instance of CriteriaSorter or an express construction and configuration
	 *	array to build one with.
	 */
	public function setSorter($sorter)
	{
		if (!($sorter instanceof CriteriaSorter))
		{
			$sorter = new CriteriaSorter($this, $sorter);
		}
		else
		{
			$sorter->setProvider($this);
		}
		
		parent::setSorter($sorter);
	}
}


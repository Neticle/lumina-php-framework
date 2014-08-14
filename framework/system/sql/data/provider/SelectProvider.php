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

use \system\sql\Connection;
use \system\sql\Criteria;
use \system\sql\Reader;
use \system\sql\data\Record;
use \system\sql\data\provider\CriteriaProvider;

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
class SelectProvider extends CriteriaProvider
{
	/**
	 * The main table to select data from.
	 *
	 * @type string
	 */
	private $table;

	/**
	 * Constructor.
	 *
	 * @param string $table
	 *	The main table to select data from.
	 *
	 * @param array $configuration
	 *	The provider express configuration array.
	 */
	public function __construct($table, array $configuration = null)
	{
		parent::__construct(null);
		
		$this->table = $table;
		
		if (isset($configuration))
		{
			$this->configure($configuration);
		}
	}
	
	/**
	 * Returns the value of the specified field in the given item.
	 *
	 * This method is intended to abstract the differences between array,
	 * models and record items returned by their respective providers.
	 *
	 * @param mixed $item
	 *	The item to get the field value of.
	 *
	 * @param string $field
	 *	The field to get the value of.
	 *
	 * @return mixed
	 *	The field value, if any.
	 */
	public function getItemFieldValue($item, $field)
	{
		return isset($item[$field]) ?
			$item[$field] : null;
	}
	
	/**
	 * Fetches all applicable items.
	 *
	 * @return array
	 *	The fetched items array.
	 */
	protected function fetchItems()
	{
		$criteria = $this->getCriteria();
		$paginator = $this->getPaginator();
		$sorter = $this->getSorter();
		
		if (isset($sorter))
		{
			$sorter->apply($criteria);
		}
		
		if (isset($paginator))
		{
			$paginator->apply($criteria);
		}
		
		return $this->getConnection()->select($this->table, $criteria)
			->fetchAll(Reader::FETCH_BOTH);
	}
	
	/**
	 * Fetches the total item count, which or may not match the number of
	 * items returned by 'fetchItems'.
	 *
	 * For instance, if the final provider implementation supports paginator
	 * it is to be handled internally, and 'fetchItems' will only return
	 * those for the current page, while 'fetchTotalItemCount' should always
	 * return the number of available items.
	 *
	 * @return int
	 *	The number of available items.
	 */
	protected function fetchTotalItemCount()
	{		
		return $this->getConnection()
			->count($this->table, $this->getCriteria()->getCountCriteria());
	}
}


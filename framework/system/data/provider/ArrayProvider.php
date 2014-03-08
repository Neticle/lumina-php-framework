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

namespace system\data\provider;

use \system\data\provider\Provider;
use \system\data\provider\paginator\ArrayPaginator;
use \system\data\provider\sorter\ArraySorter;

/**
 * A provider that works through an array of associative arrays, with
 * support for sorting and pagination.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.provider
 * @since 0.2.0
 */
class ArrayProvider extends Provider
{
	/**
	 * The items to be available through this provider.
	 *
	 * @type array
	 */
	private $items;

	/**
	 * Constructor.
	 *
	 * @param array $items
	 *	The items to be available through this provider.
	 *
	 * @param array $configuration
	 *	The provider express configuration array.
	 */
	public function __construct(array $items, array $configuration = null)
	{
		parent::__construct(null);
		$this->items = $items;
		
		if (isset($configuration))
		{
			$this->configure($configuration);
		}
	}
	
	/**
	 * Fetches all applicable items.
	 *
	 * @return array
	 *	The fetched items array.
	 */
	protected function fetchItems()
	{
		$items = $this->items;
		
		// Sort the available items
		$sorter = $this->getSorter();
		
		if (isset($sorter))
		{
			$items = $sorter->sort($items);
		}
		
		// Paginate the available items
		$paginator = $this->getPaginator();
		
		if (isset($paginator))
		{
			$items = $paginator->filter($items);
		}
		
		return $items;
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
		return count($this->items);
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
	 * Defines the paginator handle to be used by this provider instance.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified value is not compatible with the final
	 *	provider implementation.
	 *
	 * @param Paginator|array $paginator
	 *	An instance of a Paginator handle matching the final provider
	 *	implementation, or an express configuration array to build one with.
	 */
	public function setPaginator($paginator)
	{
		if (!($paginator instanceof ArrayPaginator))
		{
			$paginator = new ArrayPaginator($this, $paginator);
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
	 * @param ArraySorter|array $sorter
	 *	An instance of ArraySorter or an express construction and configuration
	 *	array to build one with.
	 */
	public function setSorter($sorter)
	{
		if (!($sorter instanceof ArraySorter))
		{
			$sorter = new ArraySorter($this, $sorter);
		}
		else
		{
			$sorter->setProvider($this);
		}
		
		parent::setSorter($sorter);
	}
}


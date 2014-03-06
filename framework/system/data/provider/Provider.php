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

use \system\core\Element;
use \system\data\provider\IProvider;

/**
 * An abstract data provider.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data
 * @since 0.2.0
 */
abstract class Provider extends Element implements IProvider
{
	/**
	 * A cached copy of the last fetched items.
	 *
	 * @param array
	 */
	private $items;
	
	/**
	 * A cached copy of the last fetched total item count.
	 *
	 * @param int
	 */
	private $totalItemCount;
	
	/**
	 * The underlying paginator handle.
	 *
	 * @type Paginator
	 */
	private $paginator;
	
	/**
	 * The underlying data sorter handle.
	 *
	 * @type Sorter
	 */
	private $sorter;
	
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
	 * Fetches all applicable items.
	 *
	 * @return array
	 *	The fetched items array.
	 */
	protected abstract function fetchItems();
	
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
	protected abstract function fetchTotalItemCount();
	
	/**
	 * Returns the currently applicable items, fetching them
	 * if necessary.
	 *
	 * @param bool $refresh
	 *	When set to TRUE any previously cached information will and re-fetched
	 *	from the underlying persistent data storage.
	 *
	 * @return array
	 *	The fetched items array.
	 */
	public function getItems($refresh = false)
	{
		if ($refresh || !isset($this->items))
		{
			$this->items = $this->fetchItems();
		}
		
		return $this->items;
	}
	
	/**
	 * Returns the number of applicable items.
	 *
	 * @param bool $refresh
	 *	When set to TRUE any previously cached information will and re-fetched
	 *	from the underlying persistent data storage.
	 *
	 * @return int
	 *	The number of applicable items.
	 */
	public function getItemCount($refresh = false)
	{
		return count($this->getItems($refresh));
	}
	
	/**
	 * Returns the total number of items.
	 *
	 * @param bool $refresh
	 *	When set to TRUE any previously cached information will and re-fetched
	 *	from the underlying persistent data storage.
	 *
	 * @return int
	 *	The total number of items.
	 */
	public function getTotalItemCount($refresh = false)
	{
		if ($refresh || !isset($this->totalItemCount))
		{
			$this->totalItemCount = $this->fetchTotalItemCount();
		}
		
		return $this->totalItemCount;
	}
	
	/**
	 * Returns an iterator for the underlying items array.
	 *
	 * @param bool $refresh
	 *	When set to TRUE any previously cached information will and re-fetched
	 *	from the underlying persistent data storage.
	 *
	 * @return \ArrayIterator
	 *	The items array iterator instance.
	 */
	public function getIterator($refresh = false)
	{
		return new \ArrayIterator($this->getItems($refresh));
	}
	
	/**
	 * Defines the paginator handle to be used by this provider instance.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified value is not compatible with the final
	 *	provider implementation.
	 *
	 * @param Paginator $paginator
	 *	An instance of a Paginator handle matching the final provider
	 *	implementation, or an express configuration array to build one with.
	 */
	public function setPaginator($paginator)
	{	
		$this->paginator = $paginator;
	}
	
	/**
	 * Returns the currently defined paginator handle.
	 *
	 * @return Paginator
	 *	The currently defined paginator handle.
	 */
	public function getPaginator()
	{
		return $this->paginator;
	}
	
	/**
	 * Defines the data sorter handle to be used by this provider instance.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified value is not compatible with the final
	 *	provider implementation.
	 *
	 * @param Sorter $paginator
	 *	An instance of a Paginator handle matching the final provider
	 *	implementation.
	 */
	public function setSorter($sorter)
	{
		$this->sorter = $sorter;
	}
	
	/**
	 * Returns the currently defined data sorter handle.
	 *
	 * @return Sorter
	 *	The currently defined data sorter handle.
	 */
	public function getSorter()
	{
		return $this->sorter;
	}
	
}


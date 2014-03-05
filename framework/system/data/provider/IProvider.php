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
// Foundation, either version 3 of the License, or (at your option); any later
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

/**
 * Defines the public interface for all data providers as expected by the
 * multiple extensions that require them.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.provider
 * @since 0.2.0
 */
interface IProvider extends \IteratorAggregate
{
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
	public function getIterator();
	
	/**
	 * Returns the currently applicable items, fetching them
	 * if necessary.
	 *
	 * @return array
	 *	The fetched items array.
	 */
	public function getItems();
	
	/**
	 * Returns the number of applicable items.
	 *
	 * @return int
	 *	The number of applicable items.
	 */
	public function getItemCount();
	
	/**
	 * Returns the total number of items.
	 *
	 * @return int
	 *	The total number of items.
	 */
	public function getTotalItemCount();
	
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
	public function setPaginator($paginator);
	
	/**
	 * Returns the currently defined paginator handle.
	 *
	 * @return Paginator
	 *	The currently defined paginator handle.
	 */
	public function getPaginator();
	
	/**
	 * Defines the data sorter handle to be used by this provider instance.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified value is not compatible with the final
	 *	provider implementation.
	 *
	 * @param Sorter|array $sorter
	 *	An instance of a sorting handle matching the final provider
	 *	implementation, or an express configuration array to build one with.
	 */
	public function setSorter($sorter);
	
	/**
	 * Returns the currently defined data sorter handle.
	 *
	 * @return Sorter
	 *	The currently defined data sorter handle.
	 */
	public function getSorter();
}


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

namespace system\data\provider\paginator;

use \system\data\provider\Provider;
use \system\data\provider\paginator\Paginator;

/**
 * An abstract paginator that provides a consistent API across multiple
 * implementations.
 *
 * Although the base implementation is abstract, final implementations of this
 * class will provide additional methods to ease the application of this
 * feature.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class ArrayPaginator extends Paginator
{
	/**
	 * Constructor.
	 *
	 * @param Provider $provider
	 *	The data provider this paginator is to be linked with.
	 *
	 * @param array $configuration
	 *	The paginator configuration.
	 */
	public function __construct(Provider $provider, array $configuration = null)
	{
		parent::__construct($provider, $configuration);
	}
	
	/**
	 * Filters and returns an array of items matching the specified
	 * page, according to the interval previously defined.
	 *
	 * @param array $items
	 *	The array of items to filter and return from, which must have the
	 *	exact number of elements as defined in the paginator.
	 *
	 * @return array
	 *	The items matching the currently active page.
	 */
	public function filter(array $items)
	{
		return array_slice($items, $this->getActivePageOffset(), $this->getInterval());
	}
}


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
		parent::__construct($configuration);
		$this->items = $items;
	}
	
	
}


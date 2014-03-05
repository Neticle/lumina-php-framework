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

namespace system\data\paginator;

use \system\core\Element;

/**
 * An abstract paginator that provides a consistent API across multiple
 * implementations.
 *
 * Although the base implementation is abstract, final implementations of this
 * class will provide additional methods to ease the application of this
 * feature.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.paginator
 * @since 0.2.0
 */
abstract class Paginator extends Element
{
	/**
	 * The number of items to display per page.
	 *
	 * @type int
	 */
	private $interval = 25;

	/**
	 * Constructor.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct(array $configuration = null)
	{
		parent::__construct($configuration);
	}
	
	/**
	 * Defines the number of items to be available per page.
	 *
	 * @param int $interval
	 *	The number of items to be available per page.
	 */
	public function setInterval($interval)
	{
		$this->interval = $interval;
	}
	
	/**
	 * Returns the number of items to be available per page.
	 *
	 * @return int
	 *	The number of items to be available per page.
	 */
	public function getInterval()
	{
		return $this->interval;
	}
	
	/**
	 * Returns the number of pages given an item count.
	 *
	 * @param int $itemCount
	 *	The total number of available items.
	 *
	 * @return int
	 *	The number of available pages, which is always equal or greater than
	 *	one (1).
	 */
	public function getPageCount($itemCount)
	{
		if (isset($this->interval) && $this->interval > 0 && $itemCount > 0)
		{
			return (int) ceil($itemCount / $this->interval);
		}
		
		return 1;
	}
}


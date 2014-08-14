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

namespace system\data\provider\sorter;

use \system\data\provider\Provider;
use \system\data\provider\sorter\Sorter;


/**
 * An abstract sorter that provides a consistent API across multiple
 * implementations.
 *
 * Although the base implementation is abstract, final implementations of this
 * class will provide additional methods to ease the application of this
 * feature.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class ArraySorter extends Sorter
{
	/**
	 * Constructor.
	 *
	 * @param Provider $provider
	 *	The data provider this sorter is to be linked with.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct(Provider $provider, array $configuration = null)
	{
		parent::__construct($provider, $configuration);
	}
	
	/**
	 * Compares one array item to another ("alpha" and "bravo").
	 *
	 * The comparison is first done by type and then by value, meaning that
	 * the same value of a different type can't be compared to another.
	 *
	 * @throws RuntimeException
	 *	If the same key does not have matching types on both items.
	 *
	 * @return int
	 *	Returns 1 if "alpha" is greater than "bravo", -1 if "alpha" is
	 *	lesser than "bravo", or 0 if both items are equal.
	 */
	public function compare(array $alpha, array $bravo)
	{		
		foreach ((array) $this->getRules() as $key => $direction)
		{
			$left = isset($alpha[$key]) ?
				$alpha[$key] : null;
			
			$right = isset($bravo[$key]) ?
				$bravo[$key] : null;
			
			if ($left === $right)
			{
				continue;
			}
			
			if (gettype($left) === 'string' && gettype($right) === 'string')
			{
				$result = strcmp($left, $right);
				
				if ($result === 0)
				{
					continue;
				}
			}
			else
			{
				$result = $left > $right ? 1 : -1;
			}
			
			return ($direction === 'asc' ? 1 : -1) * $result;
		}
		
		return 0;
	}

	/**
	 * Sorts an array of items according to the currently
	 * defined rules.
	 *
	 * @param array $items
	 *	An array of items, represented as associative arrays.
	 *
	 * @return array
	 *	The sorted array.
	 */	
	public function sort(array $items)
	{
		$rules = $this->getRules();
		
		if (isset($rules))
		{
			usort($items, array($this, 'compare'));
		}
		
		return $items;
	}
}


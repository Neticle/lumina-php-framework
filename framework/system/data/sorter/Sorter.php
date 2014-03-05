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

namespace system\data\sorter;

use \system\core\Element;

/**
 * An abstract sorter that provides a consistent API across multiple
 * implementations.
 *
 * Although the base implementation is abstract, final implementations of this
 * class will provide additional methods to ease the application of this
 * feature.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.sorter
 * @since 0.2.0
 */
abstract class Sorter extends Element
{
	/**
	 * Defines the ascendent sorting direction.
	 *
	 * @type string
	 */
	const SORT_DIRECTION_ASC = 'asc';
	
	/**
	 * Defines the descendent sorting direction.
	 *
	 * @type string
	 */
	const SORT_DIRECTION_DESC = 'desc';
	
	/**
	 * An associative array of rules, defining the direction 
	 * (Sorter::SORT_DIRECTION_* constants), indexed by field name.
	 *
	 * @type array
	 */
	private $rules;

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
	 * Defines the sorting rules.
	 *
	 * @param array $rules
	 *	An associative array of rules, defining the direction 
	 *	(Sorter::SORT_DIRECTION_* constants), indexed by field name.
	 */
	public function setRules(array $rules)
	{
		$this->rules = $rules;
	}
	
	/**
	 * Returns the sorting rules.
	 *
	 * @return array
	 *	An associative array of rules, defining the direction 
	 *	(Sorter::SORT_DIRECTION_* constants), indexed by field name.
	 */
	public function getRules()
	{
		return $this->rules;
	}
	
	/**
	 * Checks wether or not there are any rules defined for this
	 * sorter instance.
	 *
	 * @return bool
	 *	Returns TRUE if at least one rule is defined, FALSE otherwise.
	 */
	public function hasRules()
	{
		return !empty($this->rules);
	}
}


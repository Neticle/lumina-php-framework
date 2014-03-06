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

use \system\core\Element;
use \system\data\provider\Provider;

/**
 * An abstract sorter that provides a consistent API across multiple
 * implementations.
 *
 * Although the base implementation is abstract, final implementations of this
 * class will provide additional methods to ease the application of this
 * feature.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.provider.sorter
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
	 * The data provider this sorter belongs to.
	 *
	 * @type Provider
	 */
	private $provider;
	
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
	 * @param Provider $provider
	 *	The data provider this sorter is to be linked with.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	protected function __construct(Provider $provider, array $configuration = null)
	{
		parent::__construct(null);
		$this->provider = $provider;
		$this->configure($configuration);
	}
	
	/**
	 * Returns the provider this instance is linked to.
	 *
	 * @return Provider
	 *	The provider this instance is linked to.
	 */
	public function getProvider()
	{
		return $this->provider;
	}
	
	/**
	 * Changes the sorter provider.
	 *
	 * @param Provider $provider
	 *	The new provider instance.
	 */
	public function setProvider(Provider $provider)
	{
		$this->provider = $provider;
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


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

namespace system\sql\data\provider\paginator;

use \system\data\provider\paginator\Paginator;
use \system\sql\Criteria;
use \system\sql\data\provider\CriteriaProvider;

/**
 * An abstract paginator that provides a consistent API across multiple
 * implementations.
 *
 * Although the base implementation is abstract, final implementations of this
 * class will provide additional methods to ease the application of this
 * feature.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.provider.paginator
 * @since 0.2.0
 */
class CriteriaPaginator extends Paginator
{
	/**
	 * Constructor.
	 *
	 * @param CriteriaProvider $provider
	 *	The data provider this paginator is to be linked with.
	 *
	 * @param array $configuration
	 *	The paginator configuration.
	 */
	public function __construct(CriteriaProvider $provider, array $configuration = null)
	{
		parent::__construct($provider, $configuration);
	}
	
	/**
	 * Applies the paginator configuration to the given criteria.
	 *
	 * @param Criteria $criteria
	 *	The criteria to apply pagination data to.
	 */
	public function apply(Criteria $criteria)
	{
		$criteria->setOffset($this->getActivePageOffset());
		$criteria->setLimit($this->getInterval());
	}
}


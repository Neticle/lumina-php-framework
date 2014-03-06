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

namespace system\sql\data\provider;

use \system\sql\data\provider\Provider;

/**
 * A provider that works through a criteria instance and changes it's
 * offset, limit and sort properties in order to provide pagination and
 * sorting through queries that make use of it.
 *
 * This class can't be used directly and, instead, you should use SelectProvider
 * or RecordProvider according to your needs.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql.data.provider
 * @since 0.2.0
 */
abstract class CriteriaProvider extends Provider
{
	/**
	 * The underlying criteria instance.
	 *
	 * @type Criteria
	 */
	private $criteria;

	/**
	 * Constructor.
	 *
	 * @param Criteria $criteria
	 *	The instance of the Criteria to modify.
	 *
	 * @param array $configuration
	 *	The provider express configuration array.
	 */
	public function __construct(Criteria $criteria, array $configuration = null)
	{
		parent::__construct($configuration);
		$this->criteria = $criteria;
	}
}


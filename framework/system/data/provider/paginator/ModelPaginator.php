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
use \system\data\provider\paginator\ArrayPaginator;

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
class ModelPaginator extends ArrayPaginator
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
}


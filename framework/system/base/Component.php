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

namespace system\base;

use \system\core\Extension;
use \system\core\LazyExtension;

/**
 * Defines the base behaviour and events for all Lumina components.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
abstract class Component extends LazyExtension
{
	/**
	 * Constructor.
	 *
	 * @param Extension $parent
	 *	The parent extension instance.
	 *
	 * @param array $configuration
	 *	The component express configuration array.
	 */
	public final function __construct(Extension $parent, array $configuration = null)
	{
		parent::__construct($parent);
		$this->construct($configuration);
	}
}


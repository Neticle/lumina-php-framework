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

namespace system\data\validation;

use \system\data\Model;
use \system\data\validation\Rule;

/**
 * Marks a model attribute as not safe for massive assignment.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.validation
 * @since 0.2.0
 */
class SafeRule extends Rule 
{
	/**
	 * A flag indicating wether or not the attribute value is safe for
	 * massive assignment.
	 *
	 * @type bool
	 */
	protected $safe = false;
	
	/**
	 * Runs this validation rule against the given model.
	 *
	 * @param Model $model
	 *	The model being validated.
	 *
	 * @param string[] $attributes
	 *	The names of the attributes to validate.
	 *
	 *	Please note that this rule will only validate the attribute it applies
	 *	to, ignoring any extra arguments given in this array.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE on failure.
	 */
	public function validate(Model $model, array $attributes = null)
	{
		return true;
	}
}


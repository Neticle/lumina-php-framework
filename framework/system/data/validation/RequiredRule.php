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

use \system\data\IValidatableDataContainer;
use \system\data\validation\Rule;

/**
 * Validates a model attribute by making sure it is not empty.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.validation
 * @since 0.2.0
 */
class RequiredRule extends Rule
{	
	/**
	 * The message to be reported back to the model when one of the attributes
	 * fails validation due to it being empty when a value is required.
	 *
	 * @type string
	 */
	protected $message = 'Attribute "{attribute}" can not be empty.';
	
	/**
	 * A flag indicating wether or not the attribute value is required in
	 * order to pass validation.
	 *
	 * @type bool
	 */
	protected $required = true;
}


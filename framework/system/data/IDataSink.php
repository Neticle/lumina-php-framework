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

namespace system\data;

/**
 * Provides methods for storing information in an object in a standard way.
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
interface IDataSink
{

	/**
	 * Defines the value of a given attribute.
	 *
	 * @param string $attribute
	 *  The name of the attribute to defined the value for
	 *
	 * @param mixed $value
	 *  The new value to be assigned
	 */
	public function setAttribute ($attribute, $value);
	
	/**
	 * Defines the values of a list of attributes.
	 *
	 * @param array $attributes
	 *  An associative array containing all values to be defined, indexed by attribute's name
	 */
	public function setAttributes (array $attributes);

}

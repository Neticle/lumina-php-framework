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
 * Provides methods for fetching information from an object in a standard way.
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
interface IDataSource
{

	/**
	 * Returns the value of an attribute.
	 *
	 * @param string $attribute
	 *  The name of the attribute to get the value of
	 *
	 * @return mixed
	 *  The attribute's value
	 */
	public function getAttribute ($attribute);
	
	/**
	 * Gets all attributes and respective values, in an associative array indexed by attribute name.
	 *
	 * @return array
	 *  The attributes and values, indexed by name
	 */
	public function getAttributes ();

	/**
	 * Gets the names of all attributes.
	 *
	 * @return array
	 *  The attribute names
	 */
	public function getAttributeNames ();

}

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
interface ILabeledDataSource extends IDataSource
{

	/**
	 * Gets the label for a given attribute.
	 *
	 * @param string $attribute
	 *  The name of the attribute to get the label for
	 *
	 * @return string|null
	 *  The attribute's label, if any.
	 */
	public function getAttributeLabel ($attribute);

	/**
	 * Gets the list of labels for the attributes.
	 *
	 * @return array
	 *  The list of labels, indexed by attribute's name
	 */
	public function getAttributeLabels ();

}

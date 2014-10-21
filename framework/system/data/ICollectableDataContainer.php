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
 * Extends the IValidatableDataContainer interface to provide read, write and search functionality to
 * a persistent storage mechanism.
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
interface ICollectableDataContainer extends IValidatableDataContainer
{

	public function isNewRecord();
	
	public function find($criteria = null);
	
	public function findAll($criteria = null);
	
	public function findByAttributes(array $attributes);
	
	public function findAllByAttributes(array $attributes);
	
	public function exists($criteria = null);
	
	public function save($validate = true);
	
	public function delete();
	
	public function deleteAll($criteria = null);

}

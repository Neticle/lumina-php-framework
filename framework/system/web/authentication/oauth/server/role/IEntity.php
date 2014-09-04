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

namespace system\web\authentication\oauth\server\role;

/**
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
interface IEntity 
{
    
	/**
	 * Gets the identifier for the entity.
	 * The identifier must be unique (usually the entities "id" on the database).
	 * 
	 * Examples:
	 *  Identifier for user (id: 1) could be "1".
	 * 
	 *  Identifier for user (id: 1) could be "user:1" (if there are more than one type
	 *  of that particular entity).
	 */
	public function getOAuthIdentifier ();
	
}

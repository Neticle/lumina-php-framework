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
 * Represents a resource owner and must be implemented by any class that is to 
 * be used to get authenticated agaisnt the OAuth2 Provider.
 * 
 * From RFC 6749 - The OAuth 2.0 Authorization Framework:
 * 
 * RESOURCE OWNER
 *  An entity capable of granting access to a protected resource.
 *  When the resource owner is a person, it is referred to an an end-user.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 * @since 0.2.0
 */
interface IResourceOwner
{
	/**
	 * Gets the resource owner's unique identifier.
	 * 
	 * @return string
	 */
	public function getIdentifier ();
	
}

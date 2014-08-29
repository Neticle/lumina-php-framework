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

namespace system\web\authentication\oauth\server\data;

/**
 * The session, in the context of the OAuth component, is merely used to check
 * whether or not there is an end-user currently authenticated on the host 
 * application.
 * Therefore, your session mechanism must implement this interface if you wish
 * to make use of all the features the component provides.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 * @since 0.2.0
 */
interface ISession
{
	
	/**
	 * Returns the currently authenticated end-user, if any.
	 *
	 * @return IResourceOwner
	 *  The resource owner (end-user), if any, or NULL otherwise.
	 */
	public function getEndUser ();
	
}

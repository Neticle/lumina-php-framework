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

namespace system\web\session;

/**
 * A session handler component can be registered as PHP session save handler
 * in order to provide a more specific implementation.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.session
 * @since 0.2.0
 */
interface ISessionSaveHandler
{
	/**
	 * Creates or re-initializes the session store.
	 *
	 * @param string $savePath
	 *	The session save path.
	 *
	 * @param string $name
	 *	The session name.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function openSessionStore($savePath, $name);
	
	/**
	 * Closes the session store.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function closeSessionStore();
	
	/**
	 * Purges any expired sessions data.
	 *
	 * @param int $expiry
	 *	The maximum age a session is allowed to be, in seconds.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function purgeSessionData($expiry);
	
	/**
	 * Reads data from the session store.
	 *
	 * @param string $id
	 *	The ID of the session to read.
	 *
	 * @return string|bool
	 *	Returns the session contents on success, FALSE otherwise.
	 */
	public function readSessionData($id);
	
	/**
	 * Writes data to the session store.
	 *
	 * @param string $id
	 *	The ID of the session to read.
	 *
	 * @param string $data
	 *	The session contents, as a string.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function writeSessionData($id, $data);
	
	/**
	 * Destroys data from the session store.
	 *
	 * @param string $id
	 *	The ID of the session to destroy.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function destroySessionData($id);
}


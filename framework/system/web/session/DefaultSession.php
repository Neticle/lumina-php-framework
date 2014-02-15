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

use \system\web\session\Session;
use \system\core\exception\RuntimeException;

/**
 * The session component allows you to keep track of the user state
 * persistently across multiple requests.
 *
 * This implementation leaves all session management tasks to PHP and uses
 * all of it's default settings, as specified in PHP configuration
 * file ("php.ini").
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.session
 * @since 0.2.0
 */
class DefaultSession extends Session
{
	
}


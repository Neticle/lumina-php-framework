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

// Start timestamp constant
if (!defined('L_START'))
{
	define('L_START', microtime(true));
}

// Application constants
if (!defined('L_APPLICATION_ROOT'))
{
	die('Constant "L_APPLICATION_ROOT" is not defined.');
}

if (!defined('L_APPLICATION'))
{
	define('L_APPLICATION', L_APPLICATION_ROOT . '/application');
}

if (!defined('L_PUBLIC'))
{
	define('L_PUBLIC', L_APPLICATION_ROOT);
}

// Lumina constants
define('L_INSTALLATION_DIRECTORY', dirname(__FILE__));
define('L_SYSTEM', L_INSTALLATION_DIRECTORY . '/system');

// Register the default packages
require(L_SYSTEM . '/core/Lumina.php');

\system\core\Lumina::setPackagePath('system', L_SYSTEM);
\system\core\Lumina::setPackagePath('application', L_APPLICATION);
\system\core\Lumina::setPackagePath('public', L_PUBLIC);


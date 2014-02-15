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

use \system\core\Lumina;

include '../../../../lumina.php';

define('L_APPLICATION_ROOT', '/var/www');
include '../../../../../framework/bootstrap.php';

if (!isset($_GET['visit']))
{
	die('Define "visit" in query string.');
}

$visit = (int) $_GET['visit'];

lumina_test_start('Session');

$app = Lumina::loadWebApplication(null);
$session = $app->getComponent('session');

lumina_test_identical('session.name', 'LPSESSID', $session->getName());
lumina_test_identical('session.visit', $visit + 1, ++$session->visit);
lumina_test_identical('isset:not', false, isset($session->not));

$session->close();
lumina_test_end();


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

include '../../../lumina.php';

define('L_APPLICATION_ROOT', dirname(__FILE__) . '/../../../applications/static002');
include '../../../../framework/bootstrap.php';


ob_start();

Lumina::getEventBus()->onClassEvent('system\\base\\Application', 'initialize', function() {
	echo '[base-app-initialize]';
	return true;
});

Lumina::getEventBus()->onClassEvent('application\\StaticApplication002', 'initialize', function() {
	echo '[static002-app-initialize]';
	return true;
});

Lumina::getEventBus()->on('initialize', function() {
	echo '[initialize]';
	return true;
});

$app = Lumina::load(null, 'application\\StaticApplication002');
$output = ob_get_clean();

lumina_test_start('Lumina::load(...)');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

lumina_test_identical('output', '[base-app-initialize][static002-app-initialize][initialize]', $output);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

lumina_test_end();


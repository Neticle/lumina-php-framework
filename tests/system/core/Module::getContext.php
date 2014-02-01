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

/*

Should output:

	ctx1/ctx2/ctx3/ctx4/ctx5
	
*/

use \system\core\Lumina;
use \system\base\Module;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../framework/bootstrap.php';
require '../../functions.php';

lumina_test_start();

class MyModule extends Module
{
}

$namespace = 'application';
$ctx = new MyModule('ctx0', $namespace, null);

for ($i = 1; $i < 11; ++$i)
{
	$namespace .= '\\modules\\ctx' . $i;
	$child = new MyModule('ctx' . $i, $namespace, $ctx);
	$ctx = $child;
}

lumina_test_identical('ctx0~ctx10', 'ctx1/ctx2/ctx3/ctx4/ctx5/ctx6/ctx7/ctx8/ctx9/ctx10', $ctx->getRoute());
lumina_test_end();


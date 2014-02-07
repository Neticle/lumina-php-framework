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

define('L_APPLICATION_ROOT', '/var/www');
define('L_APPLICATION', '/var/www');
include '../../../../framework/bootstrap.php';

lumina_test_start('Lumina::getAliasPath(...)');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

$tests = array(
	'application' => '/var/www',
	'application.controllers' => '/var/www/controllers',
	'application.controllers.assets' => '/var/www/controllers/assets'
);

foreach ($tests as $alias => $expected)
{
	lumina_test_identical(
		'alias="' . $alias . '"; type=null; base=null', 
		$expected, 
		Lumina::getAliasPath($alias, null, null)
	);
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

$tests = array(
	'~application' => '/base/application.php',
	'application.views.myController.myview' => '/var/www/views/myController/myview.php'
);

foreach ($tests as $alias => $expected)
{
	lumina_test_identical(
		'alias="' . $alias . '"; type="php"; base="/base"', 
		$expected, 
		Lumina::getAliasPath($alias, 'php', '/base')
	);
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

$tests = array(
	'~application' => '/base/application',
	'application' => '/var/www',
	'application.views' => '/var/www/views',
	'~application.views' => '/base/application/views',
	'@/my/path' => '/my/path',
	'@~my/path' => '/base/my/path'
);

foreach ($tests as $alias => $expected)
{
	lumina_test_identical(
		'alias="' . $alias . '"; type=null; base="/base"', 
		$expected, 
		Lumina::getAliasPath($alias, null, '/base')
	);
}

lumina_test_end();


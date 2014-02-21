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

define('L_APPLICATION_ROOT', dirname(__FILE__));
define('L_APPLICATION', dirname(__FILE__));
include '../../../../framework/bootstrap.php';

lumina_test_start('Lumina::getMultiAliasPath(...)');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

$tests = array(
	'system;application' => L_SYSTEM,
	'application;system' => L_APPLICATION,
	'application' => L_APPLICATION,
	'system' => L_SYSTEM,
	'application.core;system.core' => L_SYSTEM . DIRECTORY_SEPARATOR . 'core',
	'application.i18n.dictionary;system.i18n.dictionary' => L_SYSTEM . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR . 'dictionary',
	'system.core;application.core' => L_SYSTEM . DIRECTORY_SEPARATOR . 'core',
	'system.i18n.dictionary;application.i18n.dictionary' => L_SYSTEM . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR . 'dictionary'
);

foreach ($tests as $alias => $expected)
{
	lumina_test_identical(
		'alias="' . $alias . '"; type=null; base=null', 
		$expected, 
		Lumina::getMultiAliasPath(explode(';', $alias), null, null)
	);
}

lumina_test_identical('alias="system.i18n.dictionary.Dictionary;application.i18n.dictionary.Dictionary"; type="php"; base=L_SYSTEM',
	L_SYSTEM . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR . 'dictionary' . DIRECTORY_SEPARATOR . 'Dictionary.php',
	Lumina::getMultiAliasPath(explode(';', '~i18n.dictionary.Dictionary;application.i18n.dictionary.Dictionary'), 'php', L_SYSTEM)
);

lumina_test_end();


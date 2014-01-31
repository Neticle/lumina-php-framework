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

include '../../functions.php';
include '../../../framework/system/core/Lumina.php';

Lumina::setPackagePath('application', '/var/www');

$tests = array(

	'@/some/awesome/path' => '/some/awesome/path',
	'@/' => '/',
	'@/some' => '/some',
	'@/~some/awesome/path' => '/~some/awesome/path',
	'@~some/awesome/path' => '/my/base/path/some/awesome/path',
	
	'~' => '/my/base/path',
	'~file' => '/my/base/path/file',
	'~some.awesome.file' => '/my/base/path/some/awesome/file',
	
	'application' => '/var/www',
	'application.file' => '/var/www/file',
	'application.dir1.dir2.dir3' => '/var/www/dir1/dir2/dir3'

);

lumina_test_start();

foreach ($tests as $input => $expected)
{
	$output = Lumina::getAliasPath($input, null, '/my/base/path');
	lumina_test_identical($input, $expected, $output);		
}

lumina_test_end();


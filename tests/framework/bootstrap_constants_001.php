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

include '../lumina.php';

define('L_APPLICATION_ROOT', '/var/www');
$lumina = realpath('../../framework/bootstrap.php');
include $lumina;

lumina_test_start('Bootstrap Constants');

lumina_test_identical('L_INSTALLATION_DIRECTORY', dirname($lumina), L_INSTALLATION_DIRECTORY);
lumina_test_identical('L_APPLICATION', '/var/www/application', L_APPLICATION);
lumina_test_identical('L_PUBLIC', '/var/www', L_PUBLIC);
lumina_test_identical('L_SYSTEM', L_INSTALLATION_DIRECTORY . '/system', L_SYSTEM);

lumina_test_end();


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

include 'functions.php';

define('L_APPLICATION_ROOT', '/var/www');
include '../framework/bootstrap.php';

lumina_test_start();

lumina_test_identical('L_APPLICATION', L_APPLICATION, '/var/www/application');
lumina_test_identical('L_PUBLIC', L_PUBLIC, '/var/www');
lumina_test_identical('L_SYSTEM', L_SYSTEM, L_INSTALLATION_DIRECTORY . '/system');

lumina_test_end();


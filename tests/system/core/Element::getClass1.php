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
use \system\core\Element;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../framework/bootstrap.php';
require '../../functions.php';

class MyElement extends Element {}

$e2 = new MyElement();

lumina_test_start();
lumina_test_identical('MyElement', $e2->getClass(true), 'MyElement');
lumina_test_identical('MyElement', $e2->getClass(false), 'MyElement');
lumina_test_end();


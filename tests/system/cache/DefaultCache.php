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

require('../../functions.php');
lumina_test_start();

define('L_APPLICATION_ROOT', dirname(__FILE__));
define('L_APPLICATION', realpath('../../applications/static001'));
require('../../../framework/bootstrap.php');

$app = Lumina::load('~settings.default');
$context = $app->getModule('mod01')->getModule('mod02')->getModule('mod03')->getController('message');
$cache = $context->getComponent('cache');

lumina_test_identical('has', $context->hasComponent('cache') ? 'true':'false', 'true');
lumina_test_identical('class', 'system\\cache\\DefaultCache', $cache->getClass());
lumina_test_end();




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

lumina_test_start('Router::createUrl(...)');

$app = Lumina::load(null, 'system\\web\\Application');
$router = $app->getComponent('router');

lumina_test_identical('index', 'index.php', $router->createUrl(null));
lumina_test_identical('home', 'index.php?action=home', $router->createUrl('home'));
lumina_test_identical('user.profile', 'index.php?action=user.profile', $router->createUrl('user/profile'));
lumina_test_identical('user.profile:3', 'index.php?action=user.profile&id=3', $router->createUrl('user/profile', array('id' => 3)));
lumina_test_identical('user.profile:3:0', 'index.php?action=user.profile&id=3&ajax=0', $router->createUrl('user/profile', array('id' => 3, 'ajax' => false)));
lumina_test_identical('3:0', 'index.php?id=3&ajax=0', $router->createUrl(null, array('id' => 3, 'ajax' => false)));

list($route, $parameters) = $router->getRequestRoute();
lumina_test_report('request', null, array('Route' => lumina_test_stringify($route), 'Parameters' => print_r($parameters, true)));

lumina_test_end();


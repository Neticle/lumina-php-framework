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

lumina_test_start('Breadcrumb');

$app = Lumina::load(array(

	'components' => array(
		'breadcrumb' => array(
			'class' => 'system\\web\\navigation\\Breadcrumb'
		)
	)
	
), 'system\\web\\Application');

$bc = $app->getComponent('breadcrumb');

$bc->addItem(array('admin'), 'Administration');
$bc->addItem(array('admin/user/index'), 'Users');
$bc->addItem(array('admin/user/view', 'id' => 25), 'Pedro Bispo');
$bc->deploy();

lumina_test_end();


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

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../framework/bootstrap.php';
require '../../functions.php';

$app = Lumina::load(/*array(
	'components' => array(
		'database' => array(
			'driver' => 'mysql',
			'dsn' => 'host=127.0.0.1;dbname=sakila',
			'user' => 'root',
			'password' => 'password'
		)
	)
)*/);

class MyModel extends \system\data\Model
{
	protected function getValidationRules()
	{
		return array(
			array('length', 'name', 'required' => true, 'safe' => false, 'minimum' => 5, 'maximum' => 12),
			array('email', 'email', 'required' => false)
		);
	}
}

$m = new MyModel();
$m->setAttribute('email', 'pedro.bispo@@neticle.pt');
$m->setAttribute('name', 'Pedro');

lumina_test_start();
lumina_test_identical('safe', 'email', implode(', ', $m->getSafeAttributeNames()));

$m->setAttribute('email', 'pedro.bispo@@neticle.pt');
lumina_test_identical('email:pedro.bispo@@neticle.pt', 0, (int) $m->validate());

$m->setAttribute('email', 'pedro.bispo@3neticle.pt');
lumina_test_identical('email:pedro.bispo@3neticle.pt', 1, (int) $m->validate());

$m->setAttribute('email', 'pedro.bispo@3neticle..pt');
lumina_test_identical('email:pedro.bispo@3neticle..pt', 0, (int) $m->validate());

$m->setAttribute('email', 'pedro.bispo@3neticle..pt');
lumina_test_identical('email:pedro.bispo@3neticle.pt1', 0, (int) $m->validate());

$m->setAttribute('email', '');
lumina_test_identical('email:null', 1, (int) $m->validate());


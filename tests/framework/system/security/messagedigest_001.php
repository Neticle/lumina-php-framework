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
require '../../../../framework/bootstrap.php';
require '../../../lumina.php';

lumina_test_start('MessageDigest Component (x500k rehash)');

$app = Lumina::load(array(
	
	'components' => array(
		'digest' => array(
			'class' => 'system\\security\\MessageDigest',
			'algorithm' => 'sha1',
			'rehashCount' => 500000
		)
	)
	
));

$digest = $app->getComponent('digest');
$digest->setSalt(null);

lumina_test_identical('md5: password', '311a7d9968e44171deaad379c8e800e5', $digest->digest('password', null, 'md5'));
lumina_test_identical('sha1: password', '8e2e8d9848deacd40ff296aeef52efce86811b62', $digest->digest('password', null, 'sha1'));

$digest->setSalt('12');
lumina_test_identical('sha1: password (salt="12", tokens=25, 32)', 'e2f8196d5b4fb8f02098779eee5ed5067cb2668d', $digest->digest('password', array(25, 32), 'sha1'));

$digest->setSalt('123');
lumina_test_identical('sha1: password (salt="123")', 'e670b1b430e503d2b1e31b081fa8b6c8f33ce705', $digest->digest('password', null, 'sha1'));

lumina_test_end();

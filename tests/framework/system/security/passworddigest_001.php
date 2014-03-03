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

lumina_test_start('PasswordDigest Component');

$app = Lumina::load(array(
	
	'components' => array(
		'digest' => array(
			'class' => 'system\\security\\PasswordDigest',
			'salt' => 'JepL?gI5m!qb1E$5RDl&dQP3?lhCw!x66hz$pe%_'
		)
	)
	
));

$digest = $app->getComponent('digest');

lumina_test_report('BLOWFISH', null, array(
	'hash' => ($hash = $digest->digest('password', 'blowfish')),
	'compare correct' => lumina_test_stringify($digest->compare('password', $hash)),
	'compare incorrect' => lumina_test_stringify($digest->compare('passwor', $hash))
));

lumina_test_report('MD5', null, array(
	'hash' => ($hash = $digest->digest('password', 'md5', array('rounds' => 10000))),
	'compare correct' => lumina_test_stringify($digest->compare('password', $hash)),
	'compare incorrect' => lumina_test_stringify($digest->compare('passwor', $hash))
));

lumina_test_report('SHA256', null, array(
	'hash' => ($hash = $digest->digest('password', 'sha256', array('rounds' => 10000))),
	'compare correct' => lumina_test_stringify($digest->compare('password', $hash)),
	'compare incorrect' => lumina_test_stringify($digest->compare('passwor', $hash))
));

lumina_test_report('SHA512', null, array(
	'hash' => ($hash = $digest->digest('password', 'sha512')),
	'compare correct' => lumina_test_stringify($digest->compare('password', $hash)),
	'compare incorrect' => lumina_test_stringify($digest->compare('passwor', $hash))
));

lumina_test_end();

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

lumina_test_start('Dictionary');

$app = Lumina::load(null, 'system\\web\\Application');
$dictionary = $app->getComponent('dictionary');

$dictionary->setTexts(array(
	'pt_PT.UTF-8' => array(
		'default' => array(
			'MY_CAR_TYRECOUNT_REPORT' => 'O meu carro {tyrecount, plural, =0{não tem pneus}=1{tem apenas um pneu}other{tem vários pneus}}.'
		)
	)
));

lumina_test_identical('locale', 'en_GB', $dictionary->getLocale());
lumina_test_identical('123.45678', '123.45678', $dictionary->getNumber(123.45678));
lumina_test_identical('123.45678', '$123.46', $dictionary->getCurrency(123.45678, 'USD', 2));

$dictionary->setLocale('pt_PT.UTF-8');

lumina_test_identical('locale', 'pt_PT.UTF-8', $dictionary->getLocale());
lumina_test_identical('tyres', 'O meu carro não tem pneus.', $dictionary->getMessage('MY_CAR_TYRECOUNT_REPORT', array('tyrecount' => 0)));
lumina_test_identical('tyres', 'O meu carro tem apenas um pneu.', $dictionary->getMessage('MY_CAR_TYRECOUNT_REPORT', array('tyrecount' => 1)));
lumina_test_identical('tyres', 'O meu carro tem vários pneus.', $dictionary->getMessage('MY_CAR_TYRECOUNT_REPORT', array('tyrecount' => 2)));
lumina_test_identical('123.45678', '123,45678', $dictionary->getNumber(123.45678));
lumina_test_identical('123.45678', '€123,46', $dictionary->getCurrency(123.45678, null, 2));

lumina_test_end();


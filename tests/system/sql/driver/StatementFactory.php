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

/*
+-------+--------------+------+-----+---------+----------------+
| Field | Type         | Null | Key | Default | Extra          |
+-------+--------------+------+-----+---------+----------------+
| id    | int(11)      | NO   | PRI | NULL    | auto_increment |
| name  | varchar(255) | NO   | UNI | NULL    |                |
| value | varchar(255) | YES  |     | NULL    |                |
+-------+--------------+------+-----+---------+----------------+
*/

use \system\core\Lumina;
use \system\sql\Reader;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../../framework/bootstrap.php';
require '../../../functions.php';

lumina_test_start();

$app = Lumina::load(array(
	'components' => array(
		'database' => array(
			'driver' => 'pgsql',
			'dsn' => 'host=127.0.0.1;dbname=sakila',
			'user' => 'root',
			'password' => 'password'
		)
	)
));

$db = $app->getComponent('database', true);
$factory = $db->getDriver()->getStatementFactory();

lumina_test_identical('Q1', 'SELECT test_table.id, test_table.name, test_table.value FROM `test_table`',
	$factory->createSelectStatement('test_table', array(
		'select' => 'test_table.id, test_table.name, test_table.value'
	))->getSQLStatement()
);

lumina_test_identical('Q2', 'SELECT an_alias.id, an_alias.name, an_alias.value FROM `test_table` `an_alias`',
	$factory->createSelectStatement('test_table', array(
		'select' => 'an_alias.id, an_alias.name, an_alias.value',
		'alias' => 'an_alias'
	))->getSQLStatement()
);

lumina_test_identical('Q2', 'INSERT INTO `test_table` (`id`, `name`, `value`) VALUES (:sfcp_1, :sfcp_2, :sfcp_3)',
	$factory->createInsertStatement('test_table', array(
		'id' => 50,
		'name' => 'some name',
		'value' => 'some values'
	))->getSQLStatement()
);

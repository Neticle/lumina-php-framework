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
require '../../../framework/bootstrap.php';
require '../../functions.php';

$app = Lumina::load(array(
	'components' => array(
		'database' => array(
			'dsn' => 'host=127.0.0.1;dbname=sakila',
			'user' => 'root',
			'password' => 'password'
		)
	)
));

$db = $app->getComponent('database', true);
var_dump($db->scalar('SELECT COUNT(id) FROM test_table'));
var_dump($db->query('SELECT * FROM test_table WHERE id < :max_id', array(':max_id' => 5))->fetchAll(Reader::FETCH_ASSOC));
var_dump($db->query('SELECT * FROM test_table WHERE id < :max_id', array(':max_id' => 5))->fetchColumn());
var_dump($db->query('SELECT * FROM test_table WHERE id < :max_id', array(':max_id' => 5))->fetchColumn(1));

var_dump($db->quote('test_table'));
var_dump($db->quote('test_table.id'));
var_dump($db->quote('sakila.test_table.id'));




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

CREATE TABLE test_table (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	value VARCHAR(255) NULL
);

+-------+--------------+------+-----+---------+----------------+
| Field | Type         | Null | Key | Default | Extra          |
+-------+--------------+------+-----+---------+----------------+
| id    | int(11)      | NO   | PRI | NULL    | auto_increment |
| name  | varchar(255) | NO   | UNI | NULL    |                |
| value | varchar(255) | YES  |     | NULL    |                |
+-------+--------------+------+-----+---------+----------------+

CREATE TABLE test_table_2 (
	id INT AUTO_INCREMENT PRIMARY KEY, 
	column1 TEXT, 
	column2 TINYINT, 
	column3 BLOB, 
	column4 DATETIME, 
	column5 TIMESTAMP, 
	column6 VARCHAR(255)
);

+---------+--------------+------+-----+-------------------+-----------------------------+
| Field   | Type         | Null | Key | Default           | Extra                       |
+---------+--------------+------+-----+-------------------+-----------------------------+
| id      | int(11)      | NO   | PRI | NULL              | auto_increment              |
| column1 | text         | YES  |     | NULL              |                             |
| column2 | tinyint(4)   | YES  |     | NULL              |                             |
| column3 | blob         | YES  |     | NULL              |                             |
| column4 | datetime     | YES  |     | NULL              |                             |
| column5 | timestamp    | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
| column6 | varchar(255) | YES  |     | NULL              |                             |
+---------+--------------+------+-----+-------------------+-----------------------------+

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
			'driver' => 'mysql',
			'dsn' => 'host=127.0.0.1;dbname=sakila',
			'user' => 'root',
			'password' => 'password'
		)
	)
));

$driver = $app->getComponent('database')->getDriver();
$schema = $driver->getSchema()->getDatabaseSchema();

lumina_test_identical('db.name', 'sakila', $schema->getName());
lumina_test_identical('db.test_table.name', 'test_table', $schema->getTable('test_table')->getName());
lumina_test_identical('db.test_table_2.has', 'true', $schema->hasTable('test_table_2') ? 'true' : 'false');
lumina_test_identical('db.test_table_2.id.type', 'int', $schema->getTable('test_table_2')->getColumn('id')->getType());
lumina_test_identical('db.test_table_2.id.required', 'true', $schema->getTable('test_table_2')->getColumn('id')->isRequired() ? 'true' : 'false');
lumina_test_identical('db.test_table_2.column1.required', 'false', $schema->getTable('test_table_2')->getColumn('column1')->isRequired() ? 'true' : 'false');

lumina_test_end();

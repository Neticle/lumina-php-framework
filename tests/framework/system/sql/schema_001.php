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

// MySQL:

CREATE DATABASE sakila 
	DEFAULT COLLATE utf8_general_ci 
	DEFAULT CHARSET utf8;

CREATE TABLE lumina_test_table_002 (

id INT UNSIGNED AUTO_INCREMENT NOT NULL,

column1 VARCHAR(255) NOT NULL,
column2 TINYINT NOT NULL,
column3 CHAR(1) NOT NULL,
column4 BLOB NULL,
column5 TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,

CONSTRAINT lumina_test_table_002_pk_id
PRIMARY KEY (id)
);

*/

use \system\core\Lumina;
use \system\sql\Expression;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../../framework/bootstrap.php';
require '../../../lumina.php';

$app = Lumina::load(array(
	
	'components' => array(
		'database' => array(
			'dsn' => 'host=127.0.0.1;dbname=sakila;charset=utf8',
			'user' => 'root',
			'password' => 'password'
		)
	)
	
));

$db = $app->getComponent('database');

$driver = $db->getDriver();
$schema = $driver->getSchema();
$dbschema = $schema->getDatabaseSchema();
$table = $schema->getTableSchema('lumina_test_table_002');


lumina_test_start();
lumina_test_identical('driver.name', 'mysql', $driver->getName());
lumina_test_identical('schema.tables.count', 2, count($dbschema->getTables()));
lumina_test_identical('table.columns.count', 6, count($table->getColumns()));
lumina_test_identical('table.pk', 'id', implode(', ', $table->getPrimaryKey()));

lumina_test_identical('table.id.type', 'int', $table->getColumn('id')->getType());
lumina_test_identical('table.column1.type', 'string', $table->getColumn('column1')->getType());
lumina_test_identical('table.column2.type', 'int', $table->getColumn('column2')->getType());
lumina_test_identical('table.column3.type', 'char', $table->getColumn('column3')->getType());
lumina_test_identical('table.column4.type', 'binary', $table->getColumn('column4')->getType());
lumina_test_identical('table.column5.type', 'timestamp', $table->getColumn('column5')->getType());

lumina_test_identical('table.column1.required', true, $table->getColumn('column1')->isRequired());
lumina_test_identical('table.column4.required', false, $table->getColumn('column4')->isRequired());

lumina_test_identical('table.column3.size', 1, $table->getColumn('column3')->getSize());

lumina_test_end();


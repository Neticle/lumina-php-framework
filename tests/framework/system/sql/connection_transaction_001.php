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

CREATE TABLE lumina_test_table_001 (

id INT UNSIGNED AUTO_INCREMENT NOT NULL,

column1 VARCHAR(255) UNIQUE NOT NULL,
column2 VARCHAR(255) NOT NULL,
column3 VARCHAR(255) NULL,
column4 TINYINT(1) NOT NULL DEFAULT 0,
column5 TINYINT NOT NULL,
column6 TINYINT NULL,
column7 INT NOT NULL,
column8 INT NULL,
column9 INT,
column10 TIMESTAMP NOT NULL,
column11 DATETIME NULL,

CONSTRAINT lumina_test_table_001_pk_id
PRIMARY KEY (id)
);

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
use \system\sql\driver\Driver;

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



lumina_test_start('Database Transaction');

$db->delete('lumina_test_table_002');

$db->insert('lumina_test_table_002', array(
	'column1' => 'value1',
	'column2' => 127,
	'column3' => '1'
));

lumina_test_identical('lumina_test_table_002.column1', 127, $db->select('lumina_test_table_002', array('select' => 'column2', 'condition' => 'column1=\'value1\''), true));

$db->startTransaction();

$db->delete('lumina_test_table_002');

$db->insert('lumina_test_table_002', array(
	'column1' => 'value1',
	'column2' => 125,
	'column3' => '1'
));

$db->rollBack();

lumina_test_identical('lumina_test_table_002.column1', 127, $db->select('lumina_test_table_002', array('select' => 'column2', 'condition' => 'column1=\'value1\''), true));

$db->startTransaction();

$db->delete('lumina_test_table_002');

$db->insert('lumina_test_table_002', array(
	'column1' => 'value1',
	'column2' => 125,
	'column3' => '1'
));

$db->commit();

lumina_test_identical('lumina_test_table_002.column1', 125, $db->select('lumina_test_table_002', array('select' => 'column2', 'condition' => 'column1=\'value1\''), true));

lumina_test_end();

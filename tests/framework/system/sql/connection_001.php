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

*/

use \system\core\Lumina;
use \system\sql\Expression;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../../framework/bootstrap.php';

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

if ($db->exists('lumina_test_table_001', array('condition' => 'column1=:value', 'parameters' => array(':value' => 'column1-value'))))
{
	$db->delete('lumina_test_table_001', array('condition' => 'column1=\'column1-value\''));
}

$db->insert('lumina_test_table_001', array(
	'column1' => 'column1-value',
	'column2' => 'column2-value',
	'column3' => null,
	'column4' => 0,
	'column5' => 21,
	'column6' => null,
	'column7' => -3245,
	'column8' => 3245,
	'column9' => null,
	'column10' => new Expression('NOW()'),
	'column11' => null
));


$db->update('lumina_test_table_001', array(
	'column2' => 'column2-value-updated'
), array(
	'condition' => 'column1=:column1',
	'parameters' => array(
		':column1' => 'column1-value'
	)
));

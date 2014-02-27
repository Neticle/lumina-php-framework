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

DELIMITER $$

CREATE PROCEDURE LUMINA_TEST_PROCEDURE_001
(
IN __a INT UNSIGNED,
IN __b INT UNSIGNED
)
BEGIN
	SELECT (__a + __b) result;
END $$

DELIMITER ;

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

$result1 = $db->call('LUMINA_TEST_PROCEDURE_001', array(
	1 => 10,
	2 => 23
))->fetchScalar();

$result2 = $db->call('LUMINA_TEST_PROCEDURE_001', array(
	':named' => 5,
	':named2' => 8
))->fetchScalar();

$result3 = $db->call('LUMINA_TEST_PROCEDURE_001', array(
	':named' => 5,
	1 => new \system\sql\Expression('9')
))->fetchScalar();

$result4 = $db->call('LUMINA_TEST_PROCEDURE_001', array(
	6,
	new \system\sql\Expression('9')
))->fetchScalar();

lumina_test_start('Database Procedure Scalar');
lumina_test_identical('10+23', 33, $result1);
lumina_test_identical('5+8', 13, $result2);
lumina_test_identical('5+9', 14, $result3);
lumina_test_identical('6+9', 15, $result4);
lumina_test_end();


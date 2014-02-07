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
use \system\sql\data\Record;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../../../framework/bootstrap.php';
require '../../../../lumina.php';

lumina_test_start('Record Save/Update');

$app = Lumina::load(array(
	
	'components' => array(
		'database' => array(
			'dsn' => 'host=127.0.0.1;dbname=sakila;charset=utf8',
			'user' => 'root',
			'password' => 'password'
		)
	)
	
));

class TestTable2 extends Record
{
	protected function getTableName()
	{
		return 'lumina_test_table_002';
	}
	
	
}

$record = new TestTable2();
$record->setAttributes(array(
	'column1' => 'column1 value',
	'column2' => 3,
	'column3' => 'c',
	'column4' => new Expression('NOW()')
));

$id = null;

if ($record->save())
{
	$record->column2 = 10;
	$record->save();
	$id = $record->id;
}

lumina_test_set('lumina_test_table_002.id (++)', $id);
lumina_test_end();

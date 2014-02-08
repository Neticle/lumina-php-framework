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

lumina_test_start('Record Find/FindAll');

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
	public static function model($context = 'search')
	{
		return self::getBaseModel(__CLASS__, $context);
	}

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

if ($record->save())
{
	$match = TestTable2::model()->findByAttributes(array(
		'id' => $record->id
	));
	
	lumina_test_set('Match ID', $record->id);
	lumina_test_identical('Primary Key', $record->id, $match->getPrimaryKey()['id']);
	
	if ($match)
	{
		lumina_test_identical('match.column1', $match->column1, 'column1 value');
		lumina_test_identical('match.column2', $match->column2, 3);
		lumina_test_identical('match.column3', $match->column3, 'c');
		lumina_test_set('match.column4', $match->column1);
	}
	
	
}

$instances = TestTable2::model()->findAllByAttributes(array(
));

lumina_test_identical('Exists (1)', true, TestTable2::model()->exists());

foreach ($instances as $instance)
{
	$instance->delete();
}

lumina_test_identical('Count', 0, TestTable2::model()->count());
lumina_test_identical('Exists (2)', false, TestTable2::model()->exists());

lumina_test_set('Record ID', $record->id);
lumina_test_end();



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
use \system\ext\web\widget\grid\GridWidget;
use \system\sql\Expression;
use \system\sql\data\Record;
use \system\sql\data\provider\RecordProvider;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../../../../../framework/bootstrap.php';
require '../../../../../../lumina.php';

class TestTable2 extends Record
{
	public static function model($context = 'search')
	{
		return self::getBaseModel(__CLASS__, $context);
	}

	public function getTableName()
	{
		return 'lumina_test_table_002';
	}
}

lumina_test_start('GridWidget');

$app = Lumina::loadWebApplication(array(
	
	'components' => array(
		'database' => array(
			'dsn' => 'host=127.0.0.1;dbname=sakila;charset=utf8',
			'user' => 'root',
			'password' => 'password'
		)
	)
	
));

if (TestTable2::model()->count() !== 100)
{
	for ($i = 0; $i < 100; ++$i)
	{
		(new TestTable2('insert', array(
			'column1' => 'V' . ($i + 1),
			'column2' => $i,
			'column3' => 't'
		)))->save(false);
	}
}

$p = new RecordProvider(new TestTable2(), array(
	'paginator' => array(
		'activePage' => 1
	)
));

$widget = new GridWidget($p, array(
	'columns' => 'id,column1,column2,column3'
));

$widget->deploy();

lumina_test_end();

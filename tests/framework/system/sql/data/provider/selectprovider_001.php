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
use \system\sql\data\provider\SelectProvider;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../../../../framework/bootstrap.php';
require '../../../../../lumina.php';

lumina_test_start('SelectProvider');

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
	public static function model()
	{
		return parent::getBaseModel(__CLASS__);
	}

	protected function getTableName()
	{
		return 'lumina_test_table_002';
	}
}


TestTable2::model()->deleteAll();

for ($i = 0; $i < 100; ++$i)
{
	$record = new TestTable2('insert', array(
		'column1' => ($i + 1) . 'R',
		'column2' => $i,
		'column3' => 't'
	));
	
	$record->save(false);
}


$provider = new SelectProvider('lumina_test_table_002', array(
	'criteria' => array(
		'alias' => 't',
		'select' => 't.*'
	),
	'sorter' => array(
		'rules' => array(
			'id' => 'asc'
		)
	),
	'paginator' => array(
		'interval' => 10,
		'activePage' => 1
	)
));

$page = array();

foreach ($provider->getIterator() as $record)
{
	$page[] = $provider->getItemFieldValue($record, 'column1') . 
		$provider->getItemFieldValue($record, 'column2');
}

$page = implode(', ', $page);

$page2 = array();

$provider->getPaginator()->setActivePage(2);
foreach ($provider->getIterator(true) as $record)
{
	$page2[] = $provider->getItemFieldValue($record, 'column1') . 
		$provider->getItemFieldValue($record, 'column2');
}

$page2 = implode(', ', $page2);

lumina_test_identical('Count', 100, $provider->getTotalItemCount());
lumina_test_identical('Page Count', 10, $provider->getPaginator()->getPageCount());
lumina_test_identical('Page 1', '1R0, 2R1, 3R2, 4R3, 5R4, 6R5, 7R6, 8R7, 9R8, 10R9', $page);
lumina_test_identical('Page 1', '11R10, 12R11, 13R12, 14R13, 15R14, 16R15, 17R16, 18R17, 19R18, 20R19', $page2);

lumina_test_end();

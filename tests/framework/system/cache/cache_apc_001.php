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
require '../../../../framework/bootstrap.php';
require '../../../lumina.php';

lumina_test_start('Record Cache');

$app = Lumina::loadWebApplication(array(
	
	'components' => array(
		'database' => array(
			'dsn' => 'host=127.0.0.1;dbname=sakila;charset=utf8',
			'user' => 'root',
			'password' => 'password'
		),
		
		'cache' => array(
			'class' => 'system\\cache\\ApcCache',
			'prefix' => 'mycp:'
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

$cache = $app->getComponent('cache');
$count = $cache->read('lumina.test.cache.record001.count');

if (isset($count))
{
	lumina_test_report('Cached Result Available', true, array(
		'count' => $count
	));
	
	if (isset($_GET['clearall']))
	{
		$cache->clear('lumina.test.cache.record*.count');
	}
}
else
{
	$count = TestTable2::model()->count();

	lumina_test_report('Cached Result Not Available', true, array(
		'count' => $count
	));
	
	$cache->write('lumina.test.cache.record001.count', $count, 5);
}

lumina_test_end();

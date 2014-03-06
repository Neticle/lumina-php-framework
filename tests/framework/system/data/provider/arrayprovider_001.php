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

use \system\data\provider\ArrayProvider;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../../../framework/bootstrap.php';
require '../../../../lumina.php';

// 1 ~ 100
$items = array();

for ($i = 99; $i > -1; --$i)
{
	$items[] = array('id' => $i + 1, 'title' => 'R' . $i);
}

$provider = new ArrayProvider($items, array(
	'paginator' => array(
		'interval' => '10'
	),
	'sorter' => array(
		'rules' => array(
			'id' => 'asc'
		)
	)
));

// build the page content
$page = array();

foreach ($provider as $row)
{
	$page[] = $row['id'] . $row['title'];
}

$page = implode(', ', $page);

// build the page content
$page2 = array();
$provider->getPaginator()->setActivePage(2);

foreach ($provider->getIterator(true) as $row)
{
	$page2[] = $row['id'] . $row['title'];
}

$page2 = implode(', ', $page2);

lumina_test_start('ArrayProvider');
lumina_test_identical('Page Count', 10, $provider->getPaginator()->getPageCount());
lumina_test_identical('Page 1', '1R0, 2R1, 3R2, 4R3, 5R4, 6R5, 7R6, 8R7, 9R8, 10R9', $page);
lumina_test_identical('Page 2', '11R10, 12R11, 13R12, 14R13, 15R14, 16R15, 17R16, 18R17, 19R18, 20R19', $page2);
lumina_test_end();


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

use \system\data\sorter\ArraySorter;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../../../framework/bootstrap.php';
require '../../../../lumina.php';

// 1 ~ 100
$items = array();

for ($i = 95; $i > 0; --$i)
{
	$items[] = array('id' => 0, 'title' => 'item #' . $i);
}

$sorter = new ArraySorter(array(
	'rules' => array(
		'id' => 'asc',
		'title' => 'desc',
	)
));

var_dump($sorter->sort($items));

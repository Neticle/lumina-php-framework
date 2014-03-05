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

use \system\data\paginator\ArrayPaginator;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../../../framework/bootstrap.php';
require '../../../../lumina.php';

// 1 ~ 100
$items = array();

for ($i = 1; $i < 96; ++$i)
{
	$items[] = 'R' . $i;
}

$paginator = new ArrayPaginator(array(
	'interval' => '10'
));

lumina_test_start('ArrayPaginator');
lumina_test_identical('Page Count', 10, $paginator->getPageCount(count($items)));
lumina_test_identical('Page 1', 'R1, R2, R3, R4, R5, R6, R7, R8, R9, R10', implode(', ', $paginator->filter($items, 1)));
lumina_test_identical('Page 2', 'R11, R12, R13, R14, R15, R16, R17, R18, R19, R20', implode(', ', $paginator->filter($items, 2)));
lumina_test_identical('Page 10', 'R91, R92, R93, R94, R95', implode(', ', $paginator->filter($items, 10)));
lumina_test_end();


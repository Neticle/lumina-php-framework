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

Estimated number of calls per request:
50 ~ 1000

Results:
	
	2		2.3841857910156E-5
	50		0.00014996528625488
	200		0.00046992301940918
	1000	0.0022730827331543
	75.000	0.17361402511597

*/


use \system\core\Lumina;

include '../../../framework/system/core/Lumina.php';

Lumina::setPackagePath('application', '/var/www');

$start = microtime(true);

for ($i = 0; $i < 75000; ++$i)
{
	Lumina::getClassPath('application\\modules\\user\\controllers\\UserController');
}

echo microtime(true) - $start;


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
2 ~ 50

Results:
	
	2		3.0994415283203E-5
	50		0.00023603439331055
	200		0.0008690357208252
	1000	0.0042212009429932
	5000	0.021378993988037
	10000	0.044900178909302
	50000	0.21401619911194
	75.000	0.31712102890015

*/


use \system\core\Lumina;

include '../../../framework/system/core/Lumina.php';

Lumina::setPackagePath('application', '/var/www');

$start = microtime(true);

for ($i = 0; $i < 50000; ++$i)
{
	Lumina::getAliasPath('this.is.a.relative.alias.right.here', 'layout.php', '/some/long/base/path');
}

echo microtime(true) - $start;


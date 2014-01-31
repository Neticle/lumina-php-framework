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

Expected output:
	[p1v:p2v][p1v:p2v]
	[p1v:p2v]|[p1v:p2v]|
	captured Class1
	captured Class2
	captured Class2A

*/

use \system\core\Lumina;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../framework/bootstrap.php';

class Class1 {}
class Class2 {}
class Class2A extends Class2 {}

$c1 = new Class1;
$c2 = new Class2;
$c2a = new Class2A;

$bus = Lumina::getEventBus();
$bus->raiseArray($c1, 'test', array('p1v', 'p2v'));

// Should print "[p1v:p2v][p1v:p2v]"
$bus->on('test', function($source, $p1, $p2) {
		echo '[', $p1, ':', $p2, ']';
		return true;
});

$bus->raiseArray($c1, 'test', array('p1v', 'p2v'));
$bus->raiseArray($c1, 'test', array('p1v', 'p2v'));

echo '<br />';


// Should print "[p1v:p2v]|[p1v:p2v]|"
$bus->on('test', function($source, $p1, $p2) {
	echo '|';
	return true;
});

$bus->raiseArray($c1, 'test', array('p1v', 'p2v'));
$bus->raiseArray($c1, 'test', array('p1v', 'p2v'));

echo '<br />';

// Should print "captured Class1"
$bus->on('capture', function($source) {
	echo 'captured ', get_class($source);
});

$bus->raiseArray($c1, 'capture');

echo '<br />';

// Should print "captured Class2"
$bus->onClassEvent('Class2', 'capture', function($source) {
	echo 'captured ', get_class($source);
});

$bus->raiseArray($c2, 'capture');

echo '<br />';

// Should print "captured Class2A"
$bus->raiseArray($c2a, 'capture');


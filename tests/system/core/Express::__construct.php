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
	5 + 5 = 10

*/

use \system\core\Lumina;
use \system\core\Express;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../framework/bootstrap.php';

class Sum extends Express {
	
	private $_a;
	
	private $_b;
	
	public function setA($a)
	{
		$this->_a = $a;
	}
	
	public function setB($b)
	{
		$this->_b = $b;
	}
	
	public function getResult()
	{
		return $this->_a + $this->_b;
	}
	
}

$o = new Sum(array(
	'a' => 5,
	'b' => 5
));

echo '5 + 5 = ', $o->getResult();


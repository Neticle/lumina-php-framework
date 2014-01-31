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

Should output:

	ctx1/ctx2/ctx3/ctx4/ctx5
	
*/

use \system\core\Lumina;
use \system\core\Context;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../framework/bootstrap.php';
require '../../functions.php';

class MyContext extends Context
{
	public function __construct($name, Context $parent = null, array $config = null)
	{
		parent::__construct($name, $parent);
		$this->construct($config);
	}
}

$ctx = new MyContext('ctx0', null);

for ($i = 1; $i < 6; ++$i)
{
	$child = new MyContext('ctx' . $i, $ctx);
	$ctx = $child;
}

echo $ctx->getRoute();


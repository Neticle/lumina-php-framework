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
		
	visible=this is visible;
	not_visible=
	not-visible=
	with-p1=p1=VP1;
	with_p1=p1=VP1;
	with-p2=p1=VP1X;p2=VP2;
	with-p3=p1=VP1X;p2=VP2X;p3=default
	with-p3=p1=VP1X;p2=VP2X;p3=VP3

*/

use \system\core\Lumina;
use \system\base\Controller;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../framework/bootstrap.php';
require '../../functions.php';

class MessageController extends Controller
{
	public function actionVisible()
	{
		echo 'this is visible;';
	}
	
	protected function actionNotVisible()
	{
		echo 'this is not visibile;';
	}
	
	public function actionWithP1($p1)
	{
		echo 'p1=', $p1, ';';
	}
	
	public function actionWithP2($p1, $p2)
	{
		echo 'p1=', $p1, ';', 'p2=', $p2, ';';
	}
	
	public function actionWithP3($p1, $p2, $p3 = 'default')
	{
		echo 'p1=', $p1, ';', 'p2=', $p2, ';p3=', $p3;
	}
}

$ctrl = new MessageController('message', null);

echo '<br />visible=';
$ctrl->dispatch('visible');

echo '<br />not_visible=';
$ctrl->dispatch('not_visible');

echo '<br />not-visible=';
$ctrl->dispatch('not-visible');

echo '<br />with-p1=';
$ctrl->dispatch('with-p1', array('p1' => 'VP1'));

echo '<br />with_p1=';
$ctrl->dispatch('with_p1', array('p1' => 'VP1'));

echo '<br />with-p2=';
$ctrl->dispatch('with-p2', array('p1' => 'VP1X', 'p2' => 'VP2'));

echo '<br />with-p3=';
$ctrl->dispatch('with-p3', array('p1' => 'VP1X', 'p2' => 'VP2X'));

echo '<br />with-p3=';
$ctrl->dispatch('with-p3', array('p1' => 'VP1X', 'p2' => 'VP2X', 'p3' => 'VP3'));

echo '<br />', microtime(true)-L_START;



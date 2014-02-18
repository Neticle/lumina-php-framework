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

namespace application\module\mod01\controller;

use \system\web\Controller;

/**
 * Test Controller
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package application.modules.mod01.controllers
 * @since 0.2.0
 */
class TestController extends Controller
{
	public function actionIndex($redirect = false, $emulate = false)
	{
		if ($redirect)
		{
			$this->redirect(array('test/forward-action-b', 'emulate' => true));
		}
		
		else if ($emulate)
		{
			$route = $this->getComponent('router')->getRequestRoute();
			$this->forward('/' . $route[0], $route[1]);
		}
	
		$assets = $this->getComponent('assetManager');
		$base = $assets->publish('application.module.mod01.assets');
		echo $base . 'test-asset.txt';
	}
	
	public function actionForwardActionB()
	{
		$this->forward('actionB');
	}
	
	public function actionActionB()
	{
		echo 'this is action b';
	}
}

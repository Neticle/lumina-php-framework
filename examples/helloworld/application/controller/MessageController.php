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

namespace application\controller;

use \system\base\Controller;

/**
 * A simple message controller to display a "Hello World" message.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package application.controllers
 * @since 0.2.0
 */
class MessageController extends Controller
{
	/**
	 * Displays a simple "Hello World" message.
	 */
	public function actionHelloWorld()
	{
		echo 'Hello World';
	}
}


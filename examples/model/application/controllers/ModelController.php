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

namespace application\controllers;

use \application\models\User;
use \system\base\Controller;

/**
 * A simple controller that shows you how you can use a custom model to validate
 * input data received from any form.
 *
 * For the purpose of simplifying this example, a regular HTML form is used,
 * without any widgets.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package application.controllers
 * @since 0.2.0
 */
class ModelController extends Controller
{
	/**
	 * Displays the form view and processes its input.
	 */
	public function actionIndex()
	{
		$user = new User('create');
		
		if (isset($_POST['User']))
		{
			$user->bindAttributes($_POST['User']);
			
			if ($user->validate())
			{
				echo '<h1>Model Validated!</h1>';
				var_dump($user->getAttributes(true));
				return;
			}
		}
		
		$this->render('~index', array(
			'model' => $user
		));
	}
}

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

use \system\web\Controller;
use \system\web\Request;
use \system\web\Response;
use application\model\User;

/**
 * Description of UserController
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class UserController extends Controller
{
    
	public function actionLogin () 
	{
		$error = false;
		$errorMsg = '';
		$username = '';
		
		if(Request::getMethod() === 'POST')
		{
			//var_dump($_SERVER); die;
			$username = Request::getString('username', $_POST);
			$password = Request::getString('password', $_POST);
			$returnRoute = Request::getString('return_route', $_GET, false, null);
			
			$user = User::model()->findByCredentials($username, $password);
			
			if($user !== null)
			{
				$this->getComponent('session')->write('id_user', $user->id);
				
				if($returnRoute !== null)
				{
					$route = json_decode(base64_decode($returnRoute), true);

					Response::setLocation($route);
				}
				else
				{
					$this->redirect(array('/'));
				}
				
				return;
			}
			else
			{
				$error = true;
				$errorMsg = 'The provided credentials do not match any existing account.';
			}
		}
		
		$this->render('~login', array(
			'error' => $error,
			'errorMsg' => $errorMsg,
			'username' => $username
		));
	}
	
}

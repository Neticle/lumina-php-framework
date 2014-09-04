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
use application\model\User;

/**
 * The default controller.
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
class DefaultController extends Controller
{
	
	public function actionIndex () 
	{
		$session = $this->getComponent('session');
		
		$idUser = $session->read('id_user');
		
		if($idUser === null)
		{
			$this->redirect(array('/user/login'));
			
			return;
		}
		
		$user = User::model()->findByAttributes(array('id' => $idUser));
		
		$this->render('~index', array(
			'user' => $user
		));
	}
	
}

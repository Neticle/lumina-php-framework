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

use \system\data\Model;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../../framework/bootstrap.php';
require '../../../lumina.php';

class MyModel extends Model
{
	protected function getValidationRules()
	{
		return array(
			array('required', 'name,surname,email,password'),
			
			array('length', 'name,surname', 'minimum' => 2, 'maximum' => 25),
			array('length', 'email', 'maximum' => 125),
			array('length', 'password', 'minimum' => 6, 'maximum' => 32),
			
			array('email', 'email'),
			
			array('range', 'age', 'required' => true, 'integer' => true, 'minimum' => 13, 'maximum' => 125),
		);
	}
}



$model = new MyModel();
$model->bindAttributes(array(
	'name' => 'Pedro',
	'surname' => 'B',
	'email' => 'pedro.bispo@neticle.pt',
	'age' => 5.3,
	'password' => 'p3dr0.b1sp0'
));

lumina_test_start();
lumina_test_identical('validate.name', true, $model->validate(array('name')));
lumina_test_identical('validate.surname', false, $model->validate(array('surname')));
lumina_test_identical('validate.email', true, $model->validate(array('email')));
lumina_test_identical('validate.age', false, $model->validate(array('age')));
lumina_test_identical('validate.password', true, $model->validate(array('password')));

$model->surname = 'Bispo';
$model->age = 21;

lumina_test_identical('model.name', 'Pedro', $model->name);
lumina_test_identical('model.surname', 'Bispo', $model->surname);
lumina_test_identical('model.email', 'pedro.bispo@neticle.pt', $model->email);
lumina_test_identical('model.age', 21, $model->age);
lumina_test_identical('model.password', 'p3dr0.b1sp0', $model->password);
lumina_test_identical('model.nonExistent', null, $model->nonExistent);

lumina_test_report('validation.result', $model->validate(), array('Report' => print_r($model->getAttributeErrors(), true)));

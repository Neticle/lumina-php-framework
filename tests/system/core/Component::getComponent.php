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

	[construct][after-construct][init][after-init][construct][after-construct][init][after-init]
	
*/

use \system\core\Lumina;
use \system\base\Component;
use \system\core\Extension;

define('L_APPLICATION_ROOT', dirname(__FILE__));
require '../../../framework/bootstrap.php';
require '../../functions.php';

class MyComponent extends Component
{
	protected function onConstruction()
	{
		if (parent::onConstruction())
		{
			echo '[construct]';
			return true;
		}
		
		return false;
	}
	
	protected function onAfterConstruction()
	{
		if (parent::onAfterConstruction())
		{
			echo '[after-construct]';
			return true;
		}
		
		return false;
	}
	
	protected function onInitialize()
	{
		if (parent::onInitialize())
		{
			echo '[init]';
			return true;
		}
		
		return false;
	}
	
	protected function onAfterInitialize()
	{
		if (parent::onAfterInitialize())
		{
			echo '[after-init]';
			return true;
		}
		
		return false;
	}
}

class MyExtension extends Extension
{
	public function __construct(Extension $parent = null, array $config = null)
	{
		parent::__construct($parent);
		$this->construct($config);
	}
}

$ext = new MyExtension(null, array(
	'components' => array(
		'test' => array(
			'class' => 'MyComponent',
			'components' => array(
				'test2' => array(
					'class' => 'MyComponent'
				)
			)
		)
	)
));

$ext->getComponent('test', true);
$ext->getComponent('test', true);
$ext->getComponent('test')->getComponent('test2', false);
$ext->getComponent('test')->getComponent('test2', true);


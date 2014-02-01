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
	Message is: success!

*/

define('L_APPLICATION_ROOT', dirname(__FILE__));
define('L_APPLICATION', realpath('../../applications/static001'));
require('../../../framework/bootstrap.php');

$application = new \system\base\Application('application', 'application', null, array(
	'modules' => array(
		'mod01' => array(
			'modules' => array(
				'mod02' => array(
					'modules' => array(
						'mod03' => array()
					)
				)
			)
		)
	)
));

$application->dispatch('mod01/mod02/mod03/message/display', array('message' => 'success!'));
echo '<br />', microtime(true) - L_START;


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

use \system\core\Lumina;

include '../../../lumina.php';
include '../../../applications/static002/index.php';
$app = Lumina::getApplication();

lumina_test_start('Lumina::load(...)');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

lumina_test_identical('application.namespace', 'application', $app->getNamespace());
lumina_test_identical('application.name', 'application', $app->getName());
lumina_test_identical('application.route', null, $app->getRoute());
lumina_test_class('application.class', 'application\\StaticApplication002', $app);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

lumina_test_end();


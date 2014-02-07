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
include '../../../applications/static003/index.php';
$app = Lumina::getApplication();

lumina_test_start('Lumina::load(...)');

$alpha = $app->getModule('alpha', false);
lumina_test_identical('alpha.hasModule ("beta")', true, $alpha->hasModule('beta'));
lumina_test_identical('alpha.hasModule ("charlie")', true, $alpha->hasModule('charlie'));
lumina_test_identical('alpha.hasModule ("delta")', false, $alpha->hasModule('delta'));

lumina_test_identical('alpha.initialized', false, $alpha->isInitialized());

$alpha = $app->getModule('alpha', false);
lumina_test_identical('alpha.initialized (1)', false, $alpha->isInitialized());

$alpha = $app->getModule('alpha', true);
lumina_test_identical('alpha.initialized (2)', true, $alpha->isInitialized());

$beta = $alpha->getModule('beta', false);
lumina_test_identical('beta.initialized', false, $beta->isInitialized());

$beta->initialize();
lumina_test_identical('beta.initialized (1)', true, $beta->isInitialized());

$charlie = $alpha->getModule('charlie', false);
lumina_test_identical('charlie.initialized', false, $charlie->isInitialized());
lumina_test_identical('charlie.hasModule ("delta")', true, $charlie->hasModule('delta'));

$charlie->initialize();
lumina_test_identical('charlie.initialized (1)', true, $charlie->isInitialized());

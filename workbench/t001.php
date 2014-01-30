<?php

use \system\core\Lumina;
include '../framework/system/core/Lumina.php';

Lumina::setPackagePath('application', '/var/www');

var_dump
(
	Lumina::getNamespacePath('application\\s', null)
);


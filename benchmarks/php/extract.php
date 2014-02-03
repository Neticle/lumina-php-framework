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

$variables = array();

for ($i = 0; $i < 10; ++$i)
{
	$variables['variable' . $i] = 'Value of ' . $i;
}

$loop = 10000;
$start = microtime(true);

for ($i = 0; $i < $loop; ++$i)
{
	extract($variables);
	
	if (isset($variable0)) {}
	if (isset($variable1)) {}
	if (isset($variable2)) {}
	if (isset($variable3)) {}
	if (isset($variable4)) {}
	if (isset($variable5)) {}
	if (isset($variable6)) {}
	if (isset($variable7)) {}
	if (isset($variable8)) {}
	if (isset($variable9)) {}
}

echo microtime(true) - $start;
echo '<br />';

$start = microtime(true);

for ($i = 0; $i < $loop; ++$i)
{
	if (isset($variables['variable0']))
	{
		$variable0 = $variables['variable0'];
	}
	if (isset($variables['variable1']))
	{
		$variable1 = $variables['variable1'];
	}
	if (isset($variables['variable2']))
	{
		$variable2 = $variables['variable2'];
	}
	if (isset($variables['variable3']))
	{
		$variable3 = $variables['variable3'];
	}
	if (isset($variables['variable4']))
	{
		$variable4 = $variables['variable4'];
	}
	if (isset($variables['variable5']))
	{
		$variable5 = $variables['variable5'];
	}
	if (isset($variables['variable6']))
	{
		$variable6 = $variables['variable6'];
	}
	if (isset($variables['variable7']))
	{
		$variable7 = $variables['variable7'];
	}
	if (isset($variables['variable8']))
	{
		$variable8 = $variables['variable8'];
	}
	if (isset($variables['variable9']))
	{
		$variable9 = $variables['variable9'];
	}
}

echo microtime(true) - $start;
echo '<br />';



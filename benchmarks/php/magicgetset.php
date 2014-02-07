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

class EmulatedModel
{
	private $data = array();
	
	public function get($property)
	{
		return isset($this->data[$property]) ?
			$this->data[$property] : null;
	}
	
	public function set($property, $value)
	{
		$this->data[$property] = $value;
	}
	
	public function __get($property)
	{
		return isset($this->data[$property]) ?
			$this->data[$property] : null;
	}
	
	public function __set($property, $value)
	{
		$this->data[$property] = $value;
	}
	
}

$data = new EmulatedModel();
$start = microtime(true);

for ($i = 0; $i < 100000; ++$i)
{
	$data->someProperty = $i;
}

$count = 0;
for ($i = 0; $i < 100000; ++$i)
{
	$count += $data->someProperty;
}

echo microtime(true)-$start;
echo '<hr />';




$data = new EmulatedModel();
$start = microtime(true);

for ($i = 0; $i < 100000; ++$i)
{
	$data->set('someProperty', $i);
}

$count = 0;
for ($i = 0; $i < 100000; ++$i)
{
	$count += $data->get('someProperty');
}

echo microtime(true)-$start;
echo '<hr />';


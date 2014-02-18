<?php

$start = microtime(true);

for ($i = 0; $i < 1000; ++$i)
{
	$array = array('module/controller/action', array('param1' => 1, 'param2' => 2));
	$r = $array[0];
	$p = isset($array[1]) ? $array[1] : null;
}

echo microtime(true) - $start;
echo '<br /><br />';


$start = microtime(true);

for ($i = 0; $i < 1000; ++$i)
{
	$array = array('module/controller/action', 'param1' => 1, 'param2' => 2);
	$r = $array[0];
	$p = array_slice($array, 1);
}

echo microtime(true) - $start;



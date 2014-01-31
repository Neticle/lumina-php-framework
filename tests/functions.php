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

define('L_TEST_START', microtime(true));

function lumina_test_start()
{
	echo '<table border="1"><thead><tr><th>Status</th><th>Input</th><th>Output</th>', 
		'<th>Expected</th></tr></thead><tbody>';
}

function lumina_test_end()
{
	echo '<tr><td colspan="4">' , (microtime(true) - L_TEST_START), 
		'</td></tr></tbody></table>';
}

function lumina_test_report($success, $input, $output, $expected)
{
	echo '<tr><td>', ($success ? 'OK' : 'ERROR'), '</td><td>', $input, 
		'</td><td>', $output, '</td><td>', $expected, '</td></tr>';
}

function lumina_test_equal($input, $expected, $output)
{
	lumina_test_report($expected == $output, $input, $output, $expected);
}

function lumina_test_identical($input, $expected, $output)
{
	lumina_test_report($expected === $output, $input, $output, $expected);
}


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

/**
 * Starts the test unit document.
 *
 * @param string $title
 *	The document title.
 */
function lumina_test_start($title = 'Untitled Test')
{
	$title = htmlentities($title);

	echo 
<<<HTML
<!DOCTYPE html>

<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>{$title}</title>
		<style type="text/css">
			*
			{
				margin: 0px;
				padding: 0px;
				border: none;
				border-collapse: collapse;
				outline: none;
		
				font-family: sans-serif;
				font-size: 12px;
				font-weight: normal;
				text-decoration: none;
			}

			body
			{
				padding: 0px 20px 20px 20px;
			}

			h1
			{
				font-size: 1.6em;
				font-weight: bold;
				margin: 20px 0px 10px 0px;
			}

			div.lumina-test
			{
				border-left: 5px solid #000;
				padding: 10px;
				margin: 5px 0px
			}

			div.lumina-test-success
			{
				border-left: 5px solid #52FF4D;
				background: #E9FFE8;
			}

			div.lumina-test-failure
			{
				border-left: 5px solid #FF2433;
				background: #FFE0E3;
			}

			div.lumina-test-description
			{
				font-size: 1.1em;
				font-weight: bold;
			}

			div.lumina-test-entry
			{
				margin: 10px 0px 0px 0px;
			}

			div.lumina-test-entry-label
			{
				font-size: 1em;
				font-weight: bold;
			}

			div.lumina-test-entry-code
			{
				font-family: monospace;
				white-space: pre;
				background-color: #F7F7F7;
				border: 1px solid #cecece;
				padding: 5px;
			}
		</style>
	</head>
	<body>
		<h1 class="lumina-test-title">{$title}</h1>
HTML;
}

/**
 * Ends the test unit document.
 */
function lumina_test_end()
{
	$time = microtime(true) - L_TEST_START;
	
	echo 
<<< HTML
		<div class="lumina-test-microtime">{$time}</div>
	</body>
</html>
HTML;
}

/**
 * Reports the result of a single test.
 *
 * @param string $description
 *	The test description.
 *
 * @param bool $success
 *	A flag indicating wether or not the test was successful.
 *
 * @param array $entries
 *	The test code entries, indexed by label.
 */
function lumina_test_report($description, $success, array $entries)
{
	echo 
		'<div class="lumina-test lumina-test-', (isset($success) ? ($success ? 'success' : 'failure') : 'neutral'), '">',
			'<div class="lumina-test-description">', htmlentities($description), '</div>';
	;
	
	foreach ($entries as $label => $code)
	{
		echo
			'<div class="lumina-test-entry">',
				'<div class="lumina-test-entry-label">', htmlentities($label), '</div>',
				'<div class="lumina-test-entry-code">', htmlentities($code), '</div>',
			'</div>';
	}
	
	echo '</div>';
}

/**
 * Stringifies a value by adding it's type prefix and it's own string
 * representation.
 *
 * @param string $value
 *	The value to stringify.
 *
 * @return string
 *	The value.
 */
function lumina_test_stringify($value)
{
	if ($value === null)
	{
		return 'null';
	}
	
	else if (is_bool($value))
	{
		return 'bool: ' . ($value ? 'true' : 'false');
	}
	
	else if (is_int($value))
	{
		return 'int: ' . $value;
	}
	
	else if (is_float($value))
	{
		return 'float: ' . $value;
	}
	
	else
	{
		return 'string: ' . $value;
	}
}

/**
 * Compares the expected and the output values to make sure they are
 * equal (==) and reports the result.
 *
 * @param string $description
 *	The test description.
 *
 * @param string $expected
 *	The expected output.
 *
 * @param string $output
 *	The actual output.
 */
function lumina_test_equal($description, $expected, $output)
{
	lumina_test_report($description, $expected == $output, array(
		'Expected' => lumina_test_stringify($expected),
		'Output' => lumina_test_stringify($output)
	));
}

/**
 * Compares the expected and the output values to make sure they are
 * identical (===) and reports the result.
 *
 * @param string $description
 *	The test description.
 *
 * @param string $expected
 *	The expected output.
 *
 * @param string $output
 *	The actual output.
 */
function lumina_test_identical($description, $expected, $output)
{
	lumina_test_report($description, $expected === $output, array(
		'Expected' => lumina_test_stringify($expected),
		'Output' => lumina_test_stringify($output)
	));
}

/**
 * Compares an object to make sure if it's of the specified class or a class
 * that inherits from it.
 *
 * @param string $description
 *	The test description.
 *
 * @param string $class
 *	The expected object class.
 *
 * @param object $object
 *	The object to test the class of
 */
function lumina_test_class($description, $class, $object)
{
	$success = is_a($object, $class);
	
	lumina_test_report($description, $success, array(
		'Expected Class' => $class,
		'Final Class' => get_class($object),
		'Is Expected Class' => lumina_test_stringify($success)
	));
}

/**
 * Compares a value to make sure it is set.
 *
 * @param string $description
 *	The test description.
 *
 * @param string $value
 *	The value to compare.
 */
function lumina_test_set($description, $value)
{
	lumina_test_report($description, isset($value), array(
		'Value' => lumina_test_stringify($value)
	));
}


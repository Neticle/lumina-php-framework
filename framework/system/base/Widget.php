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

namespace system\base;

use \system\core\Element;
use \system\core\Lumina;
use \system\core\exception\RuntimeException;

/**
 * Widgets are intended to dinamically generate output and speed up
 * a project development time by removing the need to implement the same
 * feature over and over again.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.base
 * @since 0.2.0
 */
abstract class Widget extends Element
{
	/**
	 * An associative array of currently registered widget classes, indexed
	 * by name.
	 *
	 * @type array
	 */
	private static $widgetClasses = array(
	
		'web.grid' => 'system\\ext\\web\\widget\\grid\\GridWidget',
		'web.paginator' => 'system\\ext\\web\\widget\\PaginatorWidget',
		'web.document' => 'system\\ext\\web\\widget\\DocumentWidget',
		'web.navigation.button' => 'system\\ext\\web\\widget\\navigation\\ButtonWidget'
	);
	
	/**
	 * Creates a new widget instance.
	 *
	 * This method provides a convenient way to quickly replace the behavior
	 * of any widget by defining a new class for its name.
	 *
	 * @param string $name
	 *	The name of the widget to create the instance of.
	 *
	 * @param mixed $...
	 *	The widget constructor arguments.
	 */
	public static function create($name)
	{
		if (!isset(self::$widgetClasses[$name]))
		{
			throw new RuntimeException('Widget "' . $name . '" is not defined.');
		}
		
		$class = self::$widgetClasses[$name];
		$reflection = new \ReflectionClass($class);
		
		if (!$reflection->isSubclassOf(__CLASS__))
		{
			throw new RuntimeException('Widget "' . $name . '" (class "' . $class . '") does not map to a valid class.');
		}
		
		return $reflection->newInstanceArgs(array_slice(func_get_args(), 1));
	}
	
	/**
	 * Defines the class for a specific widget, by name.
	 *
	 * @param string $name
	 *	The name of the widget to define the class for.
	 *
	 * @param string $class
	 *	The absolute name of the class to define the widget with.
	 */
	public static function setWidgetClass($name, $class)
	{
		self::$widgetClasses[$name] = $class;
	}
	
	/**
	 * Defines the class for a specific set of widgets, by name..
	 *
	 * @param string $classes
	 *	The absolute name of the classes to define the widgets with,
	 *	indexed by name.
	 */
	public static function setWidgetClasses(array $classes, $merge = true)
	{
		self::$widgetClasses = $merge ?
			array_replace(self::$widgetClasses, $classes) : $classes;
	}

	/**
	 * Constructor.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct(array $configuration = null)
	{
		parent::__construct($configuration);
	}
	
	/**
	 * Renders a context-less application view.
	 *
	 * @param string $view
	 *	An absolute alias resolving to the view being rendered.
	 *
	 * @param array $variables
	 *	The variables to be extracted into the script context.
	 *
	 * @param bool $capture
	 *	When set to TRUE the rendered contents will be captured instead
	 *	of sent to the currently active output buffer.
	 *
	 * @return string
	 *	The captured contents, when applicable.
	 */
	protected function render($view, array $variables = null, $capture = false)
	{
		$file = Lumina::getAliasPath($view, 'php', null);
		return View::getApplicationFileView($file, $variables)->run($capture);
	}
	
	/**
	 * Packs and renders this instance, optionally capturing any
	 * generated contents.
	 *
	 * @param bool $capture
	 *	When set to TRUE the rendered contents will be returned
	 *	instead of sent to the currently active output buffer.
	 *
	 * @return string
	 *	The rendered contents, if applicable.
	 */
	public function deploy($capture = false)
	{
		
	}
}


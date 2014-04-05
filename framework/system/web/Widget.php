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

namespace system\web;

use \system\core\Element;
use \system\core\exception\RuntimeException;

/**
 * A web oriented widget is intended to dynamically build instances of
 * HTML elements that can than be rendered throughout the view.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web
 * @since 0.2.0
 */
abstract class Widget extends Element
{
	/**
	 * The next auto incrementable widget id.
	 *
	 * @type int
	 */
	private static $nextWidgetId = 1;

	/**
	 * The unique widget string identifier.
	 *
	 * @type string
	 */
	private $id;

	/**
	 * An associative array of currently registered widget classes, indexed
	 * by name.
	 *
	 * @type array
	 */
	private static $widgetClasses = array(
		'document' => 'system\\web\\widget\\DocumentWidget',
		'data.grid' => 'system\\web\\widget\\data\\grid\\GridWidget',
		'data.paginator' => 'system\\web\\widget\\data\\PaginatorWidget',
		'navigation.breadcrumb' => 'system\\web\\widget\\navigation\\BreadcrumbWidget',
		'navigation.button' => 'system\\web\\widget\\navigation\\ButtonWidget',
		'navigation.dropDownButton' => 'system\\web\\widget\\navigation\\DropDownButtonWidget'
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
	 * Defines the widget unique identifier.
	 *
	 * @param string $id
	 *	The widget unique identifier.
	 */
	public function setId($id)
	{		
		$this->id = $id;
	}

	/**
	 * Returns the unique widget identifier, optionally generating one
	 * based on an auto incrementable field.
	 *
	 * @param bool $generate
	 *	When set to TRUE the ID will be generated before it is returned for use
	 *	based on an incrementable field.
	 *
	 * @return string
	 *	Returns the unique widget identifier.
	 */
	public function getId($generate = true)
	{
		if ($generate && !isset($this->id))
		{
			$this->id = 'lw' . self::$nextWidgetId++;
		}

		return $this->id;
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
	 * Builds the widget HTML element and returns it.
	 *
	 * @return HtmlElement
	 *	The packed HTML element instance.
	 */
	protected abstract function build();
	
	/**
	 * Builds the widget HTML element and returns it.
	 *
	 * @return HtmlElement
	 *	The packed HTML element instance.
	 */
	public final function pack()
	{
		$element = $this->build();
		$element->setAttribute('id', $this->getId(true));
		return $element;
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
	public final function deploy($capture = false)
	{
		return $this->pack()->render($capture);
	}
}


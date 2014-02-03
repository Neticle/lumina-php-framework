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

namespace system\core;

use \system\core\LazyExtension;
use \system\core\exception\RuntimeException;

/**
 * A Context is a special kind of extension that can be used with other
 * extensions capable of having different behaviours depending on the
 * information provided by it.
 *
 * An example of where a Context extension is useful is when creating URLs
 * that are relative to a specific module or controller.
 *
 * Context's also have renderable assets linked to them. This includes, between
 * others, the application layouts and views.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
abstract class Context extends LazyExtension
{
	/**
	 * Constructor.
	 *
	 * @param string $name
	 *	The contextual extension name.
	 *
	 * @param Extension $parent
	 *	The parent extension instance, if any.
	 */
	protected function __construct($name, Context $parent = null)
	{
		parent::__construct($parent);
		$this->name = $name;
	}
	
	/**
	 * Returns the context extension name.
	 *
	 * @return string
	 *	The extension name.
	 */
	public final function getName()
	{
		return $this->name;
	}
	
	/**
	 * Returns the route resolving to this context, relative to the
	 * base extension -- which is also a Context.
	 *
	 * @return string
	 *	The context route.
	 */
	public final function getRoute()
	{
		$route = null;
	
		if (!$this->isBaseExtension())
		{
			$context = $this->getParent();
			$route = $this->name;
			
			while (!$context->isBaseExtension())
			{
				$route = $context->name . '/' . $route;
				$context = $context->getParent();
			}
		}
		
		return $route;
	}
	
	/**
	 * This method is invoked right before a view is rendered.
	 *
	 * This method encapsulates the "render" event, which can not be canceled.
	 *
	 * @param View $view
	 *	The View instance.
	 *
	 * @return bool
	 *	Returns TRUE.
	 */
	protected function onRender($view)
	{
		$this->raiseArray('render', array($view));
		return true;
	}
	
	/**
	 * This method is invoked right before a layout is rendered.
	 *
	 * This method encapsulates the "display" event, which can not be canceled.
	 *
	 * @param View $view
	 *	The View instance.
	 *
	 * @return bool
	 *	Returns TRUE.
	 */
	protected function onDisplay($layout)
	{
		$this->raiseArray('display', array($layout));
		return true;
	}
	
	/**
	 * Returns the context layouts path.
	 *
	 * @param bool $recursive
	 *	When set to TRUE the layout path will be retrieved recursively.
	 *
	 * @return string
	 *	The context layouts path.
	 */
	public abstract function getLayoutsPath();
	
	/**
	 * Returns the absolute path to the layout script.
	 *
	 * @return string
	 *	The absolute path to the layout script.
	 */
	public abstract function getLayoutPath();
	
	/**
	 * Returns the context views path.
	 *
	 * @return string
	 *	The context views path.
	 */
	public abstract function getViewsPath();
	
	/**
	 * Renders a child view within the layout.
	 *
	 * @param string $view
	 *	The name of the partial view to render.
	 *
	 * @param array $variables
	 *	An associative array holding the variables to be extracted
	 *	in the view context.
	 *
	 * @param bool $capture
	 *	When set to TRUE the contents will be captured and returned
	 *	instead of sent to the output buffer.
	 *
	 * @return string
	 *	The captured contents, if applicable.
	 */
	public final function display($view, array $variables = null, $capture = false)
	{
		$layout = $this->getLayoutPath();
		
		if (isset($layout))
		{
			$layout = new View($this, $layout);
			$this->onDisplay($layout);
			
			$layout->setVariables(array(
				'viewContents' => $this->render($view, $variables, true)
			));
			
			return $layout->run($capture);
		}
		
		throw new RuntimeException('Application layout is not defined.');
	}
	
	/**
	 * Renders a child view.
	 *
	 * @param string $view
	 *	The name of the partial view to render.
	 *
	 * @param array $variables
	 *	An associative array holding the variables to be extracted
	 *	in the view context.
	 *
	 * @param bool $capture
	 *	When set to TRUE the contents will be captured and returned
	 *	instead of sent to the output buffer.
	 *
	 * @return string
	 *	The captured contents, if applicable.
	 */
	public final function render($view, array $variables = null, $capture = false)
	{
		$view = Lumina::getAliasPath($view, 'view.php', $this->getViewsPath());
		$view = new View($this, $view);
		$this->onRender($view);
		
		if (isset($variables))
		{
			$view->setVariables($variables, false);
		}
		
		return $view->run($capture);
	}
}


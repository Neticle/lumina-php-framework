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

use \system\core\View;
use \system\core\Context;
use \system\core\Lumina;

/**
 * A view can be used to dinamically generate content based on a set of
 * variables that are made available to it.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
class ContextView extends View
{	
	/**
	 * Returns the instance of a view linked with the given context.
	 *
	 * @param Context $context
	 *	The context to link the view to.
	 *
	 * @param string $view
	 *	An alias resolving to the view script file, relative to the
	 *	context views path.
	 *
	 * @return View
	 *	Returns the view instance.
	 */
	public static function getContextView(Context $context, $view)
	{
		$file = Lumina::getAliasPath($view, 'php', $context->getViewsPath());
		return new ContextView($context, $file);
	}
	
	/**
	 * Returns the instance of a view linked with the given context.
	 *
	 * @param Context $context
	 *	The context to link the view to.
	 *
	 * @param string $filePath
	 *	The absolute path to the file wrapped by the view.
	 *
	 * @return View
	 *	Returns the view instance.
	 */
	public static function getContextFileView(Context $context, $filePath)
	{
		return new ContextView($context, $filePath);
	}
	
	/**
	 * Constructor.
	 *
	 * @param Context $parent
	 *	The context this view belongs to.
	 *
	 * @param string $filePath
	 *	The absolute path to the file wrapped by this view.
	 */
	protected function __construct(Context $parent, $filePath)
	{
		parent::__construct($parent, $filePath);
	}
	
	/**
	 * Returns the view context.
	 *
	 * This function is an alias of "Extension::getParent()".
	 *
	 * @return Context
	 *	The parent Context instance.
	 */
	public final function getContext()
	{
		return $this->getParent();
	}
	
	/**
	 * Renders a child view.
	 *
	 * @param string $view
	 *	The name of the view to render.
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
	public function render($view, array $variables = null, $capture = false)
	{
		$nested = new ContextView($this->getParent(), Lumina::getAliasPath($view, 'php', $this->basePath));
		
		if (isset($variables))
		{
			$nested->setVariables($variables);
		}
		
		return $nested->run($capture);
	}
}


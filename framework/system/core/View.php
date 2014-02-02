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

use \system\base\Controller;
use \system\core\Extension;

/**
 * Views are usually created by a controller in order to generate dynamic
 * output based on a set of data provided by it.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
class View extends Extension
{
	/**
	 * The view file wrapped by this instance.
	 *
	 * @type string
	 */
	private $file;
	
	/**
	 * The base path to resolve view aliases from.
	 *
	 * @type string
	 */
	private $base;
	
	/**
	 * The variables to be extracted in the script context.
	 *
	 * @type array
	 */
	private $variables = array();
	
	/**
	 * Renders a script file.
	 *
	 * Please note the 'self' variable is not defined by default, meaning that
	 * you will have to call this function again if you wish to render a
	 * child view from that context.
	 *
	 * Make sure you can not render the view file from a controller before
	 * using this function.
	 *
	 * @param string $file
	 *	The absolute path to the file being rendered.
	 *
	 * @param array $variables
	 *	The variables to be extracted into the script context.
	 *
	 * @param bool $capture
	 *	When set to TRUE the rendered contents will be captured instead
	 *	of sent to the currently active output buffer.
	 */
	public static function renderFile($__FILE__, array $__VARIABLES__ = null, $__CAPTURE__ = true)
	{
		if (isset($__VARIABLES__))
		{
			extract($__VARIABLES__);
		}
		
		if ($__CAPTURE__)
		{
			ob_start();
			require($__FILE__);
			return ob_get_clean();
		}
		
		require($__FILE__);
	}
	
	/**
	 * Returns a context view.
	 *
	 * @param Context $context
	 *	The context to get the view for.
	 *
	 * @param string $view
	 *	An alias resolving to the intended view, relative to the
	 *	context views path.
	 *
	 * @return View
	 *	Returns the view instance.
	 */
	public static function getContextView(Context $context, $view, $type = 'view')
	{
		$file = Lumina::getAliasPath($view, $type . '.php', $context->getViewsPath());
		return new View($context, $file);
	}
	
	/**
	 * Constructor.
	 *
	 * For security reasons you should NEVER use this constructor directly and
	 * instead use the static "getContextView" function.
	 *
	 * @param Context $parent
	 *	The extension this view belongs to.
	 *
	 * @param string $view
	 *	The view to be rendered.
	 *
	 * @param string $base
	 *	The base path to resolve the view from.
	 *
	 * @param string $type
	 *	The type of view to be rendered.
	 */
	public function __construct(Context $parent, $file)
	{
		parent::__construct($parent);
		$this->filePath = $file;
		$this->basePath = dirname($file);
	}
	
	/**
	 * Returns the absolute path to the view file.
	 *
	 * @return string
	 *	The absolute path to the view file.
	 */
	public function getFilePath()
	{
		return $this->filePath;
	}
	
	/**
	 * Defines the variables to be extracted in the view script context.
	 *
	 * @param array $variables
	 *	An associative array defining the variables, indexed by name.
	 *
	 * @param bool $merge
	 *	When set to TRUE the variables will be merged with the ones
	 *	previously defined.
	 */
	public function setVariables(array $variables, $merge = false)
	{
		$this->variables = $merge ?
			array_replace($this->variables, $variables) : $variables;
	}
	
	/**
	 * Renders a child partial view.
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
	public function render($view, array $variables = null, $capture = false)
	{
		$nested = new View($this->controller, $view, $this->basePath, 'partial');
		
		if (isset($variables))
		{
			$nested->setVariables($variables);
		}
		
		return $nested->run($capture);
	}
	
	/**
	 * Runs the view by extracting the variables and including the script
	 * file it is linked to.
	 *
	 * @param bool $capture
	 *	When set to TRUE the contents will be captured and returned
	 *	instead of sent to the output buffer.
	 *
	 * @return string
	 *	The captured contents, if applicable.
	 */
	public function run($capture = false)
	{
		$variables = array('self' => $this) + $this->variables;
		return self::renderFile($this->filePath, $variables, $capture);
	}
}


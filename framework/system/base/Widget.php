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
	 * The absolute path to the directory containing the widget files.
	 *
	 * @type string
	 */
	private $basePath;

	/**
	 * Constructor.
	 *
	 * @param string $basePath
	 *	The absolute path to the directory containing the widget files, or
	 *	NULL if applicable.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct($basePath, array $configuration = null)
	{
		parent::__construct($configuration);
		$this->basePath = $basePath;
	}
	
	/**
	 * Returns the widget base path, when available.
	 *
	 * @return string
	 *	The widget base path.
	 */
	public function getBasePath()
	{
		return $this->basePath;
	}
	
	/**
	 * Renders a context-less application view.
	 *
	 * @param string $view
	 *	An alias resolving to the view being rendered, relative to the
	 *	widget base path.
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
	public function render($view, array $variables = null, $capture = false)
	{
		$file = Lumina::getAliasPath($view, 'php', $this->basePath);
		return View::getApplicationFileView($file, $variables)->run($capture);
	}
}


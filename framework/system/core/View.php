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

use \system\core\Extension;
use \system\core\Lumina;

/**
 * A view can be used to dinamically generate content based on a set of
 * variables that are made available to it.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
class View extends Render
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
	private $variables;
	
	/**
	 * Returns the instance of a view linked with the given extension.
	 *
	 * @param Extension $extension
	 *	The extension to link the view to.
	 *
	 * @param string $view
	 *	An absolute alias resolving to the view script file.
	 *
	 * @param array $variables
	 *	The variables to be initially defined for this view.
	 *
	 * @return View
	 *	Returns the view instance.
	 */
	public static function getExtensionView(Extension $extension, $view, array $variables = null)
	{
		$file = Lumina::getAliasPath($view, 'php');
		return new View($extension, $file, $variables);
	}
	
	/**
	 * Returns the instance of a view linked with the given extension.
	 *
	 * @param Extension $extension
	 *	The extension to link the view to.
	 *
	 * @param string $filePath
	 *	The absolute path to the script wrapped by this view.
	 *
	 * @param array $variables
	 *	The variables to be initially defined for this view.
	 *
	 * @return View
	 *	Returns the view instance.
	 */
	public static function getExtensionFileView(Extension $extension, $filePath, array $variables = null)
	{
		return new View($extension, $filePath, $variables);
	}
	
	/**
	 * Returns the instance of a view linked to the application.
	 *
	 * @param string $view
	 *	An alias resolving to the view script file, relative to the
	 *	application path.
	 *
	 * @param array $variables
	 *	The variables to be initially defined for this view.
	 *
	 * @return View
	 *	Returns the view instance.
	 */
	public static function getApplicationView($view, array $variables = null)
	{
		$file = Lumina::getAliasPath($view, 'php', L_APPLICATION);
		return new View(Lumina::getApplication(), $file, $variables);
	}
	
	/**
	 * Returns the instance of a view linked to the application.
	 *
	 * @param string $filePath
	 *	The absolute path to the script wrapped by this view.
	 *
	 * @param array $variables
	 *	The variables to be initially defined for this view.
	 *
	 * @return View
	 *	Returns the view instance.
	 */
	public static function getApplicationFileView($filePath, array $variables = null)
	{
		return new View(Lumina::getApplication(), $filePath, $variables);
	}
	
	/**
	 * Constructor.
	 *
	 * @param Extension $parent
	 *	The extension this view belongs to.
	 *
	 * @param array $variables
	 *	The variables to be extracted into the script context.
	 *
	 * @param string $filePath
	 *	The absolute path to the file wrapped by this view.
	 */
	protected function __construct(Extension $parent, $filePath, array $variables = null)
	{
		parent::__construct($parent);
		$this->filePath = $filePath;
		$this->basePath = dirname($filePath);
		$this->variables = (array) $variables;
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
	 * Returns the view base path.
	 *
	 * @return string
	 *	The view base path.
	 */
	public function getBasePath()
	{
		return $this->basePath;
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
		$nested = new View(Lumina::getAliasPath($view, 'php', $this->basePath));
		
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
		return $this->renderFile($this->filePath, $this->variables, $capture);
	}
}


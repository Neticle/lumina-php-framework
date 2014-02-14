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

namespace vendor\bootstrap;

use \system\web\Document;
use \system\web\asset\Bundle;

/**
 * Bootstrap CSS framework bundle.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package vendor.bootstrap
 * @since 0.2.0
 */
class BootstrapBundle extends Bundle
{
	/**
	 * A flag indicating wether or not the default theme should be loaded.
	 *
	 * @type bool
	 */
	private $loadDefaultTheme = true;
	
	/**
	 * Defines wether or not the default theme should be linked to the 
	 * current document when the bundle is applied.
	 *
	 * You should set this to FALSE if you have your own theme and link it
	 * to the current document.
	 *
	 * @param bool $loadDefaultTheme
	 *	A flag indicating wether or not the default theme should be loaded.
	 */
	public function setLoadDefaultTheme($loadDefaultTheme)
	{
		$this->loadDefaultTheme = $loadDefaultTheme;
	}

	/**
	 * Applies the asset bundle to the document.
	 *
	 * @param Document $document
	 *	The instance of the document to apply the bundle to.
	 */
	public function apply(Document $document)
	{
		$base = $this->getComponent('assetManager')->
			publishPath(dirname(__FILE__) . '/assets');
		
		// Bootstrap requires jquery
		$document->requireBundle('jquery');
		
		// Add the stylesheets
		$document->addStyle($base . 'css/bootstrap.min.css', 'bootstrap-css');
		
		if ($this->loadDefaultTheme)
		{
			$document->addStyle($base . 'css/bootstrap-theme.min.css', 'bootstrap-theme-css');
		}
		
		// Bootstrap library
		$document->addScript($base . 'js/bootstrap.min.js', 'bootstrap-js', 'footer');
	}
}


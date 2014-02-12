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

namespace vendor\jquery;

use \system\web\Document;
use \system\web\asset\Bundle;

/**
 * An asset bundle can be applied to a document, which will cause the bundle
 * assets to be added to the document if they aren't already.
 *
 * The bundle is an extension that's linked to a module through the parent
 * extensions -- usually the Document it belongs to -- thus allowing you to
 * use the module components to implement the necessary logic.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package vendor.jquery
 * @since 0.2.0
 */
class JQueryBundle extends Bundle
{

	/**
	 * The first major version of jQuery (1.x.x), compatible with older browsers
	 * like IE7 and IE8.
	 *
	 * @type string
	 */
	const VERSION_1 = '1.11.0';
	
	/**
	 * The first major version of jQuery (1.x.x), compatible with older browsers
	 * like IE7 and IE8, minified.
	 *
	 * @type string
	 */
	const VERSION_1_MIN = '1.11.0.min';
	
	/**
	 * The second major version of jQuery (2.x.x), compatible with all major
	 * browsers that support for HTML5.
	 *
	 * @type string
	 */
	const VERSION_2 = '2.1.0';
	
	/**
	 * The second major version of jQuery (2.x.x), compatible with all major
	 * browsers that support for HTML5, minified.
	 *
	 * @type string
	 */
	const VERSION_2_MIN = '2.1.0.min';
	
	/**
	 * The version to use when applying the bundle to the document.
	 *
	 * @type string
	 */
	private $version = '2.1.0.min';
	
	/**
	 * Defines the JQuery version to be applied to the document, as defined
	 * by the JQueryBundle::VERSION_* constants.
	 *
	 * @param string $version
	 *	The version to apply to the document.
	 */
	public function setVersion($version)
	{
		$this->version = $version;
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
		
		$document->addScript($base . 'jquery-' . $this->version . '.js');
	}

}


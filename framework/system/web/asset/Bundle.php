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

namespace system\web\asset;

use \system\core\Extension;
use \system\web\Document;

/**
 * An asset bundle can be applied to a document, which will cause the bundle
 * assets to be added to the document if they aren't already.
 *
 * The bundle is an extension that's linked to a module through the parent
 * extensions -- usually the Document it belongs to -- thus allowing you to
 * use the module components to implement the necessary logic.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.asset
 * @since 0.2.0
 */
abstract class Bundle extends Extension
{
	/**
	 * Constructor.
	 *
	 * @param Extension $parent
	 *	The parent extension instance.
	 *
	 * @param array $configuration
	 *	The bundle express configuration array.
	 */
	public final function __construct(Extension $parent, array $configuration = null)
	{
		parent::__construct($parent, $configuration);
	}

	/**
	 * Applies the asset bundle to the document.
	 *
	 * @param Document $document
	 *	The instance of the document to apply the bundle to.
	 */
	public abstract function apply(Document $document);
}


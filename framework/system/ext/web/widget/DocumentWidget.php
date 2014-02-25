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

namespace system\ext\web\widget;

use \system\base\Widget;
use \system\web\Document;
use \system\web\html\Html;

/**
 * The document widget will automate the creation of the document HEAD section
 * based on a document instance.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.extension.widget
 * @since 0.2.0
 */
class DocumentWidget extends Widget
{
	/**
	 * The Document instance.
	 *
	 * @type Document
	 */
	private $document;

	/**
	 * Constructor.
	 *
	 * @param Document $document
	 *	The document to be parsed by this widget.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct(Document $document, array $configuration = null)
	{
		parent::__construct(null, $configuration);
		$this->document = $document;
	}
	
	/**
	 * Deploys the document HEAD section contents.
	 *
	 * @param string $position
	 *	The identifier of the document position to deploy. The 'head' position
	 *	also includes the base URL, title and meta data.
	 */
	public function deploy($position = 'head')
	{
		$document = $this->document;
		
		if ($position === 'head')
		{
			// Base url
			echo '<base href="', Html::encode($this->getComponent('router')->getBaseUrl()), '" />';
		
			// Meta data
			foreach ($document->getMeta() as $meta)
			{
				echo '<meta ', Html::encode($meta[0]), '="', Html::encode($meta[1]), '" content="',
					Html::encode($meta[2]), '" />';
			}
			
			// Document title
			echo '<title>', Html::encode($document->getTitle()), '</title>';
		}
		
		// Styles
		foreach ($document->getStyles() as $id => $style)
		{
			if ($style[1] === $position)
			{
				echo '<link id="', Html::encode($id), '" rel="stylesheet" type="text/css" ',
					'href="', Html::encode($style[0]), '" />';
			}
		}
		
		// Inline styles
		foreach ($document->getInlineStyles() as $id => $style)
		{
			if ($style[1] === $position)
			{
				echo '<style id="', Html::encode($id), '" type="text/css">',
					$style[0], '</style>';
			}
		}
		
		// Scripts
		foreach ($document->getScripts() as $id => $script)
		{
			if ($script[1] === $position)
			{
				echo '<script type="text/javascript" id="', Html::encode($id), '" src="',
					Html::encode($script[0]), '"></script>';
			}
		}
		
		// Inline scripts
		foreach ($document->getInlineScripts() as $id => $script)
		{
			if ($script[1] === $position)
			{
				echo '<script type="text/javascript" id="', Html::encode($id), '">',
					$script[0], '</script>';
			}
		}
	}
}


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

namespace system\web\widget\navigation;

use \system\base\Widget;
use \system\web\widget\navigation\ButtonWidget;
use \system\web\html\Html;
use \system\web\html\HtmlElement;

/**
 * A drop down button will present one button with several levels of context
 * menus contained in it.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class DropDownButtonWidget extends ButtonWidget
{
	/**
	 * The description of the drop down button structure.
	 *
	 * @type array
	 */
	private $items;
	
	/**
	 * The explicitly defined image width.
	 *
	 * @type int
	 */
	private $itemImageWidth;
	
	/**
	 * The explicitly defined image height.
	 *
	 * @type int
	 */
	private $itemImageHeight;
	
	/**
	 * Defines the drop down structure as a multidimensional associative
	 * array.
	 *
	 * @param array $items
	 *	An associative array defining the drop down structure, as described
	 *	in the example bellow:
	 *
	 *		$items = array(
	 *			array(
	 *				'image' => string // url to the button image
	 *				'url' => string|array // url or route array
	 *				'label' => string // item label
	 *				'items' => array // sub items description
	 *			)
	 *			, ...
	 *		);
	 */
	public function setItems(array $items)
	{
		$this->items = $items;
	}
	
	/**
	 * Builds the list item label element.
	 *
	 * @param array $item
	 *	The description of the item to build the label for.
	 *
	 * @param string $label
	 *	The label text contents.
	 *
	 * @return HtmlElement
	 *	The generated html element.
	 */
	protected function buildListItemLabel($item, $label)
	{
		$span = new HtmlElement('span');
		$span->setClass(array('lw-button-dropdown-item-label'));
		$span->setTextContent($label);
		return $span;
	}
	
	/**
	 * Builds the list item image.
	 *
	 * @param array $item
	 *	The description of the item to build the image for.
	 *
	 * @param string $image
	 *	The URL linking to the image.
	 *
	 * @param int $width
	 *	The explicitly defined item image width, if applicable.
	 *
	 * @param int $height
	 *	The explicitly defined item image height, if applicable.
	 *
	 * @return HtmlElement
	 *	The generated html element.
	 */
	protected function buildListItemImage($item, $image, $width, $height)
	{
		$img = new HtmlElement('img');
		$img->setClass(array('lw-button-dropdown-item-image'));
		$img->setAttribute('src', $image);
		
		if (isset($width))
		{
			$img->setAttribute('width', $width);
		}
		
		if (isset($height))
		{
			$img->setAttribute('height', $height);
		}
		
		return $img;
	}
	
	/**
	 * Builds a list item button.
	 *
	 * @param array $item
	 *	The description of the item to build the button for.
	 *
	 * @param int $level
	 *	The depth level of the current item.
	 *
	 * @return HtmlElement
	 *	The generated html element.
	 */
	protected function buildListItemButton($item, $level)
	{
		$a = new HtmlElement('a');
		$a->setClass(array('lw-button-dropdown-item-anchor'));
		
		if (isset($item['image']))
		{
			$a->addContent($this->buildListItemImage($item, $item['image'], $this->itemImageWidth, $this->itemImageHeight));
		}
		
		if (isset($item['label']))
		{
			$a->addContent($this->buildListItemLabel($item, $item['label']));
		}
		
		if (isset($item['url']))
		{
			// The anchor URL
			$url = $item['url'];
			
			if (is_array($url))
			{
				$url = $this->getComponent('router')
					->createUrl($item['url'][0], array_slice($item['url'], 1));
			}
				
			$a->setAttribute('href', $url);
			
			// Target
			if (isset($item['target']))
			{
				$a->setAttribute('target', $item['target']);
			}
		}
		
		return $a;
	}
	
	/**
	 * Builds a list item.
	 *
	 * @param array $item
	 *	The description of the item to build the item for.
	 *
	 * @param int $level
	 *	The depth level of the current item.
	 *
	 * @return HtmlElement
	 *	The generated html element.
	 */
	protected function buildListItem($item, $level)
	{		
		$li = new HtmlElement('li');
		$li->setClass(array('lw-button-dropdown-item', 'lw-button-dropdown-item-depth-' . $level));
		$li->setAttribute('data-list-depth', $level);
		$li->setContent($this->buildListItemButton($item, $level));
		
		if (isset($item['items']))
		{
			$li->addContent($this->buildList($item['items'], ($level + 1)));
		}
		
		return $li;
	}
	
	/**
	 * Builds a list item button.
	 *
	 * @param array $item
	 *	The description of the item to build the button for.
	 *
	 * @param int $level
	 *	The depth level of the current item.
	 *
	 * @return HtmlElement
	 *	The generated html element.
	 */
	protected function buildList(array $items, $level = 0)
	{
		$ul = new HtmlElement('ul');
		$ul->setClass(array('lw-button-dropdown-list', 'lw-button-dropdown-list-depth-' . $level));
		$ul->setAttribute('data-list-depth', $level);
	
		$content = array();
	
		foreach ($items as $item)
		{
			$content[] = $this->buildListItem($item, $level);
		}
		
		$ul->setContent($content);
		return $ul;		
	}
	
	protected function build()
	{
		if (isset($this->items))
		{
			$div = new HtmlElement('div');
			$div->setClass(array('lw-dropdownbutton'));
			$div->addContent(parent::build());
			$div->addContent($this->buildList($this->items, 0));
			return $div;
		}
		
		return parent::build();
	}
	
}


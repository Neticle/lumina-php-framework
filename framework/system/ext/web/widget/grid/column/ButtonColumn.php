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

namespace system\ext\web\widget\grid\column;

use \system\core\Element;
use \system\data\provider\Provider;
use \system\ext\web\widget\grid\GridWidget;
use \system\web\html\HtmlElement;

/**
 * Based on .NET GridView control, this widget provides a grid with pre-defined
 * columns, pagination and data sorting based on the data from a given provider.
 *
 * The button column will present one or more buttons within a cell. When
 * multiple buttons are to be displayed, they will be collapsed into a single
 * drop down menu.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.extension.widget.grid.column
 * @since 0.2.0
 */
class ButtonColumn extends Column
{
	/**
	 * An array of buttons to display, indexed by name.
	 *
	 * @type array
	 */
	private $buttons;
	
	/**
	 * Defines the buttons to be displayed within cells of this column.
	 *
	 * @param array $buttons
	 *	An array of buttons indexed by name, where each button is represented 
	 *	as an associative array containing the following indexes:
	 *
	 *		'image' => string, default null
	 *
	 *			The URL linking to the image to be displayed as an icon
	 *			for this button.
	 *
	 *		'label' => string, defaults to the button name
	 *
	 *			The text to be displayed as a label for this button.
	 *
	 *		'url' => callable(Provider $provider, $item) | string
	 *
	 *			A valid callable reference that will return the URL to where
	 *			the user should be redirected to when clicked. As an
	 *			alternative, a string can be passed in order to generate
	 *			the same URL through PHP eval.
	 *
	 *			If NULL is given the button will be considered disabled.
	 *
	 *		'options' => array
	 *			
	 *			An associative array defining additional HTML options.
	 */
	public function setButtons(array $buttons)
	{
		$this->buttons = $buttons;
	}
	
	protected function buildButtonLabel($name, array $button)
	{
	
	}
	
	protected function buildButton($name, array $button)
	{
		$a = new HtmlElement('a');
		$a->setClass(array('lw-grid-item-cell-button', 'lw-grid-item-cell-button-' . $name));
			
		
	}

	/**
	 * Builds the content for a specific item cell.
	 *
	 * @param Provider $provider
	 *	The provider the item was retrieved from.
	 *
	 * @param mixed $item
	 *	The item build the cell for.
	 *
	 * @return HtmlElement
	 *	The resulting HTML element instance or NULL for an empty cell.
	 */
	protected abstract function buildCellContent(Provider $provider, $item)
	{
		if (isset($this->buttons))
		{
			
		}
		
		return null;
	}

}


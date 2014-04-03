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

use \system\core\exception\RuntimeException;
use \system\data\provider\Provider;
use \system\ext\web\widget\grid\column\Column;
use \system\web\html\Html;

/**
 * Based on .NET GridView control, this widget provides a grid with pre-defined
 * columns, pagination and data sorting based on the data from a given provider.
 *
 * A custom column allows the developer to generate the entire cell contents
 * through a callback that is invoked for each one of the processed item
 * cells.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.extension.widget.grid
 * @since 0.2.0
 */
class CustomColumn extends Column
{
	/**
	 * The callback to invoke when building the cell content.
	 *
	 * @type callback(Column $source, Provider $provider, $item)
	 */
	private $callback;
	
	/**
	 * Defines the callback to invoke when building the cell content.
	 *
	 * @param callback $callback
	 *	The callback to invoke when building the cell content.
	 */
	public function setCallback(callable $callback)
	{
		$this->callback = $callback;
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
	public function buildCellContent(Provider $provider, $item)
	{
		if (isset($this->callback) && is_callable($this->callback))
		{
			return call_user_func($this->callback, $this, $provider, $item);
		}
		
		return null;
	}
}


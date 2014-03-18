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
use \system\core\exception\RuntimeException;
use \system\data\provider\Provider;
use \system\ext\web\widget\grid\GridWidget;
use \system\ext\web\widget\grid\column\Column;
use \system\web\html\Html;
use \system\web\html\HtmlElement;

/**
 * Based on .NET GridView control, this widget provides a grid with pre-defined
 * columns, pagination and data sorting based on the data from a given provider.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.extension.widget.grid
 * @since 0.2.0
 */
class TextColumn extends Column
{
	/**
	 * Constructor.
	 *
	 * @param GridWidget $parent
	 *	The parent widget instance.
	 *
	 * @param string $name
	 *	The column name, if applicable.
	 *
	 * @param array $configuration
	 *	Express configuration array.
	 */
	public function __construct(GridWidget $parent, $name, array $configuration = null)
	{
		parent::__construct($parent, $name, $configuration);
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
		$name = $this->getName();
		
		if (!isset($name))
		{
			throw new RuntimeException('Unable to build unnamed text column cell.');
		}
		
		$value = $provider->getItemFieldValue($item, $name);
		
		if (isset($value))
		{
			return Html::encode($value);
		}
		
		return null;
	}
}


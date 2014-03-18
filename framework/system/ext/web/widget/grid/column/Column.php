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
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.extension.widget.grid
 * @since 0.2.0
 */
abstract class Column extends Element
{
	/**
	 * The column classes indexed by type.
	 *
	 * @type array
	 */
	private static $columnTypeClasses = array(
		'text' => 'system\\ext\\web\\widget\\grid\\column\\TextColumn'
	);

	/**
	 * The parent grid widget instance.
	 *
	 * @type GridWidget
	 */
	private $gridWidget;
	
	/**
	 * The column name, if available.
	 *
	 * @type string
	 */
	private $name;
	
	/**
	 * The column label.
	 *
	 * @type string
	 */
	private $label;
	
	/**
	 * The text to be displayed on otherwise empty cells.
	 *
	 * @type string
	 */
	private $emptyCellText;
	
	/**
	 * Constructs and returns a new column instance based on the given
	 * column construction array.
	 *
	 * @param GridWidget $gridWidget
	 *	The grid widget the column is to belong to.
	 *
	 * @param array $column
	 *	The column construction and configuration array.
	 */
	public static function fromColumnConstructionArray(GridWidget $gridWidget, array $column)
	{
		if (isset($column['type']))
		{
			$type = $column['type'];
			unset($column['type']);
			
			$class = isset(self::$columnTypeClasses[$type]) ?
				self::$columnTypeClasses[$type] : $type;
		}
		else
		{
			$class = self::$columnTypeClasses['text'];
		}
		
		if (isset($column['name']))
		{
			$name = $column['name'];
			unset($column['name']);
		}
		else
		{
			$name = null;
		}
		
		return new $class($gridWidget, $name, $column);
	}

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
		parent::__construct(null);
		$this->gridWidget = $parent;
		$this->name = $name;
		
		if (isset($configuration))
		{
			$this->configure($configuration);
		}
	}
	
	/**
	 * Returns the parent grid widget instance.
	 *
	 * @return GridWidget
	 *	The parent widget instance.
	 */
	public function getGridWidget()
	{
		return $this->gridWidget;
	}
	
	/**
	 * Returns the column name, if applicable.
	 *
	 * @return string
	 *	The column name, or NULL.
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Defines the column label.
	 *
	 * @param string $label
	 *	The column label text.
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}
	
	/**
	 * Returns the column label.
	 *
	 * @return string
	 *	The column label.
	 */
	public function getLabel()
	{
		if (!isset($this->label) && isset($this->name))
		{
			$this->label = str_replace(array('-', '_', '.'), ' ', $this->name);
		}
		
		return $this->label;
	}
	
	/**
	 * Defines the text to be displayed on cells that would be
	 * otherwise empty.
	 *
	 * @param string $text
	 *	The text to display on empty cells.
	 */
	public function setEmptyCellText($text)
	{
		$this->emptyCellText = $text;
	}
	
	/**
	 * Returns the text to be displayed on cells that would be
	 * otherwise empty.
	 *
	 * @param string $text
	 *	The text to display on empty cells.
	 */
	public function getEmptyCellText()
	{
		return $this->emptyCellText;
	}
	
	/**
	 * Builds the cell for a specific item value.
	 *
	 * @param Provider $provider
	 *	The provider the item was retrieved from.
	 *
	 * @param mixed $item
	 *	The item build the cell for.
	 *
	 * @return HtmlElement
	 *	The resulting HTML element instance.
	 */
	public function buildCell(Provider $provider, $item)
	{	
		$content = $this->buildCellContent($provider, $item);
		
		if (!isset($content) && isset($this->emptyCellText))
		{
			$content = $this->buildEmptyCellContent($provider, $item, $this->emptyCellText);
		}	
	
		$td = new HtmlElement('td');
		$td->setClass(array('lw-grid-item-cell'), false);
		$td->setContent($content);
		return $td;
	}
	
	/**
	 * Builds the content to be displayed on otherwise empty cells.
	 *
	 * @param Provider $provider
	 *	The provider the item was retrieved from.
	 *
	 * @param mixed $item
	 *	The item build the cell for.
	 *
	 * @param string $message
	 *	The message to be displayed.
	 *
	 * @return HtmlElement
	 *	The resulting HTML element instance.
	 */
	protected function buildEmptyCellContent(Provider $provider, $item, $message)
	{
		$span = new HtmlElement('span');
		$span->setClass(array('lw-grid-item-cell-empty-message'));
		$span->setTextContent($message);
		return $span;
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
	protected abstract function buildCellContent(Provider $provider, $item);
}


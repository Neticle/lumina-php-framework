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

namespace system\ext\web\widget\grid;

use \system\base\Widget;
use \system\data\provider\Provider;
use \system\ext\web\widget\grid\column\Column;
use \system\ext\web\widget\grid\column\TextColumn;
use \system\web\html\HtmlElement;

/**
 * Based on .NET GridView control, this widget provides a grid with pre-defined
 * columns, pagination and data sorting based on the data from a given provider.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.extension.widget.grid
 * @since 0.2.0
 */
class GridWidget extends Widget
{
	/**
	 * The underlying provider instance.
	 *
	 * @type Provider
	 */
	private $provider;
	
	/**
	 * An array of grid columns.
	 *
	 * @type Column[]
	 */
	private $columns = array();

	/**
	 * Constructor.
	 *
	 * @param Provider $provider
	 *	The provider to retrieve the data from.
	 *
	 * @param array $configuration
	 *	Express configuration array.
	 */
	public function __construct(Provider $provider, array $configuration = null)
	{
		parent::__construct(null);
		$this->provider = $provider;
		
		if (isset($configuration))
		{
			$this->configure($configuration);
		}
	}
	
	/**
	 * Defines the columns to be displayed by this grid widget.
	 *
	 * @param array|string $columns
	 *	The columns to be displayed by this grid widget, either as an array
	 *	of Column objects, names or a CSV string.
	 */
	public function setColumns($columns)
	{
		if (is_string($columns))
		{
			$columns = preg_split('/(\s*\,\s*)/', $columns, -1, PREG_SPLIT_NO_EMPTY);
		}
		
		foreach ($columns as $column)
		{
			if (!($column instanceof Column))
			{
				if (is_array($column))
				{
					$column = Column::fromColumnConstructionArray($this, $column);
				}
				else
				{
					$column = new TextColumn($this, $column, null);
				}
			}
			
			$this->columns[] = $column;
		}
	}
	
	/**
	 * Returns the defined columns.
	 *
	 * @return Column[]
	 *	The defined grid columns.
	 */
	public function getColumns()
	{
		return $this->columns;
	}
	
	/**
	 * Builds the entire header section cells for this grid widget.
	 *
	 * @return HtmlElement[]
	 *	The header cells (most likely "th" elements).
	 */
	public function buildHeaderCells()
	{
		$cells = array();
	
		foreach ((array) $this->columns as $index => $column)
		{
			$th = new HtmlElement('th');
			$th->setClass('ui-gridwidget-header-cell');
			$th->set('data-index', $index);
			
			$label = $column->getLabel();
			
			if (isset($label))
			{
				$th->setTextContent($label);
			}
			
			$cells[] = $th;
		}
		
		return $cells;
	}
	
	/**
	 * Builds the entire header section of the grid widget.
	 *
	 * @return HtmlElement
	 *	The entire header section of this widget.
	 */
	public function buildHeader()
	{
		$tr = new HtmlElement('tr');
		$tr->setClass(array('ui-gridwidget-header'));
		$tr->setContent($this->buildHeaderCells());
		
		$thead = new HtmlElement('thead');
		$thead->setContent($tr);
		return $thead;
	}
	
	/**
	 * Builds all rows for the body section of this grid widget.
	 *
	 * @return HtmlElement[]
	 *	The entire body row collection, most like "tr" elements.
	 */
	public function buildBodyRows()
	{
		$rows = array();
		$provider = $this->provider;
		$columns = $this->columns;
		$i = 0;
		
		foreach ($provider->getItems() as $item)
		{			
			$cells = array();
			foreach ($columns as $index => $column)
			{
				$cells[] = $column->buildCell($provider, $item);
			}
			
			$tr = new HtmlElement('tr');
			$tr->setClass(array('ui-gridwidget-item', ((++$i % 2 === 0) ? 'odd' : 'even')));
			$tr->setContent($cells);
			
			$rows[] = $tr;
		}
		
		return $rows;
	}
	
	/**
	 * Builds the entire body section of this grid widget.
	 *
	 * @return HtmlElement
	 *	The entire body section of this widget.
	 */
	public function buildBody()
	{
		$tbody = new HtmlElement('tbody');
		$tbody->setClass('ui-gridwidget-body');
		$tbody->setContent($this->buildBodyRows());
		return $tbody;
	}
	
	/**
	 * Builds the entire table container section of this grid widget.
	 *
	 * @return HtmlElement
	 *	The entire table container section of this widget.
	 */
	public function buildTable()
	{
		$table = new HtmlElement('table');
		$table->setClass('ui-gridwidget-table');
		
		$table->setContent(array(
			$this->buildHeader(),
			$this->buildBody()
		));
		
		return $table;
	}
	
	/**
	 * Builds and deploys the widget HTML elements.
	 */
	public function deploy()
	{
		$div = new HtmlElement('div');
		$div->setContent($this->buildTable());
		$div->setClass('ui-gridwidget ui-gridwidget-container');
		$div->set('id', $this->getId(true));
		$div->render();
	}
}


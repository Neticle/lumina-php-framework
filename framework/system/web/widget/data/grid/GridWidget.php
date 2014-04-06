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

namespace system\web\widget\data\grid;

use \system\data\provider\Provider;
use \system\data\provider\paginator\Paginator;
use \system\data\provider\sorter\Sorter;
use \system\web\widget\data\PaginatorWidget;
use \system\web\widget\data\grid\column\Column;
use \system\web\widget\data\grid\column\TextColumn;
use \system\web\Widget;
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
	 * The grid widget paginator query string key.
	 *
	 * @type string
	 */
	private $paginatorKey;
	
	/**
	 * The grid widget sorter query string key.
	 *
	 * @type string
	 */
	private $sorterKey;

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
	 * Returns the underlying data provider instance.
	 *
	 * @return Provider
	 *	The data provider instance.
	 */
	public function getProvider()
	{
		return $this->provider;
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
	 * Defines the paginator query string key.
	 *
	 * @param string $key
	 *	The paginator query string key.
	 */
	public function setPaginatorKey($key)
	{
		$this->paginatorKey = $key;
	}
	
	/**
	 * Returns the paginator query string key, creating it if one isn't
	 * defined already.
	 *
	 * @return string
	 *	The paginator query string key.
	 */
	public function getPaginatorKey()
	{
		if (!isset($this->paginatorKey))
		{
			$this->paginatorKey = $this->getId() . '_page';
		}
		
		return $this->paginatorKey;
	}
	
	/**
	 * Defines the sorter query string key.
	 *
	 * @param string $key
	 *	The sorter query string key.
	 */
	public function setSorterKey($key)
	{
		$this->sorterKey = $key;
	}
	
	/**
	 * Returns the sorter query string key, creating it if one isn't
	 * defined already.
	 *
	 * @return string
	 *	The sorter query string key.
	 */
	public function getSorterKey()
	{
		if (!isset($this->sorterKey))
		{
			$this->sorterKey = $this->getId() . '_sort';
		}
		
		return $this->sorterKey;
	}
	
	/**
	 * Builds the sorting url rule based on a given field and intended
	 * direction.
	 *
	 * @param string $field
	 *	The field to build the url to.
	 *
	 * @param string $direction
	 *	The sorting direction ("ask", "desc") to build the url to.
	 *
	 * @return string
	 *	The absolute url.
	 */
	protected function buildSortingRuleUrl($field, $direction)
	{
		$key = $this->getSorterKey();
		
		$router = $this->getComponent('router');
		$route = $router->getRequestRoute();
		
		$route[1][$key] = array(
			$field => $direction
		);
		
		return $router->createAbsoluteUrl($route[0], $route[1]);
	}
	
	/**
	 * Builds the entire grid widget table element.
	 *
	 * @param Provider $provider
	 *	The data provider to build the table from.
	 *
	 * @param Paginator $paginator
	 *	The paginator linked with the given provider instance.
	 *
	 * @param Sorter $sorter
	 *	The sorter linked with the given provider instance.
	 *
	 * @param PaginatorWidget $paginatorWidget
	 *	The paginator widget instance.
	 *
	 * @return HtmlElement
	 *	The html element instance.
	 */
	protected function buildTable(Provider $provider, Paginator $paginator = null, Sorter $sorter = null, PaginatorWidget $paginatorWidget = null)
	{
		$table = new HtmlElement('table');
		$table->setClass(array('lw-grid-table'));
		$table->setContent(array(
			$this->buildTableHeader($provider, $paginator, $sorter, $paginatorWidget),
			$this->buildTableBody($provider, $paginator, $sorter),
			$this->buildTableFooter($provider, $paginator, $sorter, $paginatorWidget)
		));
		return $table;
	}
	
	/**
	 * Builds the entire grid widget table header element.
	 *
	 * @param Provider $provider
	 *	The data provider to build the table from.
	 *
	 * @param Paginator $paginator
	 *	The paginator linked with the given provider instance.
	 *
	 * @param Sorter $sorter
	 *	The sorter linked with the given provider instance.
	 *
	 * @param PaginatorWidget $paginatorWidget
	 *	The paginator widget instance.
	 *
	 * @return HtmlElement
	 *	The html element instance.
	 */
	protected function buildTableHeader(Provider $provider, Paginator $paginator = null, Sorter $sorter = null, PaginatorWidget $paginatorWidget = null)
	{
		$fields = isset($sorter) ? 
			$sorter->getFields() : null;
		
		$content = array();
		
		foreach ($this->columns as $column)
		{
			$content[] = $this->buildTableHeaderItem($provider, $paginator, $sorter, $column);
		}
		
		$tr = new HtmlElement('tr');
		$tr->setClass(array('lw-grid-header'));
		$tr->setContent($content);
		
		$thead = new HtmlElement('thead');
		$thead->setClass(array('lw-grid-header-container'));
		$thead->setContent($tr);
		return $thead;
	}
	
	/**
	 * Builds the entire grid widget table header item element.
	 *
	 * @param Provider $provider
	 *	The data provider to build the table from.
	 *
	 * @param Paginator $paginator
	 *	The paginator linked with the given provider instance.
	 *
	 * @param Sorter $sorter
	 *	The sorter linked with the given provider instance.
	 *
	 * @param Column $column
	 *	The column the header item is being built for.
	 *
	 * @return HtmlElement
	 *	The html element instance.
	 */
	protected function buildTableHeaderItem(Provider $provider, Paginator $paginator = null, Sorter $sorter = null, Column $column)
	{
		$field = $column->getName();
		$direction = null;
		
		if (isset($sorter) && in_array($field, $sorter->getFields()))
		{
			$key = $this->getSorterKey();
			
			$direction = (isset($_GET[$key][$field]) && $_GET[$key][$field] === 'asc') ?
				'desc' : 'asc';
		}
	
		$a = $this->buildTableHeaderItemAnchor($provider, $paginator, $sorter, $column, $field, $direction);
		
		$th = new HtmlElement('th');
		$th->setClass(array('lw-grid-header-cell'));
		$th->setContent($a);
		return $th;
	}
	
	/**
	 * Builds the entire grid widget table header item element.
	 *
	 * @param Provider $provider
	 *	The data provider to build the table from.
	 *
	 * @param Paginator $paginator
	 *	The paginator linked with the given provider instance.
	 *
	 * @param Sorter $sorter
	 *	The sorter linked with the given provider instance.
	 *
	 * @param Column $column
	 *	The column the header item is being built for.
	 *
	 * @param string $field
	 *	The field the anchor is being built for.
	 *
	 * @param string $direction
	 *	The sorting direction the anchor is being built for.
	 *
	 * @return HtmlElement
	 *	The html element instance.
	 */
	protected function buildTableHeaderItemAnchor(Provider $provider, Paginator $paginator = null, Sorter $sorter = null, Column $column, $field, $direction)
	{
		$a = new HtmlElement('a');
		$a->setClass(array('lw-grid-sort', (isset($direction) ? ('lw-grid-sort-' . $direction) : 'lw-grid-sort-disabled')));
		$a->setTextContent($column->getLabel());
		
		if (isset($direction))
		{
			$rules = $sorter->getRules();
		
			if (isset($rules) && in_array($field, array_keys($rules))) {
				$a->setClass('lw-grid-sort-active');
			}
			
			$a->setAttribute('href', $this->buildSortingRuleUrl($field, $direction));
		}
		
		return $a;
	}
	
	/**
	 * Builds the entire grid widget table footer element.
	 *
	 * @param Provider $provider
	 *	The data provider to build the table from.
	 *
	 * @param Paginator $paginator
	 *	The paginator linked with the given provider instance.
	 *
	 * @param Sorter $sorter
	 *	The sorter linked with the given provider instance.
	 *
	 * @param PaginatorWidget $paginatorWidget
	 *	The paginator widget instance.
	 *
	 * @return HtmlElement
	 *	The html element instance.
	 */
	protected function buildTableFooter(Provider $provider, Paginator $paginator = null, Sorter $sorter = null, PaginatorWidget $paginatorWidget = null)
	{
		if (isset($paginatorWidget))
		{
			$td = new HtmlElement('td');
			$td->setClass(array('lw-grid-footer-cell', 'lw-paginator-container'));
			$td->setAttribute('colspan', count($this->columns));
			$td->setContent($paginatorWidget->pack());	
		
			$tr = new HtmlElement('tr');
			$tr->setClass(array('lw-grid-footer'));
			$tr->setContent($td);
		
			$tfoot = new HtmlElement('tfoot');
			$tfoot->setClass(array('lw-grid-footer-container'));
			$tfoot->setContent($tr);
			return $tfoot;
		}
		
		return null;
	}
	
	/**
	 * Builds the entire table body.
	 *
	 * @param Provider $provider
	 *	The data provider to build the table from.
	 *
	 * @param Paginator $paginator
	 *	The paginator linked with the given provider instance.
	 *
	 * @param Sorter $sorter
	 *	The sorter linked with the given provider instance.
	 *
	 * @return HtmlElement
	 *	The html element instance.
	 */
	protected function buildTableBody(Provider $provider, Paginator $paginator = null, Sorter $sorter = null)
	{
		$rows = array();
	
		foreach ($provider->getIterator() as $item)
		{
			$rows[] = $this->buildTableBodyRow($provider, $paginator, $sorter, $item);
		}
		
		$tbody = new HtmlElement('tbody');
		$tbody->setClass(array('lw-grid-body'));
		$tbody->setContent($rows);
		return $tbody;
	}
	
	/**
	 * Builds a row.
	 *
	 * @param Provider $provider
	 *	The data provider to build the table from.
	 *
	 * @param Paginator $paginator
	 *	The paginator linked with the given provider instance.
	 *
	 * @param Sorter $sorter
	 *	The sorter linked with the given provider instance.
	 *
	 * @param mixed $item
	 *	The item to build the row for.
	 *
	 * @return HtmlElement
	 *	The html element instance.
	 */
	protected function buildTableBodyRow(Provider $provider, Paginator $paginator = null, Sorter $sorter = null, $item)
	{
		$cells = array();
		
		foreach ($this->columns as $column)
		{
			$cells[] = $column->buildCell($provider, $item);
		}
		
		$tr = new HtmlElement('tr');
		$tr->setClass(array('lw-grid-item'));
		$tr->setContent($cells);
		return $tr;
	}
	
	/**
	 * Builds the widget container.
	 *
	 * @param Provider $provider
	 *	The data provider to build the table from.
	 *
	 * @param Paginator $paginator
	 *	The paginator linked with the given provider instance.
	 *
	 * @param Sorter $sorter
	 *	The sorter linked with the given provider instance.
	 *
	 * @param PaginatorWidget $paginatorWidget
	 *	The paginator widget to deploy after the table is built.
	 *
	 * @return HtmlElement
	 *	The html element instance.
	 */
	protected function buildContainer(Provider $provider, Paginator $paginator = null, Sorter $sorter = null, PaginatorWidget $paginatorWidget = null)
	{	
		// Build the container with the table
		$div = new HtmlElement('div');
		$div->setClass(array('lw-grid'));
		$div->setContent(
			$this->buildTable($provider, $paginator, $sorter, $paginatorWidget)
		);
		
		return $div;
	}
	
	/**
	 * Builds the paginator widget linked to the given paginator
	 * handle.
	 *
	 * @return PaginatorWidget
	 *	The paginator widget instance.
	 */
	protected function buildPaginatorWidget(Paginator $paginator)
	{
		return Widget::create('data.paginator', $paginator);
	}
	
	/**
	 * Builds the widget HTML element and returns it.
	 *
	 * @return HtmlElement
	 *	The packed HTML element instance.
	 */
	protected function build()
	{
		$provider = $this->provider;
		$paginator = $provider->getPaginator();
		$sorter = $provider->getSorter();
		
		// Apply the pagination before the table is built
		$paginatorWidget = null;
		
		if (isset($paginator))
		{
			$paginatorWidget = $this->buildPaginatorWidget($paginator);
			$paginatorWidget->setKey($this->getPaginatorKey());
			$paginatorWidget->bindRequest();
		}
		
		// Sorter
		$key = $this->getSorterKey();
		
		if (isset($_GET[$key]))
		{
			$sorter->bind((array) $_GET[$key]);
		}
		
		return $this->buildContainer($provider, $paginator, $sorter, $paginatorWidget);
	}
}


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
use \system\data\provider\paginator\Paginator;
use \system\web\html\Html;
use \system\web\html\HtmlElement;

/**
 * This widget will create a visual representation of a Paginator instance,
 * with links to actions that will display the contents of other pages.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.extension.widget
 * @since 0.2.0
 */
class PaginatorWidget extends Widget
{
	/**
	 * The paginator this widget is linked to.
	 *
	 * @type Paginator
	 */
	private $paginator;
	
	/**
	 * The total number of page items to display, excluding the directional
	 * navigation buttons and the current page.
	 *
	 * @type int
	 */
	private $size = 10;
	
	/**
	 * Defines the variable in the current request query string that holds
	 * the currently active page number.
	 *
	 * @type string
	 */
	private $key = 'page';

	/**
	 * Constructor.
	 *
	 * @param Paginator $paginator
	 *	The paginator this widget is linked to.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct(Paginator $paginator, array $configuration = null)
	{
		parent::__construct(null);
		$this->paginator = $paginator;
		
		if (isset($configuration))
		{
			$this->configure($configuration);
		}
	}
	
	public function setKey($key)
	{
		$this->key = $key;
	}
	
	public function getKey()
	{
		return $this->key;
	}
	
	public function setSize($size)
	{
		$this->size = $size;
	}
	
	public function getSize()
	{
		return $this->size;
	}
	
	/**
	 * Creates an absolute page URL that links to the exact same action
	 * with the exact same parameters and the intended page number.
	 *
	 * @param int $page
	 *	The intended page number.
	 *
	 * @return string
	 *	The absolute page Url.
	 */
	public function getPageUrl($page)
	{
		$router = $this->getComponent('router');
		$route = $router->getRequestRoute();
		
		$parameters = $route[1];
		$parameters[$this->key] = $page;
		
		return $router->createAbsoluteUrl($route[0], $parameters);
	}
	
	/**
	 * Builds the entire page list.
	 *
	 * @param int $from
	 *	The page number to start building the list from.
	 *
	 * @param int $to
	 *	The page number to build the list to.
	 *
	 * @param int $active
	 *	The active page number.
	 *
	 * @return HtmlElement
	 *	The entire page list.
	 */
	public function buildList($from, $to, $active, $count)
	{
		$items = array(
			$this->buildFirstListItem(1, $active > 1),
			$this->buildPreviousListItem($active - 1, $active > 1)
		);
		
		for ($i = $from; $i <= $to; ++$i)
		{
			$items[] = $this->buildPageListItem($i, $active === $i);
		}
		
		$items[] = $this->buildNextListItem($active + 1, $active < $count);
		$items[] = $this->buildLastListItem($count, $active < $count);
		
		$ul = new HtmlElement('ul');
		$ul->setClass('ui-paginatorwidget-list');
		$ul->setContent($items);
		return $ul;
	}
	
	public function buildFirstListItem($page, $enable)
	{
		$a = new HtmlElement('a');
		$a->setTextContent('<<<');
		
		if ($enable)
		{
			$a->set('href', $this->getPageUrl($page));
		}
	
		$li = new HtmlElement('li');
		$li->setContent($a);
		$li->setClass(array(
			'ui-paginatorwidget-item',
			'ui-paginatorwidget-item-first',
			($enable ? 'available' : 'disabled')
		));
		
		return $li;
	}
	
	public function buildPreviousListItem($page, $enable)
	{
		$a = new HtmlElement('a');
		$a->setTextContent('<');
		
		if ($enable)
		{
			$a->set('href', $this->getPageUrl($page));
		}
	
		$li = new HtmlElement('li');
		$li->setContent($a);
		$li->setClass(array(
			'ui-paginatorwidget-item',
			'ui-paginatorwidget-item-previous',
			($enable ? 'available' : 'disabled')
		));
		
		return $li;
	}
	
	public function buildLastListItem($page, $enable)
	{
		$a = new HtmlElement('a');
		$a->setTextContent('>>>');
		
		if ($enable)
		{
			$a->set('href', $this->getPageUrl($page));
		}
	
		$li = new HtmlElement('li');
		$li->setContent($a);
		$li->setClass(array(
			'ui-paginatorwidget-item',
			'ui-paginatorwidget-item-last',
			($enable ? 'available' : 'disabled')
		));
		
		return $li;
	}
	
	public function buildNextListItem($page, $enable)
	{
		$a = new HtmlElement('a');
		$a->setTextContent('>');
		
		if ($enable)
		{
			$a->set('href', $this->getPageUrl($page));
		}
	
		$li = new HtmlElement('li');
		$li->setContent($a);
		$li->setClass(array(
			'ui-paginatorwidget-item',
			'ui-paginatorwidget-item-last',
			($enable ? 'available' : 'disabled')
		));
		
		return $li;
	}
	
	/**
	 * Builds the page item element.
	 *
	 * @param int $page
	 *	The number of the page to build the item for.
	 *
	 * @param bool $active
	 *	A flag indicating wether this is the currently active page.
	 *
	 * @return HtmlElement
	 *	The page item element.
	 */
	public function buildPageListItem($page, $active)
	{	
		$a = new HtmlElement('a');
		$a->setTextContent($page);
		
		if (!$active)
		{
			$a->set('href', $this->getPageUrl($page));
		}
	
		$li = new HtmlElement('li');
		$li->setClass(array('ui-paginatorwidget-item', ($active ? 'active' : 'available')));
		$li->setContent($a);
		return $li;
	}
	
	/**
	 * Parses the current request and defines the currently active page
	 * as specified in the query string.
	 *
	 * @throws HttpException
	 *	Throws a '404 Document Not Found' when the page number is out of range.
	 */
	public function apply()
	{
		$key = $this->key;
		
		if (isset($_GET[$key]))
		{
			$page = intval($_GET[$key]);
			
			if ($page < 1 || $page > $this->paginator->getPageCount())
			{
				throw HttpException(404, 'Document Not Found');
			}
		}
		else
		{
			$page = 1;
		}
		
		$this->paginator->setActivePage($page);
	}
	
	public function build()
	{
		$paginator = $this->paginator;
		$count = $paginator->getPageCount();
		
		$list = $this->buildList(1, $count, $paginator->getActivePage(), $count);
		
		$div = new HtmlElement('div');
		$div->setClass(array('ui-paginatorwidget ui-paginatorwidget-container'));
		$div->setContent($list);
		return $div;
	}
	
	public function deploy()
	{
		$this->build()->render();
	}
}


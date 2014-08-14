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

namespace system\web\widget\data;

use \system\data\provider\paginator\Paginator;
use \system\web\Widget;
use \system\web\html\Html;
use \system\web\html\HtmlElement;

/**
 * This widget will create a visual representation of a Paginator instance,
 * with links to actions that will display the contents of other pages.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
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
	
	/**
	 * Defines the paginator query string key.
	 *
	 * @param string $key
	 *	The paginator query string key.
	 */
	public function setKey($key)
	{
		$this->key = $key;
	}
	
	/**
	 * Returns the paginator query string key.
	 *
	 * @return string
	 *	The paginator query string key.
	 */
	public function getKey()
	{
		return $this->key;
	}
	
	/**
	 * Defines the number of pagination items to display, excluding
	 * the currently active page and first, previous, next and last items.
	 *
	 * @param int $size
	 *	The number of pagination items to display.
	 */
	public function setSize($size)
	{
		$this->size = $size;
	}
	
	/**
	 * Returns the number of pagination items to display.
	 *
	 * @return int
	 *	The number of pagination items to display.
	 */
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
	protected function buildList($from, $to, $active, $count)
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
		$ul->setClass('lw-paginator');
		$ul->setContent($items);
		$ul->setAttribute('id', $this->getId());
		return $ul;
	}
	
	/**
	 * Builds the 'to first' list item.
	 *
	 * @param int $page
	 *	The number of the page the item should link to.
	 *
	 * @param bool $enable
	 *	A flag indicating wether or not this pagination list item
	 *	should be enabled.
	 *
	 * @return HtmlElement
	 *	The pagination list item.
	 */
	protected function buildFirstListItem($page, $enable)
	{
		$a = new HtmlElement('a');
		$a->setTextContent('<<<');
		
		if ($enable)
		{
			$a->setAttribute('href', $this->getPageUrl($page));
		}
	
		$li = new HtmlElement('li');
		$li->setContent($a);
		$li->setClass(array(
			'lw-paginator-item',
			'lw-paginator-item-first',
			($enable ? 'lw-paginator-item-available' : 'lw-paginator-item-disabled')
		));
		
		return $li;
	}
	
	/**
	 * Builds the 'to previous' list item.
	 *
	 * @param int $page
	 *	The number of the page the item should link to.
	 *
	 * @param bool $enable
	 *	A flag indicating wether or not this pagination list item
	 *	should be enabled.
	 *
	 * @return HtmlElement
	 *	The pagination list item.
	 */
	protected function buildPreviousListItem($page, $enable)
	{
		$a = new HtmlElement('a');
		$a->setTextContent('<');
		
		if ($enable)
		{
			$a->setAttribute('href', $this->getPageUrl($page));
		}
	
		$li = new HtmlElement('li');
		$li->setContent($a);
		$li->setClass(array(
			'lw-paginator-item',
			'lw-paginator-item-previous',
			($enable ? 'lw-paginator-item-available' : 'lw-paginator-item-disabled')
		));
		
		return $li;
	}
	
	/**
	 * Builds the 'to last' list item.
	 *
	 * @param int $page
	 *	The number of the page the item should link to.
	 *
	 * @param bool $enable
	 *	A flag indicating wether or not this pagination list item
	 *	should be enabled.
	 *
	 * @return HtmlElement
	 *	The pagination list item.
	 */
	protected function buildLastListItem($page, $enable)
	{
		$a = new HtmlElement('a');
		$a->setTextContent('>>>');
		
		if ($enable)
		{
			$a->setAttribute('href', $this->getPageUrl($page));
		}
	
		$li = new HtmlElement('li');
		$li->setContent($a);
		$li->setClass(array(
			'lw-paginator-item',
			'lw-paginator-item-last',
			($enable ? 'lw-paginator-item-available' : 'lw-paginator-item-disabled')
		));
		
		return $li;
	}
	
	/**
	 * Builds the 'to next' list item.
	 *
	 * @param int $page
	 *	The number of the page the item should link to.
	 *
	 * @param bool $enable
	 *	A flag indicating wether or not this pagination list item
	 *	should be enabled.
	 *
	 * @return HtmlElement
	 *	The pagination list item.
	 */
	protected function buildNextListItem($page, $enable)
	{
		$a = new HtmlElement('a');
		$a->setTextContent('>');
		
		if ($enable)
		{
			$a->setAttribute('href', $this->getPageUrl($page));
		}
	
		$li = new HtmlElement('li');
		$li->setContent($a);
		$li->setClass(array(
			'lw-paginator-item',
			'lw-paginator-item-last',
			($enable ? 'lw-paginator-item-available' : 'lw-paginator-item-disabled')
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
	protected function buildPageListItem($page, $active)
	{	
		$a = new HtmlElement('a');
		$a->setTextContent($page);
		
		if (!$active)
		{
			$a->setAttribute('href', $this->getPageUrl($page));
		}
	
		$li = new HtmlElement('li');
		$li->setClass(array('lw-paginator-item', ($active ? 'lw-paginator-item-active' : 'lw-paginator-item-available')));
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
	public function bindRequest()
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
	
	/**
	 * Builds the widget HTML element and returns it.
	 *
	 * @return HtmlElement
	 *	The packed HTML element instance.
	 */
	protected function build()
	{
		$paginator = $this->paginator;
		$count = $paginator->getPageCount();
		return $this->buildList(1, $count, $paginator->getActivePage(), $count);
	}
}


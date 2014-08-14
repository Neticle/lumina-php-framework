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

use \system\web\Widget;
use \system\web\html\HtmlElement;
use \system\web\navigation\Breadcrumb;

/**
 * The breadcrumb widget can be used to deploy the current state
 * of a breadcrumb navigation component.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class BreadcrumbWidget extends Widget
{
	/**
	 * The breadcrumb navigation instance.
	 *
	 * @type Breadcrumb
	 */
	private $breadcrumb;

	/**
	 * Constructor.
	 *
	 * @param Breadcrumb $breadcrumb
	 *	The breadcrumb navigation instance.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct(Breadcrumb $breadcrumb, array $configuration = null)
	{
		parent::__construct(null);
		$this->breadcrumb = $breadcrumb;
		
		if (isset($configuration))
		{
			$this->configure($configuration);
		}
	}
	
	/**
	 * Builds the entire breadcrumb list anchor.
	 *
	 * @param Breadcrumb $breadcrumb
	 *	The navigation instance to build the list for.
	 *
	 * @param array $route
	 *	The route to which the item links to.
	 *
	 * @param string $title
	 *	The item title.
	 *
	 * @param bool $active
	 *	A flag indicating wether or not the item to construct is active
	 *	or current (last) item.
	 *
	 * @return HtmlElement
	 *	The constructed html element instance.
	 */
	protected function buildListItemAnchor(Breadcrumb $breadcrumb, array $route, $title, $active)
	{
		$a = new HtmlElement('a');
		$a->setClass(array('lw-breadcrumb-item-anchor', ($active ? 'active' : 'current')));
		$a->setTextContent($title);
		
		if ($active)
		{
			$a->setAttribute('href', 
				$this->getComponent('router')->
					createUrl($route[0], array_slice($route, 1))
			);
		}
		
		return $a;
	}
	
	/**
	 * Builds the entire breadcrumb list item.
	 *
	 * @param Breadcrumb $breadcrumb
	 *	The navigation instance to build the list for.
	 *
	 * @param array $route
	 *	The route to which the item links to.
	 *
	 * @param string $title
	 *	The item title.
	 *
	 * @param bool $active
	 *	A flag indicating wether or not the item to construct is active
	 *	or current (last) item.
	 *
	 * @return HtmlElement
	 *	The constructed html element instance.
	 */
	protected function buildListItem(Breadcrumb $breadcrumb, array $route, $title, $active)
	{
		$a = $this->buildListItemAnchor($breadcrumb, $route, $title, $active);
	
		$li = new HtmlElement('li');
		$li->setClass(array('lw-breadcrumb-item', ($active ? 'active' : 'current')));
		$li->setContent($a);
		return $li;
	}
	
	/**
	 * Builds the entire breadcrumb navigation list.
	 *
	 * @param Breadcrumb $breadcrumb
	 *	The navigation instance to build the list for.
	 *
	 * @return HtmlElement
	 *	The constructed html element instance.
	 */
	protected function buildList(Breadcrumb $breadcrumb)
	{
		$items = $breadcrumb->getItems();
		$count = count($items);
		
		$content = array();
		
		for ($i = 0; $i < $count; /* void */)
		{
			$content[] = $this->buildListItem($breadcrumb, $items[$i][0], $items[$i][1], ($count > ++$i));
		}
		
		$ul = new HtmlElement('ul');
		$ul->setClass(array('lw-breadcrumb'));
		$ul->setContent($content);
		return $ul;
	}
	
	/**
	 * Builds the widget HTML element and returns it.
	 *
	 * @return HtmlElement
	 *	The packed HTML element instance.
	 */
	protected function build()
	{
		return $this->buildList($this->breadcrumb);
	}
}


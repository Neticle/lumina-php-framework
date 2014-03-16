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

namespace system\web\navigation;

use \system\base\Component;
use \system\ext\web\widget\navigation\BreadcrumbWidget;

/**
 * The breadcrumb navigation component can be used by any element to register
 * nodes, which can then be deployed through the 'BreadcrumbWidget' widget.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.navigation
 * @since 0.2.0
 */
class Breadcrumb extends Component
{
	/**
	 * The breadcrumb navigation items.
	 *
	 * @type array
	 */
	private $items = array();
	
	/**
	 * The breadcrumb widget to deploy this navigation with.
	 *
	 * @type BreadcrumbWidget
	 */
	private $breadcrumbWidget;
	
	/**
	 * Adds a new breadcrumb navigation item.
	 *
	 * @param array $route
	 *	The route array resolving to the matching action.
	 *
	 * @param string $title
	 *	The breadcrumb item title.
	 */
	public function addItem(array $route, $title)
	{
		$this->items[] = array($route, $title);
	}
	
	/**
	 * Returns the breadcrumb navigation items array.
	 *
	 * @return array
	 *	The breadcrumb navigation items array.
	 */
	public function getItems()
	{
		return $this->items;
	}
	
	/**
	 * Deploys the state of this component through the
	 * breadcrumb widget.
	 */
	public function deploy()
	{
		if (!isset($this->breadcrumbWidget))
		{
			$this->breadcrumbWidget = new BreadcrumbWidget($this);
		}
		
		$this->breadcrumbWidget->deploy();
	}
}


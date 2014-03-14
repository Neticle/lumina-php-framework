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

namespace system\data\provider\paginator;

use \system\core\Element;
use \system\data\provider\Provider;

/**
 * An abstract paginator that provides a consistent API across multiple
 * implementations.
 *
 * Although the base implementation is abstract, final implementations of this
 * class will provide additional methods to ease the application of this
 * feature.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.provider.paginator
 * @since 0.2.0
 */
abstract class Paginator extends Element
{
	/**
	 * The instance of the provider this instance is linked with.
	 *
	 * @type Provider
	 */
	private $provider;
	
	/**
	 * The number of items to display per page.
	 *
	 * @type int
	 */
	private $interval = 25;
	
	/**
	 * The currently active page number.
	 *
	 * @type int
	 */
	private $activePage = 1;

	/**
	 * Constructor.
	 *
	 * @param Provider $provider
	 *	The data provider this paginator is to be linked with.
	 *
	 * @param array $configuration
	 *	The paginator configuration.
	 */
	protected function __construct(Provider $provider, array $configuration = null)
	{
		parent::__construct(null);
		$this->provider = $provider;
		$this->configure($configuration);
	}
	
	/**
	 * Defines the number of items per page.
	 *
	 * @param int $interval
	 *	The number of items per page.
	 */
	public function setInterval($interval)
	{
		if ($interval < 1)
		{
			throw new RuntimeException('Invalid value "' . $interval . '" specified for interval.');
		}
	
		$this->interval = $interval;
		$this->provider->reset();
	}
	
	/**
	 * Returns the number of items per page.
	 *
	 * @return int
	 *	The number of items per page.
	 */
	public function getInterval()
	{
		return $this->interval;
	}
	
	/**
	 * Returns the provider this instance is linked to.
	 *
	 * @return Provider
	 *	The provider this instance is linked to.
	 */
	public function getProvider()
	{
		return $this->provider;
	}
	
	/**
	 * Changes the paginator provider and defines the currently
	 * active page back to 1.
	 *
	 * @param Provider $provider
	 *	The new provider instance.
	 */
	public function setProvider(Provider $provider)
	{
		$provider->reset();
		$this->provider = $provider;
		$this->activePage = 1;
	}
	
	/**
	 * Returns the total item count, as reported by the
	 * data provider.
	 *
	 * @return int
	 *	The total item count, across all pages.
	 */
	public function getTotalItemCount()
	{
		return $this->provider->getTotalItemCount();
	}
	
	/**
	 * Returns the total page count.
	 *
	 * @return int
	 *	The total page count.
	 */
	public function getPageCount()
	{
		$itemCount = $this->provider->getTotalItemCount();
		
		if (isset($this->interval) && $this->interval > 0 && $itemCount > 0)
		{
			return (int) ceil($itemCount / $this->interval);
		}
		
		return 1;
	}
	
	/**
	 * Defines the currently active page number.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified page number is invalid.
	 *
	 * @param int $activePage
	 *	The active page number.
	 */
	public function setActivePage($activePage)
	{
		if ($activePage < 1 || $activePage > $this->getPageCount())
		{
			throw new RuntimeException('Invalid page "' . $activePage . '" specified.');
		}
		
		$this->activePage = $activePage;
		$this->provider->reset();
	}
	
	/**
	 * Returns the currently active page number.
	 *
	 * @return int
	 *	The currently active page number.
	 */
	public function getActivePage()
	{
		return $this->activePage;
	}
	
	/**
	 * Returns the offset for the first item of the specified page.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified page number is invalid.
	 *
	 * @param int $page
	 *	The page to get the offset for.
	 *
	 * @return int
	 *	The page offset.
	 */
	public function getPageOffset($page)
	{
		if ($page < 1 || $page > $this->getPageCount())
		{
			throw new RuntimeException('Invalid page "' . $activePage . '" specified.');
		}
		
		return ($page - 1) * $this->interval;
	}
	
	/**
	 * Returns the offset for the first item of the currently active page.
	 *
	 * @return int
	 *	The page offset.
	 */
	public function getActivePageOffset()
	{
		return ($this->activePage - 1) * $this->interval;
	}
}


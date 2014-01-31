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

namespace system\core;

use \system\core\Extension;

/**
 * A Context is a special kind of extension that can be used with other
 * extensions capable of having different behaviours depending on the
 * information provided by it.
 *
 * An example of where a Context extension is useful is when creating URLs
 * that are relative to a specific module or controller.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
abstract class Context extends Extension
{
	/**
	 * Constructor.
	 *
	 * @param string $name
	 *	The contextual extension name.
	 *
	 * @param Extension $parent
	 *	The parent extension instance, if any.
	 */
	protected function __construct($name, Context $parent = null)
	{
		parent::__construct($parent);
		$this->name = $name;
	}
	
	/**
	 * Returns the context extension name.
	 *
	 * @return string
	 *	The extension name.
	 */
	public final function getName()
	{
		return $this->name;
	}
	
	/**
	 * Returns the route resolving to this context, relative to the
	 * base extension -- which is also a Context.
	 *
	 * @return string
	 *	The context route.
	 */
	public final function getRoute()
	{
		$route = null;
	
		if (!$this->isBaseExtension())
		{
			$context = $this->getParent();
			$route = $this->name;
			
			while (!$context->isBaseExtension())
			{
				$route = $context->name . '/' . $route;
				$context = $context->getParent();
			}
		}
		
		return $route;
	}
}


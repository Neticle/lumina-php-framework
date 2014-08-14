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

use \system\core\exception\RuntimeException;

/**
 * The express class provides methods to simplify the construction and
 * configuration of instances of classes extending it.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
abstract class Express
{
	/**
	 * Constructor.
	 *
	 * @param array $configuration
	 *	The construction express configuration array.
	 */
	protected function __construct(array $configuration = null)
	{
		if (isset($configuration))
		{
			$this->configure($configuration);
		}
	}
	
	/**
	 * Configures the current instance by invoking the matching 'set' methods
	 * for each one of the given properties.
	 *
	 * If the 'set' method is not found or invalid the property will be
	 * ignored and a NOTICE will be raised.
	 *
	 * The 'set' method of a property is calculated by concatenating 'set'
	 * with the property name (first letter in upper case).
	 *
	 * @param array $configuration
	 *	The express configuration array, indexed by property.
	 */
	public function configure(array $configuration)
	{
		$class = new \ReflectionClass($this);
		
		foreach ($configuration as $property => $value)
		{
			$method = 'set' . ucfirst($property);
			
			if ($class->hasMethod($method))
			{
				$method = $class->getMethod($method);
				
				if ($method->isPublic() && !$method->isStatic()
					&& $method->getNumberOfRequiredParameters() === 1)
				{
					$method->invoke($this, $value);
					continue;
				}
				
			}
			
			trigger_error('Invalid property "' . $property . 
				'" specified for express configuration.', E_USER_NOTICE);
		}
	}
}


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

namespace system\sql\schema;

use \system\core\Express;

/**
 * The base schema object.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
abstract class Schema extends Express
{
	/**
	 * The schema object name.
	 *
	 * @type string
	 */
	private $name;
	
	/**
	 * The schema object data character encoding set.
	 *
	 * @type string
	 */
	private $charset;

	/**
	 * Constructor.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct(array $configuration = null)
	{
		parent::__construct($configuration);
	}
	
	/**
	 * Defines the schema object name.
	 *
	 * @param string $name
	 *	The schema object name.
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Returns the schema object name.
	 *
	 * @return string
	 *	The schema object name.
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Defines the schema object data character encoding set.
	 *
	 * @param string $charset
	 *	The schema object data character encoding set.
	 */
	public function setCharset($charset)
	{
		$this->charset = $charset;
	}
	
	/**
	 * Returns the schema object data character encoding set.
	 *
	 * @return string
	 *	The schema object data character encoding set.
	 */
	public function getCharset()
	{
		return $this->charset;
	}
}


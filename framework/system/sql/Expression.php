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

namespace system\sql;

/**
 * Wraps a SQL expression and can be used to insert or update fields based
 * on any SQL expression.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class Expression
{
	/**
	 * The SQL expression wrapped by this instance.
	 *
	 * @type string
	 */
	private $expression;
	
	/**
	 * Constructor.
	 *
	 * @param string $expression
	 *	The expression to wrap with this instance.
	 */
	public function __construct($expression)
	{
		$this->expression = $expression;
	}
	
	/**
	 * Returns the SQL expression wrapped by this instance.
	 *
	 * @return string
	 *	The wrapped SQL expression.
	 */
	public function toString()
	{
		return $this->expression;
	}
}

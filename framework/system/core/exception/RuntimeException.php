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

namespace system\core\exception;

use \system\core\exception\Exception;

/**
 * A runtime exception, which is usually thrown due to misconfigurations,
 * incorrect argument types and other situations that are directly related
 * to the application specific implementation.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core.exception
 * @since 0.2.0
 */
class RuntimeException extends Exception
{
	/**
	 * Constructor.
	 *
	 * @param string $message
	 *	A human readable message describing the exception.
	 *
	 * @param PHPException $previous
	 *	The previous exception instance, for chaining.
	 */
	public function __construct($message, $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}
}


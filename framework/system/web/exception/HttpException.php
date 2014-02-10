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

namespace system\web\exception;

use \system\core\exception\Exception;

/**
 * A HttpException is intended to be thrown by the controllers and handled
 * by the application in order to generate a proper HTTP response for the
 * client.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core.exception
 * @since 0.2.0
 */
class HttpException extends Exception
{
	/**
	 * The response status code.
	 *
	 * @type int
	 */
	private $statusCode;
	
	/**
	 * The response status description.
	 *
	 * @type string
	 */
	private $statusDescription;
	
	/**
	 * Constructor.
	 *
	 * @param int $status
	 *	The response status code.
	 *
	 * @param string $description
	 *	The response status description.
	 *
	 * @param PHPException $previous
	 *	The previous exception instance, for chaining.
	 */
	public function __construct($status, $description, $previous = null)
	{
		parent::__construct($description, $previous);
		$this->statusCode = $status;
		$this->statusDescription = $description;
	}
	
	/**
	 * Returns the response status code.
	 *
	 * @return int
	 *	The response status code.
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}
	
	/**
	 * Returns the response status description.
	 *
	 * @return string
	 *	The response status description.
	 */
	public function getStatusDescription()
	{
		return $this->statusDescription;
	}
}


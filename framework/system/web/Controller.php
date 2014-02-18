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

namespace system\web;

use \system\base\Component;
use \system\web\Response;
use \system\web\exception\HttpException;

/**
 * The web Controller is intended to be used by web applications and will
 * throw http exceptions when the dispatch procedure fails on them.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web
 * @since 0.2.0
 */
class Controller extends \system\base\Controller
{
	/**
	 * This method is invoked right after the controller dispatch procedure
	 * fails, for whatever reason, and throws a 404 Document Not Found
	 * http exception.
	 *
	 * This method encapsulates the "dispatchFailure" event.
	 *
	 * @throws HttpException
	 *	Thrown if the event is not canceled by one of its handlers.
	 *
	 * @param string $action
	 *	The name of the action to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @return bool
	 *	Returns TRUE.
	 */
	protected function onDispatchFailure($action, array $parameters = null)
	{
		if ($this->raiseArray('dispatchFailure', array($action, $parameters)))
		{
			throw new HttpException(404, 'Document Not Found');
		}
	}
	
	/**
	 * This method is invoked right after the controller dispatch procedure
	 * fails binding the provided parameters.
	 *
	 * This method encapsulates the "dispatchActionBindFailure" event.
	 *
	 * @throws HttpException
	 *	Thrown if the event is not canceled by one of its handlers.
	 *
	 * @param string $action
	 *	The name of the action to dispatch to.
	 *
	 * @param array $parameters
	 *	An associative array defining the values to be bound to the action
	 *	parameters, indexed by name.
	 *
	 * @return bool
	 *	Returns TRUE.
	 */
	protected function onDispatchActionBindFailure($action, array $parameters = null)
	{
		if ($this->raiseArray('dispatchActionBindFailure', array($action, $parameters)))
		{
			throw new HttpException(400, 'Bad Request');
		}
	}
	
	/**
	 * Redirects the client to the given location.
	 *
	 * @param string|array $location
	 *	The location to redirect to as a string or a route array.
	 *
	 * @param bool $terminate
	 *	When set to TRUE the script execution will be terminated right after
	 *	the location header is set.
	 */
	protected function redirect($location, $terminate = true)
	{
		Response::setLocation($location);
		
		if ($terminate)
		{
			exit;
		}
	}
}


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
		if (is_array($location))
		{
			$location = $this->getComponent('router')->createAbsoluteUrl(
				$this->getResolvedContextRoute($location[0]), 
				array_slice($location, 1)
			);
		}
	
		header('Location: ' . $location);
		
		if ($terminate)
		{
			exit;
		}
	}
	
	/**
	 * Sets the response status code and message.
	 *
	 * @param int $status
	 *	The HTTP status code
	 *
	 * @param string $message
	 *	The HTTP status message. If none provided the default message for the
	 *	given code will be used.
	 */
	protected function setHttpStatus($status, $message = null) {
		Response::setStatus($status, $message);
	}
	
	/**
	 * Sends a text response with the given status code and proper mime-type.
	 *
	 * @param int $status
	 *	The HTTP status code
	 *
	 * @param sring $text
	 *	A string containing the text to be sent.
	 */
	protected function sendText($status, $text) {
		$this->setHttpStatus($status);
		
		Response::setHeader('Content-Type', 'text/plain', true);
		
		echo $text;
	}
	
	/**
	 * Sends a JSON response with the given status code and proper mime-type.
	 *
	 * @param int $status
	 *	The HTTP status code
	 *
	 * @param array $data
	 *	An array to be encoded and sent as JSON.
	 */
	protected function sendJson($status, $data) {
		$this->setHttpStatus($status);
		
		Response::setHeader('Content-Type', 'application/json', true);
		
		echo json_encode($data);
	}

}


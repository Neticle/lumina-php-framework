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

namespace system\web\session;

use \system\base\Component;
use \system\core\exception\RuntimeException;
use \system\web\session\ISessionHandler;

/**
 * The session component allows you to keep track of the user state
 * persistently across multiple requests.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.session
 * @since 0.2.0
 */
abstract class Session extends Component
{
	/**
	 * The session name.
	 *
	 * @type string
	 */
	private $name = 'LPSESSID';

	/**
	 * When set to TRUE this session component will be registered as the
	 * global PHP session handler and session will be automatically started
	 * during it's initialization.
	 *
	 * @type bool
	 */
	private $register = true;
	
	/**
	 * When set to TRUE along with 'register', the session will be started
	 * during the component initialization.
	 *
	 * @type bool
	 */
	private $start = true;
	
	/**
	 * Extends the base behavior by optionally define this component as
	 * the global PHP session handler.
	 *
	 * If this component implements the \SessionHandlerInterface it will be
	 * registered as PHP's session handler and, as such, you should never
	 * create multiple instances of this component unless you set the
	 * 'register' property to FALSE.
	 *
	 * @return bool
	 *	Returns TRUE to continue the event, FALSE otherwise.
	 */
	protected function onInitialize()
	{
		if (parent::onInitialize())
		{
			if ($this->register && $this instanceof ISessionHandler)
			{
				session_set_save_handler(
					array($this, 'openSessionStore'),
					array($this, 'closeSessionStore'),
					array($this, 'readSessionData'),
					array($this, 'writeSessionData'),
					array($this, 'destroySessionData'),
					array($this, 'purgeSessionData')
				);
			}
		
			if ($this->start)
			{
				$this->start();
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns the session name, which is used in cookies and query string
	 * parameters to identify the session.
	 *
	 * @return string
	 *	The session name.
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Defines the session name, which is used in cookies and query string
	 * parameters to identify the session.
	 *
	 * @param string $name
	 *	The session name.
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Starts the current session.
	 *
	 * @throws RuntimeException
	 *	Thrown if the session fails to start.
	 */
	public function start()
	{
		session_name($this->name);
		session_start();
	}
	
	/**
	 * Regenerates the current session.
	 *
	 * @param bool $reset
	 *	When set to TRUE the current session data will be removed during
	 *	the regeneration process.
	 */
	public function regenerate($reset = false)
	{
		if ($reset)
		{
			$_SESSION = array();
		}
		
		if (!session_regenerate_id(true))
		{
			throw new RuntimeException('Unable to regenerate the current session.');
		}
	}
	
	/**
	 * Destroys any data that belongs to the current session.
	 *
	 * @throws RuntimeException
	 *	Thrown when the session fails to be destroyed.
	 */
	public function destroy()
	{
		if (!session_destroy())
		{
			throw new RuntimeException('Unable to destroy the current session.');
		}
	}
	
	/**
	 * Flushes and closes the current session.
	 *
	 * @throws RuntimeException
	 *	Thrown when the session fails to be closed.
	 */
	public function close()
	{
		session_write_close();
	}
	
	/**
	 * Checks wether or not the current session is closed.
	 *
	 * @return bool
	 *	Returns TRUE if the session is closed, FALSE otherwise.
	 */
	public function isClosed()
	{
		return session_status() !== PHP_SESSION_ACTIVE;
	}
	
	/**
	 * Returns a value from the session.
	 *
	 * @param string $attribute
	 *	The attribute to return.
	 *
	 * @param mixed $default
	 *	The value to be returned by default.
	 *
	 * @return mixed
	 *	The matching value, or 'default' if it's not defined.
	 */
	public function getAttribute($attribute, $default = null)
	{
		return isset($_SESSION[$attribute]) ?
			$_SESSION[$attribute] : $default;
	}
	
	/**
	 * Defines a session attribute.
	 *
	 * @param string $attribute
	 *	The attribute name.
	 *
	 * @param mixed $value
	 *	The value to define.
	 */
	public function setAttribute($attribute, $value)
	{
		$_SESSION[$attribute] = $value;
	}
	
	/**
	 * Pushes a value to a session array attribute.
	 *
	 * @param string $attribute
	 *	The session attribute, which must be defined as an array.
	 *
	 * @param mixed $value
	 *	The value to push.
	 */
	public function pushAttribute($attribute, $value)
	{
		$_SESSION[$attribute][] = $value;
	}
	
	/**
	 * Checks wether or not the specified attribute is defined.
	 *
	 * @param string $attribute
	 *	The attribute to be verified.
	 *
	 * @return bool
	 *	Returns TRUE if the key is defined, FALSE otherwise.
	 */
	public function hasAttribute($attribute)
	{
		return isset($_SESSION[$attribute]);
	}
	
	/**
	 * Clears a session attribute.
	 *
	 * @param string $attribute
	 *	The attribute to clear from the session.
	 */
	public function clearAttribute($attribute)
	{
		unset($_SESSION[$attribute]);
	}
	
	/**
	 * Returns an attribute value from the session.
	 *
	 * @param string $attribute
	 *	The attribute name.
	 *
	 * @return mixed
	 *	The attribute value, or 'default' if it's not defined.
	 */
	public function __get($attribute)
	{
		return $this->getAttribute($attribute);
	}
	
	/**
	 * Defines a session attribute value.
	 *
	 * @param string $attribute
	 *	The attribute name.
	 *
	 * @param mixed $value
	 *	The value to define.
	 */
	public function __set($attribute, $value)
	{
		$this->setAttribute($attribute, $value);
	}
	
	/**
	 * Checks wether or not the specified attribute is defined.
	 *
	 * @param string $attribute
	 *	The attribute to be verified.
	 *
	 * @return bool
	 *	Returns TRUE if the attribute is defined, FALSE otherwise.
	 */
	public function __isset($attribute)
	{
		return $this->hasAttribute($attribute);
	}
	
	/**
	 * Clears a session attribute.
	 *
	 * @param string $attribute
	 *	The attribute to be cleared from the session.
	 */
	public function __unset($attribute)
	{
		return $this->clearAttribute($attribute);
	}
	
}


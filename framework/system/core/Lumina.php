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

use \system\core\EventBus;
use \system\core\exception\RuntimeException;

/**
 * Lumina.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
class Lumina 
{
	/**
	 * The package paths, indexed by name.
	 *
	 * @type array
	 */
	private static $packagePaths = array();
	
	/**
	 * The global event bus instance.
	 *
	 * @type EventBus
	 */
	private static $eventBus;
	
	/**
	 * The application instance.
	 *
	 * @type Application
	 */
	private static $application;
	
	/**
	 * Defines the path for a new package.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified package is already defined.
	 *
	 * @param string $package
	 *	The name of the package to define.
	 *
	 * @param string $path
	 *	The absolute path to the package base directory.
	 */
	public static function setPackagePath($package, $path) 
	{
		if (isset(self::$packagePaths[$package])) 
		{
			throw new RuntimeException('Package "' . $package . '" is already defined.');
		}
		
		self::$packagePaths[$package] = $path;
	}
	
	/**
	 * Returns the path for the specified package.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified package is not defined.
	 *
	 * @param string $package
	 *	The name of the package to get the path for.
	 *
	 * @return string
	 *	Returns the package path.
	 */
	public static function getPackagePath($package) 
	{
		if (isset(self::$packagePaths[$package])) 
		{
			return self::$packagePaths[$package];
		}
		
		throw new RuntimeException('Package "' . $package . '" is not defined.');
	}
	
	/**
	 * Returns a flag indicating wether or not the specified package 
	 * path is defined.
	 *
	 * @param string $package
	 *	The name of the package to be verified.
	 *
	 * @return bool
	 *	Returns TRUE if the package is defined, FALSE otherwise.
	 */
	public static function hasPackage($package) 
	{
		return isset(self::$packagePaths[$package]);
	}
	
	/**
	 * Returns a numeric array containing the alias data.
	 *
	 * @param string $alias
	 *	The name of the alias to extract the data from.
	 *
	 * @return array
	 *	An array containing the following two indexes: a flag indicating
	 *	wether or not it's a relative alias; the member path.
	 *
	 *	If the specified alias is absolute the second index will contain the
	 *	absolute path to the resolved file or directory without the extension.
	 */
	public static function getAliasData($alias, $type) 
	{		
		if ($alias[0] === '~') 
		{
			$relative = true;
			$member = str_replace('.', DIRECTORY_SEPARATOR, substr($alias, 1));
		}
		else if ($alias[0] === '@') 
		{
			$relative = $alias[1] === '~';
			$member = substr($alias, $relative ? 2 : 1);
		}
		else 
		{
			if (($index = strpos($alias, '.')) !== false) 
			{
				$token = substr($alias, 0, $index);
				$relative = !isset(self::$packagePaths[$token]);
				
				if ($relative)
				{
					$base = $token;
				}
				else
				{
					$base = self::$packagePaths[$token];
				}
				
				$member = $base . DIRECTORY_SEPARATOR .
					str_replace('.', DIRECTORY_SEPARATOR, substr($alias, $index + 1));
			}
			else
			{
				if (isset(self::$packagePaths[$alias]))
				{
					if (isset($type))
					{
						throw new RuntimeException('Can not apply alias type to package path.');
					}
					
					$relative = false;
					$member = self::$packagePaths[$alias];
				}
				else
				{
					$relative = true;
					$member = $alias;
				}
			}
		}
		
		if (isset($type))
		{
			$member .= '.' . $type;
		}
		
		return array($relative, $member);
	}
	
	/**
	 * Resolves the given alias into an absolute path.
	 *
	 * @param string $alias
	 *	The absolute or relative alias to be resolved.
	 *
	 * @param string $type
	 *	The type of alias being resolved.
	 *
	 * @param string $base
	 *	The base path to resolve relative aliases from.
	 *
	 * @return string
	 *	The resolved alias path.
	 */
	public static function getAliasPath($alias, $type = 'php', $base = null) 
	{
		list($relative, $member) = self::getAliasData($alias, $type);
		
		if ($relative)
		{
			if (isset($base)) 
			{
				if (!empty($member))
				{
					$base .= DIRECTORY_SEPARATOR . $member;
				}
				
				return $base;
			}
			
			throw new RuntimeException('Relative alias specified on absolute context.');
		}
		
		return $member;		
	}
	
	/**
	 * Returns the absolute path of the specified class as long as it's derived
	 * from a defined base package.
	 *
	 * @throws RuntimeException
	 *	When the specified class is not a member of a defined base package
	 *	or in the PHP "\\" namespace.
	 *
	 * @param string $class
	 *	The class to return the path for.
	 *
	 * @return string
	 *	The absolute path to the class file.
	 */
	public static function getClassPath($class)
	{
		$data = explode('\\', $class, 2);
		$package = $data[0];
		
		if (isset($data[1], self::$packagePaths[$package]))
		{
			return self::$packagePaths[$package] . DIRECTORY_SEPARATOR .
				str_replace('\\', DIRECTORY_SEPARATOR, $data[1]) . '.php';
		}
		
		throw new RuntimeException('Invalid class specified.');		
	}
	
	/**
	 * Returns the absolute path of the specified namespace as long as it's 
	 * derived from a defined base package.
	 *
	 * @throws RuntimeException
	 *	When the specified namespace is not a member of a defined base package
	 *	or a package itself.
	 *
	 * @param string $namespace
	 *	The namespace to return the path for.
	 *
	 * @return string
	 *	The absolute path to the namespace directory.
	 */
	public static function getNamespacePath($namespace) 
	{
		$data = explode('\\', $namespace, 2);
		$package = $data[0];
		
		if (isset(self::$packagePaths[$package]))
		{
			$package = self::$packagePaths[$package];
		
			if (isset($data[1]))
			{
				return $package . DIRECTORY_SEPARATOR .
					str_replace('\\', DIRECTORY_SEPARATOR, $data[1]);
			}
			
			return $package;
		}
		
		throw new RuntimeException('Invalid namespace specified.');	
	}
	
	/**
	 * Returns the namespace of a class.
	 *
	 * @param string $class
	 *	The class to get the namespace from.
	 *
	 * @return string
	 *	The class namespace, or NULL.
	 */
	public static function getClassNamespace($class)
	{
		return ($index = strrpos($class, '\\')) === false ?
			null : substr($class, 0, $index);
	}
	
	/**
	 * Returns the name of a class, which does not include the namespace.
	 *
	 * @param string $class
	 *	The class to get the name from.
	 *
	 * @return string
	 *	The class name.
	 */
	public static function getClassName($class)
	{
		return ($index = strrpos($class, '\\')) === false ?
			$class : substr($class, ++$index);
	}
	
	/**
	 * The Lumina class autoloader implementation.
	 *
	 * This method expects the specified class to not be loaded and, if it is
	 * PHP will throw a generic 'already defined' error, as expected.
	 *
	 * @throws RuntimeException
	 *	Thrown when: the class file exists but the class is not defined in it;
	 *	the class does not exist and {$throw} is set to TRUE.
	 *
	 * @param bool $throw
	 *	When set to TRUE an exception will be thrown if the class file 
	 *	does not exist.
	 *
	 * @param string $class
	 *	The name of the class to load.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public static function loadClass($class, $throw = true)
	{
		$path = self::getClassPath($class);
		
		if (file_exists($path))
		{
			require_once($path);
			
			if (class_exists($class, false) || interface_exists($class, false))
			{
				return true;
			}
			
			throw new RuntimeException('Class "' . $class . '" is not defined in "' . $path . '"');
		}
		
		if ($throw)
		{
			throw new RuntimeException('Class "' . $class . '" not found.');
		}
		
		return false;
	}
	
	/**
	 * Checks for the existance of a class.
	 *
	 * If the class is not yet defined it will be loaded automatically through
	 * the Lumina "loadClass" method.
	 *
	 * @throws RuntimeException
	 *	Thrown when the class file exists but the class is not defined in it.
	 *
	 * @param string $class
	 *	The class to check for existance.
	 *
	 * @return bool
	 *	Returns TRUE if the class exists, FALSE otherwise.
	 */
	public static function classExists($class)
	{
		return class_exists($class, false) || self::loadClass($class, false);
	}
	
	/**
	 * Loads an application instance.
	 *
	 * @param string|array $configuration
	 *	An alias relative to the application path resolving to a configuration
	 *	script file, or an associative array with the settings.
	 *
	 * @param string $class
	 *	The application class.
	 *
	 * @return Application
	 *	Returns the application instance.
	 */
	public static function load($configuration = null, $class = 'system\\base\\Application')
	{
		if (isset($configuration) && is_string($configuration))
		{
			$configuration = require
			(
				self::getAliasPath($configuration, 'php', L_APPLICATION)
			);
		}
		
		self::$application = new $class('application', 'application', null, $configuration);
		self::$application->initialize();
		return self::$application;		
	}
	
	/**
	 * Loads an application instance.
	 *
	 * @param string|array $configuration
	 *	An alias relative to the application path resolving to a configuration
	 *	script file, or an associative array with the settings.
	 *
	 * @param string $class
	 *	The application class.
	 *
	 * @return Application
	 *	Returns the application instance.
	 */
	public static function loadWebApplication($configuration = null, $class = 'system\\web\\Application')
	{
		return self::load($configuration, $class);
	}
	
	/**
	 * Returns the application instance.
	 *
	 * @return Application
	 *	The application instance.
	 */
	public static function getApplication()
	{
		return self::$application;
	}
	
	/**
	 * Returns the global event bus instance.
	 *
	 * All events raised by objects extending Element can be captured by
	 * registering a handler through this event bus.
	 *
	 * @return EventBus
	 *	The global event bus instance.
	 */
	public static function getEventBus()
	{
		if (!isset(self::$eventBus))
		{
			self::$eventBus = new EventBus();
		}
		
		return self::$eventBus;
	}

}


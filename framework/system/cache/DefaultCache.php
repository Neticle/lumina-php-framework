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

namespace system\cache;

use \system\cache\Cache;

/**
 * The default (not so) cache component to use, for development purposes.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class DefaultCache extends Cache
{
	/**
	 * Opens or starts any resources required for cache management purposes.
	 *
	 * @throws RuntimeException
	 *	Thrown when connection or initialization fails.
	 */
	public function open()
	{
		/* not implemented */ 
	}
	
	/**
	 * Closes or disposes any resources required for cache management purposes.
	 *
	 * @throws RuntimeException
	 *	Thrown when the operation fails.
	 */
	public function close()
	{
		/* not implemented */ 
	}
	
	/**
	 * Returns a flag indicating the current status of this cache handler.
	 *
	 * @return bool
	 *	Returns TRUE if the cache handle is closed, FALSE otherwise.
	 */
	public function isClosed()
	{
		return false;
	}

	/**
	 * Returns a flag indicating wether or not this cache handler supports
	 * persistent memory caching.
	 *
	 * @return bool
	 *	Returns TRUE if this handler supports memory caching, FALSE otherwise.
	 */
	public function isMemoryCache()
	{
		return true;
	}

	/**
	 * Writes a value to cache.
	 *
	 * @throws RuntimeException
	 *	Thrown when the write operation fails.
	 *
	 * @param string $key
	 *	The unique identifier for the value being cached.
	 *
	 * @param mixed $value
	 *	The value to be cached, which may be converted to a string through the
	 *	PHP serialization algorithm if this cache handler does not support
	 *	persistent memory caching.
	 *
	 * @param int $expiry
	 *	The amount of seconds until this key expires. When set to NULL the
	 *	value will be cached for an undeterminated amount of time, until it's
	 *	invalidated explicitly.
	 */
	public function write($key, $value, $expiry = null)
	{
		/* not implemented */ 
	}
	
	/**
	 * Returns a previously cached value.
	 *
	 * @throws RuntimeException
	 *	Thrown when the read operation fails.
	 *
	 * @param string $key
	 *	The unique identifier of the value to return.
	 *
	 * @return mixed
	 *	Returns the previously cached value or NULL if the value was not found
	 *	or it's past its expiration date.
	 */
	public function read($key)
	{
		return null;
	}
	
	/**
	 * Clears any cached value for the given key.
	 *
	 * @throws RuntimeException
	 *	Thrown when the clear operation fails.
	 */
	public function clear($key)
	{
		/* not implemented */ 
	}
	
	/**
	 * Returns the total size of all cache entries which key starts with
	 * the prefix defined for this component, if any.
	 *
	 * @return int
	 *	The total size of all entries, in bytes.
	 */
	public function getSize()
	{
		return 0;
	}
}


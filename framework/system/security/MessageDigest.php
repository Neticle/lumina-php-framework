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

namespace system\security;

use \system\base\Component;

/**
 * The message digest component will apply a salt prefix to a given
 * string and digest it with an algorithm of choice.
 *
 * Although it was it's initial purpose, you should use 'PasswordDigest'
 * component to generate secure password hashes, through PHP 'crypt'
 * implementation.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class MessageDigest extends Component
{
	/**
	 * The algorithm to hash the contents with.
	 *
	 * @type string
	 */
	private $algorithm = 'sha1';
	
	/**
	 * The salt to be applied to the raw contents.
	 *
	 * @type string
	 */
	private $salt = '"!e#c(/pf)(b!/3&da31$';
	
	/**
	 * Defines the number times the content should be rehashed before
	 * it is returned for use.
	 *
	 * @type int
	 */
	private $rehashCount = 0;
	
	/**
	 * Defines the algorithm to use by default.
	 *
	 * @param string $algorithm
	 *	The hashing algorithm to use by default.
	 */
	public function setAlgorithm($algorithm)
	{
		$this->algorithm = $algorithm;
	}
	
	/**
	 * Returns the algorithm to use by default.
	 *
	 * @return string
	 *	The hashing algorithm to use by default.
	 */
	public function getAlgorithm()
	{
		return $this->algorithm;
	}
	
	/**
	 * Defines the salt to be applied to the raw contents.
	 *
	 * @param string $salt
	 *	The salt to apply to the raw contents.
	 */
	public function setSalt($salt)
	{
		$this->salt = $salt;
	}
	
	/**
	 * Returns the salt to be applied to the raw contents.
	 *
	 * @return string
	 *	The salt to apply to the raw contents.
	 */
	public function getSalt()
	{
		return $this->salt;
	}
	
	/**
	 * Defines the number of times contents should be rehashed before
	 * they are returned for use.
	 *
	 * @param int $rehashCount
	 *	The number of times to rehash the content.
	 */
	public function setRehashCount($rehashCount)
	{
		$this->rehashCount = $rehashCount;
	}
	
	/**
	 * Returns the number of times contents should be rehashed before
	 * they are returned for use.
	 *
	 * @return int
	 *	The number of times to rehash the content.
	 */
	public function getRehashCount()
	{
		return $this->rehashCount;
	}
	
	/**
	 * Digests the given content.
	 *
	 * @param string $content
	 *	The content to digest, as a string.
	 *
	 * @param string|string[] $token
	 *	A token or array of tokens to be appended to the default salt.
	 *
	 *	Considering a user password based example, for extra security, you
	 *	can supply the user id, username and last modification date in order
	 *	to generate a more complex hash.
	 *
	 * @param string $algorithm
	 *	The algorithm to use when digesting strings, or NULL to use
	 *	the default algorithm for this component.
	 *
	 * @param bool $bytes
	 *	When set to TRUE the hash result will be returned as a raw array
	 *	of bytes instead of a HEX string.
	 */
	public function digest($content, $token = null, $algorithm = null, $bytes = false)
	{
		if (!isset($algorithm))
		{
			$algorithm = $this->algorithm;
		}
		
		$salt = $this->salt;
		
		if (isset($token))
		{
			if (is_array($token))
			{
				$token = implode(':', $token);
			}
			
			$salt .= $token;
		}
		
		for($i = -1; $i < $this->rehashCount; ++$i)
		{
			$content .= $salt;
			$content = hash($algorithm, $content, $bytes);
		}
		
		return $content;
	}
}


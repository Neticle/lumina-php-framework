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
 * The message digest component will apply a random salt to a given password
 * and hash it according to certain options, trough PHP's 'crypt' 
 * implementation.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.security
 * @since 0.2.0
 */
class PasswordDigest extends Component
{
	/**
	 * The default algorithm to use when digesting passwords.
	 *
	 * @type string
	 */
	private $algorithm = 'blowfish';
	
	/**
	 * The options to apply when the default algorithm is being used
	 * to digest passwords.
	 *
	 * @type string
	 */
	private $options = array(
		'cost' => '07'
	);

	/**
	 * Creates a random string to be used when building the salt.
	 *
	 * @param int $length
	 *	The length of the random string to build.
	 *
	 * @return string
	 *	The random string.
	 */
	private function createRandomString($length)
	{
		$characters = 'abcdefghijklmnopqrstuvxywzABCDEFGHIJKLMNOPQRSTUVXYWZ0123456789.';
		$result = '';
		
		for ($i = 0; $i < $length; ++$i)
		{
			$index = rand(0, 62);
			$result .= $characters[$index];
		}
		
		return $result;
	}

	/**
	 * Creates a random salt based on the algorithm and given options.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified algorithm doesn't exist or lacks support
	 *	from Lumina, PHP or the environment itself.
	 *
	 * @param string $algorithm
	 *	The name of the algorithm to build the salt for.
	 *
	 * @param array $options
	 *	An associative array containing the salt options.
	 *
	 * @return string
	 *	The generated salt.
	 */
	private function createRandomSalt($algorithm = 'blowfish', array $options = null)
	{
		if ($algorithm === 'blowfish' && CRYPT_BLOWFISH)
		{		
			return  '$2y$' . (isset($options['cost']) ? $options['cost'] : '07') . '$'
				. $this->createRandomString(22);
		}
		
		else if ($algorithm === 'md5' && CRYPT_MD5)
		{
			return '$1$' . $this->createRandomString(9);
		}
		
		else if ($algorithm === 'sha256' && CRYPT_SHA256)
		{			
			$rounds = 'rounds=' . 
				(
					isset($options['rounds']) ?
					((string) $options['rounds']) : '5000'
				) . '$';
			
			return '$5$' . $rounds . $this->createRandomString(16 - strlen($rounds));
		}
		
		else if ($algorithm === 'sha512' && CRYPT_SHA512)
		{			
			$rounds = 'rounds=' . 
				(
					isset($options['rounds']) ?
					((string) $options['rounds']) : '5000'
				) . '$';
			
			return '$6$' . $rounds . $this->createRandomString(16 - strlen($rounds));
		}
		
		throw new RuntimeException('Unsupported algorithm "' . $algorithm . '" specified.');
	}
	
	/**
	 * Digests a given password into a hash.
	 *
	 * @throws RuntimeException
	 *	Thrown when the specified algorithm doesn't exist or lacks support
	 *	from Lumina, PHP or the environment itself.
	 *
	 * @param string $password
	 *	The password to digest.
	 *
	 * @param string $algorithm
	 *	The algorithm to use when digesting the password, or NULL to use the
	 *	configuration applied to this component.
	 *
	 * @param array $options
	 *	An associative array containing the salt options.
	 *
	 * @return string
	 *	The hashed password.
	 */
	public function digest($password, $algorithm = null, array $options = null)
	{
		if (!isset($algorithm))
		{
			$algorithm = $this->algorithm;
			$options = $this->options;
		}
	
		return crypt($password, $this->createRandomSalt($algorithm, $options));
	}
	
	/**
	 * Compares a raw password to a given hash.
	 *
	 * @param string $password
	 *	The password to compare.
	 *
	 * @param string $hash
	 *	The password hash, as returned by 'digest' method, which includes
	 *	all the necessary information to match these two values.
	 *
	 * @return bool
	 *	Returns TRUE if the password and hash match, FALSE otherwise.
	 */
	public function compare($password, $hash)
	{
		return crypt($password, $hash) == $hash;
	}
}


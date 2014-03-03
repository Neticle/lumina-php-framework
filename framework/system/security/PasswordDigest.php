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
use \system\core\exception\RuntimeException;

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
		'cost' => 10
	);
	
	/**
	 * A static preffix salt to be applied to the raw passwords in order to
	 * make the generation of rainbow tables exceptionally hard, even when
	 * the attacker gains access to the original hash with lower cost.
	 *
	 * @type string
	 */
	private $salt;
	
	/**
	 * Defines the default algorithm to use when digesting passwords.
	 *
	 * @param string $algorithm
	 *	The default algorithm to use when digesting passwords.
	 */
	public function setAlgorithm($algorithm)
	{
		$this->algorithm = $algorithm;
	}
	
	/**
	 * Returns the default algorithm to use when digesting passwords.
	 *
	 * @return string
	 *	The default algorithm to use when digesting passwords.
	 */
	public function getAlgorithm()
	{
		return $this->algorithm;
	}
	
	/**
	 * Defines the options to apply when the default algorithm is being used
	 * to digest passwords.
	 *
	 * @param array $options
	 *	The default algorithm options.
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
	}
	
	/**
	 * Returns the options to apply when the default algorithm is being used
	 * to digest passwords.
	 *
	 * @return array
	 *	The default algorithm options.
	 */
	public function getOptions()
	{
		return $this->options;
	}
	
	/**
	 * Defines a static preffix salt to be applied to the raw passwords in order
	 * to make the generation of rainbow tables exceptionally hard, even when
	 * the attacker gains access to the original hash with lower cost.
	 *
	 * Please note that in addition to any defined salt, a random salt is always
	 * generated and used when digesting passwords. Unlike that random salt,
	 * this static salt will not be contained in the final hash as it's applied
	 * to the passwords directly.
	 *
	 * @param string $salt
	 *	The static password salt.
	 */
	public function setSalt($salt)
	{
		$this->salt = $salt;
	}
	
	/**
	 * Returns a static preffix salt to be applied to the raw passwords in order
	 * to make the generation of rainbow tables exceptionally hard, even when
	 * the attacker gains access to the original hash with lower cost.
	 *
	 * @return string
	 *	The static password salt.
	 */
	public function getSalt()
	{
		return $this->salt;
	}

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
			if (isset($options['cost']))
			{
				$cost = $options['cost'];
				
				if ($cost < 10)
				{
					$cost = '0' . $cost;
				}
			}
			else
			{
				$cost = '10';
			}
		
			return  '$2y$' . $cost . '$'
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
	
		return crypt($this->salt . $password, $this->createRandomSalt($algorithm, $options));
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
		return crypt($this->salt . $password, $hash) == $hash;
	}
	
	/**
	 * Matches a given hash against the component configuration.
	 *
	 * If the hash does not match the component configuration, it should be
	 * rehashed for security reasons.
	 *
	 * @throws RuntimeException
	 *	Thrown if the hash fails to be parsed.
	 *
	 * @param string $hash
	 *	The hash to be matched against the component.
	 *
	 * @return bool
	 *	Returns TRUE if the hash matches, FALSE otherwise.
	 */
	public function match($hash)
	{
		$info = $this->parse($hash);
		$algorithm = $info['algorithm'];
		
		if ($algorithm === $this->algorithm)
		{		
			if ($algorithm === 'blowfish')
			{
				$cost = isset($this->options['cost']) ?
					$this->options['cost'] : 10;
			
				return ($cost === $info['cost']);
			}
		
			if ($algorithm === 'sha256' || $algorithm === 'sha512')
			{
				$rounds = isset($this->options['rounds']) ?
					$this->options['rounds'] : 5000;
			
				return ($rounds === $info['rounds']);
			}
		}
		
		return false;
	}
	
	/**
	 * Parses the given hash into an associative array containing the
	 * following indexes:
	 *
	 *	"algorithm": the name of the algorithm used to generate the hash;
	 *
	 *	"modifier": the modifier, which might be the work cost or number
	 *		of rounds required to generate the final hash;
	 *
	 * @throws RuntimeException
	 *	Thrown if the hash fails to be parsed.
	 *
	 * @return array
	 *	The parses hash information, as an associative array.
	 */
	public function parse($hash)
	{
		$index = strpos($hash, '$', 1);
		
		if ($index)
		{
			$start = substr($hash, 0, $index);
		
			if ($start === '$1')
			{
				return array(
					'algorithm' => 'md5',
					'modifier' => null
				);
			}
		
			if ($start === '$5' || $start === '$6')
			{
				$rounds = (substr($hash, 2, 8) === '$rounds=') ?
					intval(substr($hash, 10, strpos($hash, '$', $index + 1) - 10)) : 0;
			
				return array(
					'algorithm' => $start === '$5' ? 'sha256' : 'sha512',
					'modifier' => $rounds,
					'rounds' => $rounds
				);
			}
			
			if ($start === '$2y' || $start === '$2x' || $start === '$2a')
			{
				$cost = intval(ltrim(substr($hash, 4, 2), '0'));
			
				return array(
					'algorithm' => 'blowfish',
					'modifier' => $cost,
					'cost' => $cost
				);
			}
		}
		
		throw new RuntimeException('Failed to parse hash information.');
	}
}


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

namespace system\web\authentication\oauth\server\data;

use \system\core\Express;

/**
 * A simple implementation of the Authorization Code (as specified by the IAuthCode
 * interface).
 * 
 * This class consists of a set of getters and setters and can be configured through
 * it's constructor, as expected behavior from the Express class.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 * @since 0.2.0
 */
class AuthCode extends Express implements IAuthCode
{
	
	/**
	 * The code as a string.
	 * 
	 * @type string 
	 */
	private $code;
	
	/**
	 * The Resource Owner's ID.
	 * 
	 * @type string
	 */
	private $ownerId;
	
	/**
	 * The Client's ID.
	 * 
	 * @type string
	 */
	private $clientId;
	
	/**
	 * The expiration date for this code.
	 * 
	 * @type \DateTime
	 */
	private $expirationDate;
	
	public function __construct(array $attributes = null)
	{
		parent::__construct($attributes);
	}
	
	/**
	 * Gets the code as a string
	 * 
	 * @return string
	 */
	public function getCode ()
	{
		return $this->code;
	}

	/**
	 * Gets the Resource Owner's ID.
	 * 
	 * @return string
	 */
	public function getOwnerId ()
	{
		return $this->ownerId;
	}

	/**
	 * Gets the Client's ID.
	 * 
	 * @return string
	 */
	public function getClientId ()
	{
		return $this->clientId;
	}

	/**
	 * Gets the expiration date for this code.
	 * 
	 * @return \DateTime
	 */
	public function getExpirationDate ()
	{
		return $this->expirationDate;
	}

	/**
	 * Sets the code string.
	 * 
	 * @param string $code
	 */
	public function setCode ($code)
	{
		$this->code = $code;
	}

	/**
	 * Sets the ID of the Resource Owner this code belongs to.
	 * 
	 * @param string $ownerId
	 */
	public function setOwnerId ($ownerId)
	{
		$this->ownerId = $ownerId;
	}

	/**
	 * Sets the ID of the Client this code belongs to.
	 * 
	 * @param string $clientId
	 */
	public function setClientId ($clientId)
	{
		$this->clientId = $clientId;
	}

	/**
	 * Sets the expiration date for this code.
	 * 
	 * @param \DateTime $expirationDate
	 */
	public function setExpirationDate (\DateTime $expirationDate)
	{
		$this->expirationDate = $expirationDate;
	}

	/**
	 * Checks whether or not this code is still valid.
	 * 
	 * @return bool
	 *  Returns TRUE if code is still valid, FALSE otherwise.
	 */
	public function isValid ()
	{
		$expiration = $this->getExpirationDate();

		return $expiration < new \DateTime('now');
	}

}

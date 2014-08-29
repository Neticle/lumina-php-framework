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
 * A simple implementation of the Access Token (as specified by the IAccessToken
 * interface).
 * 
 * This class consists of a set of getters and setters and can be configured through
 * it's constructor, as expected behavior from the Express class.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 * @since 0.2.0
 */
class AccessToken extends Express implements IAccessToken {
	
	/**
	 * The token as a string.
	 * 
	 * @type string 
	 */
	private $token;
	
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
	 * The expiration date for this token.
	 * 
	 * @type \DateTime
	 */
	private $expirationDate;
	
	public function __construct(array $attributes = null)
	{
		parent::__construct($attributes);
	}
	
	/**
	 * Gets the token as a string
	 * 
	 * @return string
	 */
	public function getToken ()
	{
		return $this->token;
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
	 * Gets the expiration date for this token.
	 * 
	 * @return \DateTime
	 */
	public function getExpirationDate ()
	{
		return $this->expirationDate;
	}

	/**
	 * Sets the token string.
	 * 
	 * @param string $token
	 */
	public function setToken ($token)
	{
		$this->token = $token;
	}

	/**
	 * Sets the ID of the Resource Owner this token belongs to.
	 * 
	 * @param string $ownerId
	 */
	public function setOwnerId ($ownerId)
	{
		$this->ownerId = $ownerId;
	}

	/**
	 * Sets the ID of the Client this token belongs to.
	 * 
	 * @param string $clientId
	 */
	public function setClientId ($clientId)
	{
		$this->clientId = $clientId;
	}

	/**
	 * Sets the expiration date for this token.
	 * 
	 * @param \DateTime $expirationDate
	 */
	public function setExpirationDate (\DateTime $expirationDate)
	{
		$this->expirationDate = $expirationDate;
	}

	/**
	 * Checks whether or not this token is still valid.
	 * 
	 * @return bool
	 *  Returns TRUE if token is still valid, FALSE otherwise.
	 */
	public function isValid ()
	{
		$expiration = $this->getExpirationDate();

		return $expiration < new \DateTime('now');
	}

	/**
	 * Gets the token type.
	 * 
	 * @return string
	 */
	public function getType ()
	{
		return IAccessToken::TYPE_BEARER;
	}

}

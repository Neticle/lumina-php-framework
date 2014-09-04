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

use DateTime;
use system\core\Express;
use system\web\authentication\oauth\server\role\IClient;
use system\web\authentication\oauth\server\role\IResourceOwner;

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
	 * The Resource Owner.
	 * 
	 * @type IResourceOwner
	 */
	private $owner;
		
	/**
	 * The Client.
	 * 
	 * @type IClient
	 */
	private $client;
		
	/**
	 * The expiration date for this token.
	 * 
	 * @type \DateTime
	 */
	private $expirationDate;
	
	/**
	 * The token's context type.
	 * 
	 * @type int 
	 */
	private $context;
	
	/**
	 * The token's status.
	 * 
	 * @type int 
	 */
	private $status;
	
	/**
	 * The token's type.
	 * 
	 * @type string
	 */
	private $type;
	
	/**
	 * The refresh token.
	 * 
	 * @type string
	 */
	private $refreshToken;
	
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
	 * Gets the Resource Owner.
	 * 
	 * @return IResourceOwner
	 */
	public function getOwner ()
	{
		return $this->owner;
	}
	
	/**
	 * Gets the Resource Owner's ID.
	 * 
	 * @return string
	 */
	public function getOwnerId ()
	{
		return $this->owner->getIdentifier();
	}

	/**
	 * Gets the Client.
	 * 
	 * @return IClient
	 */
	public function getClient ()
	{
		return $this->client;
	}
	
	/**
	 * Gets the Client's ID.
	 * 
	 * @return string
	 */
	public function getClientId ()
	{
		return $this->client->getIdentifier();
	}

	/**
	 * Gets the expiration date for this token.
	 * 
	 * @return DateTime
	 */
	public function getExpirationDate ()
	{
		return $this->expirationDate;
	}

	/**
	 * Gets the tokens context type.
	 * 
	 * (See IAccessToken::CONTEXT_*)
	 * 
	 * @return int
	 */
	public function getContextType ()
	{
		return $this->context;
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

	public function getRefreshToken ()
	{
		return $this->refreshToken;
	}

	public function getStatus ()
	{
		return $this->status;
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
	 * Sets the Resource Owner this token belongs to.
	 * 
	 * @param IResourceOwner $owner
	 */
	public function setOwner ($owner)
	{
		$this->ownerId = $owner;
	}

	/**
	 * Sets the Client this token belongs to.
	 * 
	 * @param IClient $client
	 */
	public function setClient ($client)
	{
		$this->clientId = $client;
	}

	/**
	 * Sets the expiration date for this token.
	 * 
	 * @param DateTime $expirationDate
	 */
	public function setExpirationDate (DateTime $expirationDate)
	{
		$this->expirationDate = $expirationDate;
	}

	/**
	 * Sets the context type for this token.
	 * 
	 * (See IAccessToken::CONTEXT_*)
	 * 
	 * @param int $context
	 */
	public function setContextType ($context) {
		$this->context = $context;
	}
	
	public function setType ($type)
	{
		$this->type = $type;
	}

	public function setRefreshToken ($refreshToken)
	{
		$this->refreshToken = $refreshToken;
	}

	public function setStatus ($status)
	{
		$this->status = $status;
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

		return $expiration < new DateTime('now');
	}

}

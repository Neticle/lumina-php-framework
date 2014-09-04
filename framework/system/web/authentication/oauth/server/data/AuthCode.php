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
	 * The Resource Owner
	 * 
	 * @type IResourceOwner|string 
	 */
	private $owner;
		
	/**
	 * The Client.
	 * 
	 * @type IClient|string
	 */
	private $client;
		
	/**
	 * The expiration date for this code.
	 * 
	 * @type \DateTime
	 */
	private $expirationDate;

	/**
	 * The status code.
	 * 
	 * @type int
	 */
	private $status;
	
	public function __construct(array $attributes = null)
	{
		parent::__construct($attributes);
	}
	
	/**
	 * Gets the storage instance in use by the oauth component.
	 * 
	 * @return IStorage
	 */
	public function getStorage ()
	{
		return $this->getComponent('oauthProvider')->getStorage();
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
	 * Gets the Resource Owner.
	 * 
	 * @return IResourceOwner|string
	 */
	public function getOwner ($returnId = false)
	{
		$owner = $this->owner;
		
		if($returnId === false && !($owner instanceof IResourceOwner))
		{
			$owner = $this->getStorage()->fetchResourceOwner($owner);
		}
		else if($returnId === true && $owner instanceof IResourceOwner)
		{
			$owner = $owner->getOAuthIdentifier();
		}
		
		return $owner;
	}
	
	/**
	 * Gets the Client.
	 * 
	 * @return IClient|string
	 */
	public function getClient ($returnId = false)
	{
		$client = $this->client;
		
		if($returnId === false && !($client instanceof IClient))
		{
			$client = $this->getStorage()->fetchClient($client);
		}
		else if($returnId === true && $client instanceof IClient)
		{
			$client = $client->getOAuthIdentifier();
		}
		
		return $client;
	}
	
	/**
	 * Gets the expiration date for this code.
	 * 
	 * @return DateTime
	 */
	public function getExpirationDate ()
	{
		return $this->expirationDate;
	}

	public function getStatus ()
	{
		return $this->status;
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

	public function setOwner (IResourceOwner $owner)
	{
		$this->owner = $owner;
	}
	

	public function setClient (IClient $client)
	{
		$this->client = $client;
	}
	
	/**
	 * Sets the expiration date for this code.
	 * 
	 * @param DateTime $expirationDate
	 */
	public function setExpirationDate (DateTime $expirationDate)
	{
		$this->expirationDate = $expirationDate;
	}

	public function setStatus ($status)
	{
		$this->status = $status;
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

		return $expiration < new DateTime('now');
	}

}

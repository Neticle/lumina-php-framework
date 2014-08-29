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

namespace system\web\authentication\oauth\server\role;

use \system\core\Express;

/**
 * A simple implementation of the Client (as specified by the IClient interface).
 * 
 * This class consists of a set of getters and setters and can be configured through
 * it's constructor, as expected behavior from the Express class.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 * @since 0.2.0
 */
class Client extends Express implements IClient
{

	/**
	 * The client identifier string.
	 * 
	 * @type string
	 */
	private $identifier;
	
	/**
	 * The client type (see IClient::TYPE_*)
	 * 
	 * @type int
	 */
	private $type;
	
	/**
	 * The client profile (see IClient::PROFILE_*)
	 * 
	 * @type int
	 */
	private $profile;
	
	/**
	 * The client redirection endpoint URI.
	 * 
	 * @type string
	 */
	private $redirectionEndpointURI;
	
	public function __construct (array $attributes = null)
	{
		parent::__construct($attributes);
	}

	/**
	 * Gets the client's identifier string.
	 * 
	 * @return string
	 */
	public function getIdentifier ()
	{
		return $this->identifier;
	}

	/**
	 * Gets the client's type.
	 * 
	 * (See IClient::TYPE_*)
	 * 
	 * @return int
	 */
	public function getType ()
	{
		return $this->type;
	}

	/**
	 * Gets the client's profile.
	 * 
	 * (See IClient::PROFILE_*)
	 * 
	 * @return int
	 */
	public function getProfile ()
	{
		return $this->profile;
	}

	/**
	 * Gets the client's redirection endpoint URI.
	 * 
	 * @return string
	 */
	public function getRedirectionEndpointURI ()
	{
		return $this->redirectionEndpointURI;
	}

	/**
	 * Sets the client's identifier string.
	 * 
	 * @param string $identifier
	 */
	public function setIdentifier ($identifier)
	{
		$this->identifier = $identifier;
	}

	/**
	 * Sets the client's type.
	 * 
	 * (See IClient::TYPE_*)
	 * 
	 * @param int $type
	 */
	public function setType ($type)
	{
		$this->type = $type;
	}

	/**
	 * Sets the client's profile.
	 * 
	 * (See IClient::PROFILE_*)
	 * 
	 * @param int $profile
	 */
	public function setProfile ($profile)
	{
		$this->profile = $profile;
	}

	/**
	 * Sets the client's redirection endpoint URI.
	 * 
	 * @param string $redirectionEndpointURI
	 */
	public function setRedirectionEndpointURI ($redirectionEndpointURI)
	{
		$this->redirectionEndpointURI = $redirectionEndpointURI;
	}

}

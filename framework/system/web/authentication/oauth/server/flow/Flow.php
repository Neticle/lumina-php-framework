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

namespace system\web\authentication\oauth\server\flow;

use system\core\Express;
use system\web\Request;
use system\web\authentication\oauth\server\role\IAuthorizationServer;

/**
 * Description of Flow
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
abstract class Flow extends Express 
{
    
	private $provider;
	
	private $authorizationServer;
	
	public function __construct(array $configuration) 
	{
		parent::__construct($configuration);
	}
	
	public function getAuthorizationServer ()
	{
		return $this->authorizationServer;
	}

	public function setAuthorizationServer (IAuthorizationServer $authorizationServer)
	{
		$this->authorizationServer = $authorizationServer;
	}

	public function getProvider ()
	{
		return $this->provider;
	}

	public function setProvider (OAuth2Provider $provider)
	{
		$this->provider = $provider;
	}
	
	protected function getHTTPAuthorization ()
	{
		$authorizationHdr = Request::getHeader('Authorization');
		
		if($authorizationHdr !== null) 
		{
			$parts = explode(' ', $authorizationHdr);
			
			if(!isset($parts[0])) 
			{
				return null;
			}
			
			$parts[0] = strtolower($parts[0]);
			
			if($parts[0] === 'basic' && isset($parts[1])) 
			{
				$credentials = explode(':', base64_decode($parts[1]));
				
				if(count($credentials) === 2) 
				{
					return array
					(
						'type' => 'basic',
						'credentials' => $credentials
					);
				}
			}
			else if(($parts[0] === 'bearer' || $parts[0] === 'mac') && isset($parts[1]))
			{
				return array
				(
					'type' => $parts[0],
					'token' => $parts[1]
				);
			}
		}
		
		return null;
	}
	
}

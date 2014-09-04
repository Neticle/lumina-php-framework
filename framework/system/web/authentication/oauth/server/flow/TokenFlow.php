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

use system\web\authentication\oauth\server\component\OAuth2Provider;
use system\web\Request;

/**
 * Description of TokenFlow
 *
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
abstract class TokenFlow extends Flow
{
    
	public static function getRequestingClient (OAuth2Provider $provider)
	{
		$clientId = Request::getString('client_id', $_GET, false, null);

		if ($clientId === null)
		{
			return null;
		}

		return $provider->getStorage()->fetchClient($clientId);
	}
	
	protected function onBeforeValidate () {
		return true;
	}
	
	protected function onBeforeGrant () {
		return true;
	}
	
	public abstract function prepare ();
	
	public abstract function validate ();
	
	public abstract function grant ();
	
	public final function handle () {
		return ($this->prepare() && $this->validate() && $this->grant());
	}
	
}

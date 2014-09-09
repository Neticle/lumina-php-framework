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

namespace system\http;

/**
 * Representation of an HTTP message.
 * 
 * @author Igor Azevedo <igor.azevedo@neticle.pt>
 */
interface IMessage
{
	
	/**
	 * Gets all set headers as an associative array paired as key => value.
	 * 
	 * @return array
	 */
	public function getHeaders ();
	
	/**
	 * Gets an header's value, given it's key.
	 * 
	 * @param string $key
	 *  The header's key (name).
	 * 
	 * @return string|null
	 *  The header's value, if any.
	 */
	public function getHeader ($key);
	
	/**
	 * Gets the message's body contents, if any.
	 * 
	 * @return string|null
	 */
	public function getBody ();
	
	/**
	 * Gets the length of the body contents.
	 * 
	 * @return int
	 */
	public function getLength ();
	
}
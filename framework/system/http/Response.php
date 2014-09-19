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

class Response extends Message implements IResponse
{
	private $code;
	
	public function __construct($code, $headers, $body, $configuration = null) {
		parent::__construct($configuration);
		
		$this->setCode($code);
		$this->setHeaders($headers);
		$this->setBody($body);
	}
	
	public function getCode ()
	{
		return $this->code;
	}

	protected function setCode ($code)
	{
		$this->code = intval($code);
	}
	
	public function getContentType ($raw = false)
	{
		$contentType = $this->getHeader('Content-Type');
		
		if($raw || $contentType === null)
		{
			return $contentType;
		}
		
		$contentType = explode(';', $contentType);
		
		return trim($contentType[0]);
	}
	
	public function compareContentType ($comparison)
	{
		$contentType = $this->getContentType();
		
		return $contentType === null ? false : (strtolower($contentType) === strtolower($comparison)); 
	}
	
}
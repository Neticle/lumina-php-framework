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

namespace system\i18n\dictionary;

use system\i18n\dictionary\Dictionary;

/**
 * The static dictionary works through a associative array containing all
 * texts for all domains.
 *
 * This component is obviously not suitable for production usage on large
 * projects due to it's performance impact.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class StaticDictionary extends Dictionary
{
	/**
	 * The dictionary texts, indexed by locale, domain and identifier.
	 *
	 * @type array
	 */
	private $texts;
	
	/**
	 * Defines the dictionary texts.
	 *
	 * @param array $texts
	 *	The dictionary texts.
	 */
	public function setTexts(array $texts)
	{
		$this->texts = $texts;
	}
	
	/**
	 * Returns the dictionary texts.
	 *
	 * @return array
	 *	The dictionary texts.
	 */
	public function getTexts()
	{
		return $this->texts;
	}
	
	/**
	 * Fetches text of a specific domain.
	 *
	 * @param string $domain
	 *	The domain to fetch the text of.
	 *
	 * @param string $identifier
	 *	The unique text identifier.
	 *
	 * @return string
	 *	The fetched text if found, the identifier otherwise.
	 */
	protected function fetchDomainText($domain, $identifier)
	{
		$locale = $this->getLocale();
		
		return isset($this->texts[$locale][$domain][$identifier]) ?
			$this->texts[$locale][$domain][$identifier] : $identifier;
	}
}


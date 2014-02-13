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

use \system\base\Component;

/**
 * The Dictionary component has the only purpose of providing text translations
 * and number formatting.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.global.dictionary
 * @since 0.2.0
 */
abstract class Dictionary extends Component
{
	/**
	 * The current dictionary locale identifier.
	 *
	 * @type string
	 */
	private $locale;
	
	/**
	 * The default dictionary domain.
	 *
	 * @type string
	 */
	private $domain = 'default';
	
	/**
	 * The formatters instances, indexed by type.
	 *
	 * @type array
	 */
	private $formatters;
	
	/**
	 * Changes the dictionary locale.
	 *
	 * @param string $locale
	 *	The locale to change the dictionary to.
	 */
	public final function setLocale($locale)
	{
		$this->onLocaleChange($this->locale, $locale);
		$this->locale = $locale;
		$this->formatters = null;
	}
	
	/**
	 * Returns the current locale.
	 *
	 * @return string
	 *	The current locale.
	 */
	public final function getLocale()
	{
		return $this->locale;
	}
	
	/**
	 * This method encapsulates the 'localeChange' event.
	 *
	 * @param string $previous
	 *	The previously loaded locale.
	 *
	 * @param string $locale
	 *	The locale the dictionary is changing to.
	 *
	 * @return bool
	 *	Returns TRUE to continue the event, FALSE otherwise.
	 */
	protected function onLocaleChange($previous, $locale)
	{
		return $this->raiseArray('localeChange', array($previous, $locale));
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
	protected abstract function fetchDomainText($domain, $identifier);
	
	/**
	 * Returns text of a specific domain.
	 *
	 * @param string $domain
	 *	The domain to get the text from.
	 *
	 * @param string $identifier
	 *	The unique text identifier.
	 *
	 * @param array $parameters
	 *	An associative array defining the value of the text named parameters.
	 *
	 * @return string
	 *	The text if found, the identifier otherwise.
	 */
	public final function getDomainText($domain, $identifier, array $parameters = null)
	{
		$text = $this->fetchText($domain, $identifier);
		
		if (isset($parameters))
		{
			$search = array();
			$replace = array();
			
			foreach ($parameters as $index => $value)
			{
				$search[] = '{' . $index . '}';
				$replace[] = $value;
			}
			
			$text = str_replace($search, $replace, $text);
		}
		
		return $text;
	}
	
	/**
	 * Returns text of the currently active domain.
	 *
	 * @param string $identifier
	 *	The unique text identifier.
	 *
	 * @param array $parameters
	 *	An associative array defining the value of the text named parameters.
	 *
	 * @return string
	 *	The text if found, the identifier otherwise.
	 */
	public final function getText($identifier, array $parameters = null)
	{
		return $this->getDomainText($this->domain, $identifier, $parameters);
	}
	
	/**
	 * Returns the dictionary number formatter instance.
	 *
	 * @return \NumberFormatter
	 *	The decimal number formatter instance.
	 */
	public final function getNumberFormatter()
	{
		if (!isset($this->formatters['number']))
		{
			$this->formatters['number'] = 
				new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL);
		}
		
		return $this->formatters['number'];
	}
	
	/**
	 * Returns the dictionary number formatter instance.
	 *
	 * @return \NumberFormatter
	 *	The currency number formatter instance.
	 */
	public final function getCurrencyFormatter()
	{
		if (!isset($this->formatters['currency']))
		{
			$this->formatters['currency'] = 
				new \NumberFormatter($this->locale, \NumberFormatter::CURRENCY);
		}
		
		return $this->formatters['currency'];
	}
	
	/**
	 * Returns a formatted message of the currently active domain.
	 *
	 * @param string $identifier
	 *	The unique message identifier.
	 *
	 * @param array $parameters
	 *	The message parameter values.
	 *
	 * @return string
	 *	The formatted message.
	 */
	public final function getMessage($identifier, array $parameters = null)
	{
		return $this->getDomainMessage($this->domain, $identifier, $parameters);
	}
	
	/**
	 * Returns a formatted message of the specified domain.
	 *
	 * @param string $domain
	 *	The domain to get the message of.
	 *
	 * @param string $identifier
	 *	The unique message identifier.
	 *
	 * @param array $parameters
	 *	The message parameter values.
	 *
	 * @return string
	 *	The formatted message.
	 */
	public final function getDomainMessage($domain, $identifier, array $parameters = null)
	{
		$text = $this->fetchDomainText($domain, $identifier);
		return \MessageFormatter::formatMessage($this->locale, $text, $parameters);
	}
	
	/**
	 * Formats and returns a number as a string.
	 *
	 * @param numeric $value
	 *	The value to format.
	 *
	 * @param int $precision
	 *	The number of decimals to present.
	 *
	 * @return string
	 *	The formatted number.
	 */
	public function getNumber($value, $precision = null)
	{
		$formatter = $this->getNumberFormatter();
		
		if (isset($precision))
		{
			$formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $precision);
			$formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $precision);
		}
		else
		{
			$formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 0);
			$formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 10);
		}
		
		return $formatter->format($value);
	}
	
	/**
	 * Formats and returns a number as a currency string.
	 *
	 * @param numeric $value
	 *	The value to format.
	 *
	 * @param string $currency
	 *	The 3-letter ISO 4217 currency code indicating the currency to use or
	 *	NULL to use the default currency for the current language and region.
	 *
	 * @param int $precision
	 *	The number of decimals to present.
	 *
	 * @return string
	 *	The formatted number.
	 */
	public function getCurrency($value, $currency = null, $precision = 2)
	{
		$formatter = $this->getCurrencyFormatter();
		
		if (isset($precision))
		{
			$formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $precision);
			$formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $precision);
		}
		else
		{
			$formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 0);
			$formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 10);
		}
		
		if (isset($currency))
		{
			return $formatter->formatCurrency($value, strtoupper($currency));
		}
		
		return $formatter->format($value);
	}
}


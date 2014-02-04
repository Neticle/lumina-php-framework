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

namespace system\data;

use \system\core\Element;

/**
 * Models allow you to safely handle request input data by ensuring it's
 * structure and format.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.base
 * @since 0.2.0
 */
abstract class Model
{
	/**
	 * The model attribute values, indexed by name.
	 *
	 * @type array
	 */
	private $attributes;
	
	/**
	 * The model validation context.
	 *
	 * @type string
	 */
	private $context;
	
	/**
	 * The model validation and binding rules.
	 *
	 * @type Rule[]
	 */
	private $rules;
	
	/**
	 * Constructor.
	 *
	 * @param string $context
	 *	The initial model context.
	 *
	 * @param array $attributes
	 *	An associative array containing the initial attribute values.
	 */
	public function __construct($context = 'default', array $attributes = null)
	{
		$this->context = $context;
		$this->attributes = (array) $attributes;
	}
	
	/**
	 * Defines the new context for this model.
	 *
	 * @param string $context
	 *	The new model validation context.
	 *
	 * @param bool $reset
	 *	When set to TRUE any previously defined or bound attribute values
	 *	will be removed.
	 */
	public function setContext($context, $reset = false)
	{
		$this->context = $context;
		
		if ($reset)
		{
			$this->attributes = array();
		}
	}
	
	/**
	 * Returns the current model validation context.
	 *
	 * @return string
	 *	The model validation context.
	 */
	public function getContext()
	{
		return $this->context;
	}
	
	/**
	 * Defines the value for the model attributes.
	 *
	 * @param array $attributes
	 *	An associative array defining the attribute values, indexed by name.
	 */
	public function setAttributes(array $attributes)
	{
		$this->attributes = $attributes;
	}
	
	/**
	 * Returns the value for each defined attribute, indexed by name.
	 *
	 * @return array
	 *	The model attribute values, indexed by name.
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}
	
	/**
	 * Returns the model validation rules.
	 *
	 * @return array
	 *	The model validation rules. Each rule can be described as a rule
	 *	construction array or an instance of Rule.
	 */
	protected function getValidationRules()
	{
		return array();
	}
	
	public function getValidationRuleInstances()
	{
		if (!isset($this->rules))
		{
			foreach ($this->getValidationRules() as $rule)
			{
				$rule
			}
		}
		
		return $this->rules;
	}
}


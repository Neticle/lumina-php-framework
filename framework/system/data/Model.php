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
use \system\data\validation\Rule;

/**
 * Models allow you to safely handle request input data by ensuring it's
 * structure and format.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.base
 * @since 0.2.0
 */
abstract class Model extends Element
{
	/**
	 * The model attribute values, indexed by name.
	 *
	 * @type array
	 */
	private $attributes;
	
	/**
	 * The model attribute names.
	 *
	 * @type string[]
	 */
	private $attributeNames;
	
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
	 * An associative array defining the attribute validation error
	 * messages, indexed by name.
	 *
	 * @type array
	 */
	private $errors = array();
	
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
	 * Resets all attribute values and reported error messages.
	 */
	public function reset()
	{
		$this->attributes = array();
		$this->errors = array();
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
	public final function setContext($context, $reset = false)
	{
		$this->context = $context;
		
		if ($reset)
		{
			$this->reset();
		}
	}
	
	/**
	 * Returns the current model validation context.
	 *
	 * @return string
	 *	The model validation context.
	 */
	public final function getContext()
	{
		return $this->context;
	}
	
	/**
	 * Defines the value for the model attributes.
	 *
	 * @param array $attributes
	 *	An associative array defining the attribute values, indexed by name.
	 */
	public final function setAttributes(array $attributes)
	{
		$this->attributes = $attributes;
	}
	
	/**
	 * Defines the value for an attribute.
	 *
	 * @param string $attribute
	 *	The name of the attribute to define the value for.
	 *
	 * @param mixed $value
	 *	The attribute value.
	 */
	public final function setAttribute($attribute, $value)
	{
		$this->attributes[$attribute] = $value;
	}
	
	/**
	 * Returns the value for an attribute.
	 *
	 * @param string $attribute
	 *	The name of the attribute to get the value of.
	 *
	 * @return mixed
	 *	The attribute value.
	 */
	public final function getAttribute($attribute)
	{
		return isset($this->attributes[$attribute]) ?
			$this->attributes[$attribute] : null;
	}
	
	/**
	 * Binds the attributes to the model instance.
	 *
	 * This method is similar to "setAttributes" with the exception unsafe
	 * attributes will be ignored.
	 *
	 * @param array $attributes
	 *	The values to be bound, indexed by name.
	 */
	public final function bindAttributes(array $attributes)
	{
		$safe = $this->getSafeAttributeNames();
		
		foreach ($attributes as $name => $value)
		{
			if (in_array($name, $safe))
			{
				$this->attributes[$name] = $value;
			}
		}
	}
	
	/**
	 * Returns the value for each attribute, indexed by name.
	 *
	 * @param bool $absolute
	 *	When set to TRUE all attributes defined by the model validation rules
	 *	will be present in the returned array as NULL if they are not
	 *	explicitly defined.
	 *
	 * @return array
	 *	The model attribute values, indexed by name.
	 */
	public final function getAttributes($absolute = false)
	{
		if ($absolute)
		{
			$attributes = $this->attributes;
			
			foreach ($this->getAttributeNames() as $name)
			{
				if (!isset($attributes[$name]))
				{
					$attributes[$name] = null;
				}
			}
			
			return $attributes;
			
		}
		
		return $this->attributes;
	}
	
	/**
	 * Returns the names of all attributes defined by the model validation
	 * rules as an array of strings.
	 *
	 * @return string[]
	 *	The model attribute names.
	 */
	public final function getAttributeNames()
	{
		if (!isset($this->attributeNames))
		{
			$names = array();
		
			foreach ($this->getValidationRuleInstances() as $rule)
			{
				$names = array_merge($names, $rule->getAttributes());
			}
			
			$this->attributeNames = array_unique($names);
		}
		
		return $this->attributeNames;		
	}
	
	/**
	 * Returns an associative array defining the attribute labels, 
	 * indexed by name.
	 *
	 * @return array
	 *	The attribute labels.
	 */
	public function getAttributeLabels()
	{
		return array();
	}
	
	/**
	 * Returns the label of an attribute.
	 *
	 * @param string $attribute
	 *	The attribute to get the label for.
	 *
	 * @return string
	 *	The attribute label. If a label is not defined, it will be built
	 *	based on the attribute name.
	 */
	public function getAttributeLabel($attribute)
	{
		$labels = $this->getAttributeLabels($attribute);
		
		if (isset($labels[$attribute]))
		{
			return $labels[$attribute];
		}
		
		return ucwords(str_replace(array('-', '_'), ' ', $attribute));
	}
	
	/**
	 * Returns the names of all safe attributes defined by the model validation
	 * rules applicable to a context, as an array of strings.
	 *
	 * @param string $context
	 *	A context to be verified. If no value is given the current model
	 *	context will be used.
	 *
	 * @return string[]
	 *	The model safe attribute names.
	 */
	public final function getSafeAttributeNames($context = null)
	{
		if (!isset($context))
		{
			$context = $this->context;
		}
	
		$unsafe = array();
		$attributes = array();
		
		foreach ($this->getValidationRuleInstances() as $rule)
		{
			if ($rule->appliesToContext($context))
			{
				$attributes = array_merge($rule->getAttributes(), $attributes);
				
				if (!$rule->isSafe())
				{
					$unsafe = array_merge($unsafe, $rule->getAttributes());
				}
			}
		}
		
		$unsafe = array_unique($unsafe);
		$attributes = array_unique($attributes);
		$safe = array_diff($attributes, $unsafe);
		return $safe;
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
	
	/**
	 * Returns the model validation rules as an array of objects.
	 *
	 * @return Rule[]
	 *	The model validation rules.
	 */
	public final function getValidationRuleInstances()
	{
		if (!isset($this->rules))
		{
			$collection = array();
			
			foreach ($this->getValidationRules() as $rule)
			{
				if (!($rule instanceof Rule))
				{
					$rule = Rule::fromRuleConstructionArray($rule);
				}
				
				$collection[] = $rule;
			}
			
			$this->rules = $collection;
		}
		
		return $this->rules;
	}
	
	/**
	 * Validates the model attributes.
	 *
	 * @param string[] $attributes
	 *	The names of the attributes to limit the validation to.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE on error.
	 */
	public function validate(array $attributes = null)
	{
		// Determine the attributes to validate
		$names = $this->getAttributeNames();
		
		if (isset($attributes))
		{			
			$names = array_intersect($names, $attributes);
		}
		
		if ($this->onBeforeValidation($names))
		{
			$result = $this->onValidation($names);
		
			// Process each validation rule in order
			foreach ($this->getValidationRuleInstances() as $rule)
			{
				if (!$rule->validate($this, $names))
				{
					$result = false;
				}
			}
		}
		else
		{
			$result = false;
		}
		
		$this->onAfterValidation($names, $result);
		return $result;
	}
	
	/**
	 * Registers a new attribute validation error.
	 *
	 * @param string $attribute
	 *	The name of the attribute to register the error for.
	 *
	 * @param string $message
	 *	The error message to register.
	 */
	public function addAttributeError($attribute, $message)
	{
		$this->errors[$attribute][] = $message;
	}
	
	/**
	 * Returns all attribute validation errors, optionally filtered
	 * by attribute names.
	 *
	 * @param string|string[] $attributes
	 *	The name(s) of the attribute(s) to get the error messages for, either
	 *	as a CSV string or an array of strings.
	 *
	 * @return array
	 *	The attribute error messages, indexed by name.
	 */
	public function getAttributeErrors($attributes = null)
	{
		if (isset($attributes))
		{
			if (is_string($attributes))
			{
				$attributes = preg_split('(\s*\,\s*)', $attributes, -1, PREG_SPLIT_NO_EMPTY);
			}
			
			return array_intersect_keys($this->attributeErrors, array_flip($attributes));
		}
		
		return $this->errors;
	}
	
	/**
	 * Returns all attribute validation errors, optionally filtered
	 * by attribute names.
	 *
	 * Unlike 'getAttributeErrors', only the error messages will be returned.
	 *
	 * @param string|string[] $attributes
	 *	The name(s) of the attribute(s) to get the error messages for, either
	 *	as a CSV string or an array of strings.
	 *
	 * @return string[]
	 *	The attribute error messages.
	 */
	public function getAttributeErrorMessages($attributes = null)
	{
		if (isset($attributes))
		{
			if (is_string($attributes))
			{
				$attributes = preg_split('(\s*\,\s*)', $attributes, -1, PREG_SPLIT_NO_EMPTY);
			}
			
			$errors = array_intersect_keys($this->attributeErrors, array_flip($attributes));
		}
		else
		{
			$errors = $this->errors;
		}
		
		$messages = array();
		
		foreach ($errors as $attribute => $attributeMessages)
		{
			$messages = array_merge($messages, $attributeMessages);
		}
		
		return array_unique($messages);
	}
	
	/**
	 * This method encapsulates the 'validation' event.
	 *
	 * Canceling this event will cause the validation to fail, thus FALSE
	 * being returned when 'validate' function is called. This will not stop
	 * the configured rules from being processed.
	 *
	 * @param string[] $attributes
	 *	The name of the attributes being validated.
	 *
	 * @return bool
	 *	Returns FALSE to cancel the validation event, FALSE otherwise.
	 */
	protected function onValidation(array $attributes)
	{
		return $this->raiseArray('validation', array($attributes));
	}
	
	/**
	 * This method encapsulates the 'beforeValidation' event.
	 *
	 * Canceling this event will cause the validation to fail, thus FALSE
	 * being returned when 'validate' function is called. This will stop
	 * the configured rules from being processed.
	 *
	 * @param string[] $attributes
	 *	The name of the attributes being validated.
	 *
	 * @return bool
	 *	Returns FALSE to cancel the validation event, FALSE otherwise.
	 */
	protected function onBeforeValidation(array $attributes)
	{
		return $this->raiseArray('beforeValidation', array($attributes));
	}
	
	/**
	 * This method encapsulates the 'afterValidation' event.
	 *
	 * @param string[] $attributes
	 *	The name of the attributes being validated.
	 *
	 * @return bool
	 *	Returns FALSE to cancel the validation event, FALSE otherwise.
	 */
	protected function onAfterValidation(array $attributes, $result)
	{
		return $this->raiseArray('afterValidation', array($attributes, $result));
	}
}


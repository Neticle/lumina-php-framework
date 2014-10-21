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
use \system\core\exception\RuntimeException;
use \system\data\validation\Rule;

/**
 * Models allow you to safely handle request input data by ensuring it's
 * structure and format.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
abstract class Model extends Element implements IValidatableDataContainer
{
	/**
	 * Cached model instances, indexed by class name.
	 *
	 * @type array
	 */
	private static $modelInstances = [];
	
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
	 * An associative array defining the attribute validation error
	 * messages, indexed by name.
	 *
	 * @type array
	 */
	private $attributeErrors = [];
	
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
	 * The name of this model instance, as used by several widgets and
	 * helper classes when generating input fields to collect data based
	 * on this model.
	 *
	 * @type string
	 */
	private $name;
	
	/**
	 * Returns base instance of a model of the specified class after
	 * resetting it to the given context.
	 *
	 * This function will re-use a model instance that was previously
	 * created for this purpose, which means you can not use a nested
	 * instance of the same class.
	 *
	 * If nested instances of the same model are required you can simply
	 * create them yourself (new MyModel($context, ...)).
	 *
	 * This is simply an utility method that needs to be reflected by each
	 * model class, allowing you to use your models without having to
	 * manually create a new instance each time.
	 *
	 * @param string $class
	 *	The name of the class to get the model of.
	 *
	 * @param string $context
	 *	The context to reset the model to.
	 *
	 * @return Model
	 *	The created model instance.
	 */
	public static function getBaseModel($class = null, $context = 'default')
	{
		if (isset($class))
		{
			if (isset(self::$modelInstances[$class]))
			{
				$instance = self::$modelInstances[$class];
				$instance->setContext($context);
			}
			else
			{
				$instance = new $class($context);
				self::$modelInstances[$class] = $instance;
			}
			
			return $instance;
		}
		
		throw new RuntimeException('Undefined model class.');
	}
	
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
		$this->attributes = [];
		$this->attributeErrors = [];
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
		if (isset($this->attributes[$attribute]))
		{
			return $this->attributes[$attribute];
		}
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
				$this->attributes[$name] = $value === '' ?
					null : $value;
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
			$names = [];
		
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
		return [];
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
		
		return ucwords(str_replace([ '-', '_' ], ' ', $attribute));
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
	
		$unsafe = [];
		$attributes = [];
		
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
		return [];
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
			$collection = [];
			
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
		$this->attributeErrors = [];
		
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
		
		return ($result & $this->onAfterValidation($names)) 
			&& empty($this->attributeErrors);
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
		$this->attributeErrors[$attribute][] = $message;
	}
	
	/**
	 * Returns all attribute validation errors, optionally filtered
	 * by attribute names.
	 *
	 * @param string[]|string $attributes
	 *	The name(s) of the attribute(s) to get the error messages for.
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
				$attributes = preg_split('/(\s*\,\s*)/', $attributes, -1, PREG_SPLIT_NO_EMPTY);
			}
		
			return array_intersect_key($this->attributeErrors, array_flip($attributes));
		}
		
		return $this->attributeErrors;
	}
	
	/**
	 * Returns all attribute validation errors, optionally filtered
	 * by attribute names.
	 *
	 * Unlike 'getAttributeErrors', only the error messages will be returned.
	 *
	 * @param string[]|string $attributes
	 *	The name(s) of the attribute(s) to get the error messages for.
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
				$attributes = preg_split('/(\s*\,\s*)/', $attributes, -1, PREG_SPLIT_NO_EMPTY);
			}
		
			$errors = array_intersect_key($this->attributeErrors, array_flip($attributes));
		}
		else
		{
			$errors = $this->attributeErrors;
		}
		
		$messages = [];
		
		foreach ($errors as $attribute => $attributeMessages)
		{
			$messages = array_merge($messages, $attributeMessages);
		}
		
		return array_unique($messages);
	}
	
	/**
	 * Checks if an attribute has validation errors reported for it.
	 *
	 * @param string[]|string $attributes
	 *	The name(s) of the attribute(s) to  to verify.
	 *
	 * @return bool
	 *	Returns TRUE if the attribute has reported validation errors,
	 *	FALSE otherwise.
	 */
	public function hasAttributeErrors($attributes = null)
	{
		if (isset($attributes))
		{
			if (is_string($attributes))
			{
				$attributes = preg_split('/(\s*\,\s*)/', $attributes, -1, PREG_SPLIT_NO_EMPTY);
			}
		
			$errors = array_intersect_key($this->attributeErrors, array_flip($attributes));
		}
		else
		{
			$errors = $this->attributeErrors;
		}
		
		return !empty($errors);
	}
	
	/**
	 * Defines the name of this model instance, as used by several widgets and
	 * helper classes when generating input fields to collect data based
	 * on this model.
	 *
	 * @param string $name
	 *	The model instance name.
	 */
	public final function setName($name)
	{
		if (isset($this->name))
		{
			throw new RuntimeException('Model instance name has already been defined.');
		}
		
		$this->name = $name;
	}
	
	/**
	 * Returns the name of this model instance, as used by several widgets and
	 * helper classes when generating input fields to collect data based
	 * on this model.
	 *
	 * If a name isn't already defined, it will be set based on the model
	 * class.
	 *
	 * @return string
	 *	The model instance name.
	 */
	public final function getName()
	{
		if (!isset($this->name))
		{
			$this->name = $this->getClass(false);
		}
		
		return $this->name;
	}
	
	/**
	 * Returns the name of an attribute for this instance, , as used by several
	 * widgets and helper classes when generating input fields to collect.
	 *
	 * @param string $attribute
	 *	The attribute to get the name of.
	 *
	 * @return string
	 *	The attribute name.
	 */
	public final function getAttributeName($attribute)
	{
		return $this->getName() . '[' . $attribute . ']';
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
	 *	Returns FALSE to cancel the validation event, TRUE otherwise.
	 */
	protected function onValidation(array $attributes)
	{
		return $this->raiseArray('validation', [ $attributes ]);
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
	 *	Returns FALSE to cancel the validation event, TRUE otherwise.
	 */
	protected function onBeforeValidation(array $attributes)
	{
		return $this->raiseArray('beforeValidation', [ $attributes ]);
	}
	
	/**
	 * This method encapsulates the 'afterValidation' event.
	 *
	 * @param string[] $attributes
	 *	The name of the attributes being validated.
	 *
	 * @return bool
	 *	Returns FALSE to cancel the validation event, TRUE otherwise.
	 */
	protected function onAfterValidation(array $attributes)
	{
		return $this->raiseArray('afterValidation', [ $attributes ]);
	}
	
	/**
	 * Returns the value of a virtual property.
	 *
	 * The base implementation considers all model attributes virtual properties
	 * and has the same behaviour as 'getAttribute'.
	 *
	 * @param string $property
	 *	The name of the property to return.
	 *
	 * @return mixed
	 *	The property value, or NULL.
	 */
	public function __get($property)
	{
		return isset($this->attributes[$property]) ?
			$this->attributes[$property] : null;
	}
	
	/**
	 * Defines the value of a virtual property.
	 *
	 * The base implementation considers all model attributes virtual properties
	 * and has the same behavior as 'setAttribute'.
	 *
	 * @param string $property
	 *	The name of the property to define.
	 *
	 * @param mixed $value
	 *	The value to define the property with.
	 */
	public function __set($property, $value)
	{
		$this->attributes[$property] = $value;
	}
	
	/**
	 * Checks wether or not a value is defined for a virtual property.
	 *
	 * The base implementation considers all model attributes
	 * virtual properties.
	 *
	 * @param string $property
	 *	The name of the property to verify.
	 *
	 * @return bool
	 *	Returns TRUE if the property is defined and not NULL, FALSE otherwise.
	 */
	public function __isset($property)
	{
		return isset($this->attributes[$property]);
	}
	
	/**
	 * Clears the definition of a virtual property value.
	 *
	 * The base implementation considers all model attributes
	 * virtual properties.
	 *
	 * @param string $property
	 *	The name of the property to remove the definition of.
	 */
	public function __unset($property)
	{
		unset($this->attributes[$property]);
	}
}


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

namespace system\data\validation;

use \system\core\Express;

/**
 * A Rule is used to validate values previously bound to a model instance in
 * order to make sure said values are safe for use.
 *
 * Rules are not limited to making sure it has the appropriate format. In fact,
 * rules are designed to modify the value to a more suitable format when
 * applicable.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.data.validation
 * @since 0.2.0
 */
abstract class Rule extends Express
{
	/**
	 * Holds the class names for rule aliases.
	 *
	 * @type array
	 */
	private static $ruleClasses = array(
		'numeric' => 'system\\data\\validation\\NumericRule',
		'range' => 'system\\data\\validation\\RangeRule',
		'email' => 'system\\data\\validation\\EmailRule',
		'length' => 'system\\data\\validation\\LengthRule',
		'required' => 'system\\data\\validation\\RequiredRule',
		'safe' => 'system\\data\\validation\\SafeRule',
		'unsafe' => 'system\\data\\validation\\UnsafeRule',
		'enum' => 'system\\data\\validation\\EnumRule',
		'unique' => 'system\\data\\validation\\UniqueRule',
		'reference' => 'system\\data\\validation\\ReferenceRule'
	);

	/**
	 * An array containing the names of the attributes this validation
	 * rule applies to.
	 *
	 * @type string[]
	 */
	private $attributes;
	
	/**
	 * An array containing the contexts this validation rule applies to.
	 *
	 * @type string[]
	 */
	private $contexts;
	
	/**
	 * The message to be reported back to the model when one of the attributes
	 * fails validation due to it being empty when a value is required.
	 *
	 * @type string
	 */
	protected $message = 'Attribute "{attribute}" does not have a valid format.';
	
	/**
	 * A flag indicating wether or not the attribute value is required in
	 * order to pass validation.
	 *
	 * @type bool
	 */
	protected $required = false;
	
	/**
	 * A flag indicating wether or not the attribute value is safe for
	 * massive assignment.
	 *
	 * @type bool
	 */
	protected $safe = false;
	
	/**
	 * Returns a rule instance based on a rule construction array.
	 *
	 * @param array $rule
	 *	An array containing the class or alias, attributes and express
	 *	configuration data, as described in the three examples bellow:
	 *
	 *	1. array('required', 'attr1,attr2,attr3', 'context' => 'insert')
	 *	2. array('MyCustomRule', 'attr1,attr2')
	 *	3. array('required', array('attr1', 'attr2'), 'context' => 'insert')
	 */
	public static function fromRuleConstructionArray(array $rule)
	{
		$class = $rule[0];
		$attributes = $rule[1];
		
		if (is_string($attributes))
		{
			$attributes = preg_split('/(\s*\,\s*)/', $attributes, -1, PREG_SPLIT_NO_EMPTY);
		}
		
		if (isset(self::$ruleClasses[$class]))
		{
			$class = self::$ruleClasses[$class];
		}
		
		return new $class($class, $attributes, $rule);
	}
	
	/**
	 * Constructor.
	 *
	 * @param string[] $attributes
	 *	An array containing the names of the attributes this validation
	 *	rule applies to.
	 *
	 * @param array $configuration
	 *	The rule express configuration array.
	 */
	public final function __construct(array $attributes, array $configuration = null)
	{
		parent::__construct($configuration);
		$this->attributes = $attributes;
	}
	
	/**
	 * Checks wether or not this rule applies to a context.
	 *
	 * @param string $context
	 *	The context to check the rule against.
	 *
	 * @return bool
	 *	Returns TRUE if the rule applies, FALSE otherwise.
	 */
	public function appliesToContext($context)
	{
		return !$this->contexts || in_array($context, $this->contexts);
	}
	
	/**
	 * Checks wether or not this rule applies to an attribute.
	 *
	 * @param string $attribute
	 *	The attribute to check the rule against.
	 *
	 * @return bool
	 *	Returns TRUE if the rule applies, FALSE otherwise.
	 */
	public function appliesToAttribute($attribute)
	{
		return in_array($attribute, $this->attributes);
	}
	
	/**
	 * Defines the message to be reported back to the model when one of the 
	 * attributes fails validation due to it being empty when a value 
	 * is required.
	 *
	 * @param string $message
	 *	The message to be reported back to the model if a validation
	 *	error is encountered.
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}
	
	/**
	 * Returns the message to be reported back to the model when one of the 
	 * attributes fails validation due to it being empty when a value 
	 * is required.
	 *
	 * @return string
	 *	The message to be reported back to the model if a validation
	 *	error is encountered.
	 */
	public function getMessage()
	{
		return $this->message;
	}
	
	/**
	 * Checks wether or not attributes affected by this rule are safe
	 * for massive assignment.
	 *
	 * @return bool
	 *	Returns TRUE if the attributes are safe, FALSE otherwise.
	 */
	public function isSafe()
	{
		return $this->safe;
	}
	
	/**
	 * Checks wether or not attributes affected by this rule are required
	 * in order to successfully validate.
	 *
	 * @return bool
	 *	Returns TRUE if the attributes are safe, FALSE otherwise.
	 */
	public function isRequired()
	{
		return $this->required;
	}
	
	/**
	 * Validates a model attribute value.
	 *
	 * @param Model $model
	 *	The model being validated.
	 *
	 * @param string $attribute
	 *	The name of the attribute being validated.
	 *
	 * @param mixed $value
	 *	The value being validated.
	 *
	 * @return bool
	 *	Returns TRUE if the attribute value is valid, FALSE otherwise.
	 */
	protected function validateAttributeValue(Model $model, $attribute, $value)
	{
		if (empty($value) && $this->required)
		{
			$success = false;
			$this->report($model, $attribute);
			return false;
		}
		
		return true;
	}
	
	/**
	 * Runs this validation rule against the given model.
	 *
	 * @param Model $model
	 *	The model being validated.
	 *
	 * @param string[] $attributes
	 *	The names of the attributes to validate.
	 *
	 *	Please note that this rule will only validate the attribute it applies
	 *	to, ignoring any extra arguments given in this array.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE on failure.
	 */
	public final function validate(Model $model, array $attributes = null)
	{
		$context = $model->getContext();
		
		if (!$this->contexts || in_array($context, $this->contexts))
		{
			// Determine the attributes to validate
			$attributes = $attributes ?
				array_intersect($this->attributes, $attributes) : $this->attributes;
		
			$values = $model->getAttributes();
			$success = true;
		
			foreach ($attributes as $attribute)
			{
				// The attribute value
				$value = isset($values[$attribute]) ?
					$values[$attribute] : null;
			
				// Validate the attribute individually
				if (!$this->validateAttributeValue($model, $attribute, $value))
				{
					$success = false;
				}
			
			}
			
			return $success;
		}
		
		return true;
	}
	
	/**
	 * Reports a validation error message.
	 *
	 * @param Model $model
	 *	The model to report the error message to.
	 *
	 * @param string $attribute
	 *	The attribute the message is related to.
	 *
	 * @param array $parameters
	 *	An associative array containing additional parameters to be used
	 *	by the error message.
	 */
	protected final function report(Model $model, $attribute, array $parameters = null)
	{
		$search = array('{attribute}', '{attribute-name}');
		$replace = array($model->getAttributeLabel($attribute), $attribute);
		
		if (isset($parameters))
		{
			foreach ($parameters as $key => $value)
			{
				$search[] = '{' . $key . '}';
				$replace[] = $value;
			}
		}
		
		$message = str_replace($search, $replace, $message);	
		$model->addError($attribute, $message);
	}
	
}


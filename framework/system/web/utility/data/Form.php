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

namespace system\web\utility\data;

use \system\core\Element;
use \system\data\Model;
use \system\web\html\HtmlElement;

/**
 * An utility class that allows the developer to quickly create consitent views
 * which purpose is to collect input data to be bound to a model or record
 * instance, as well as present any errors detected during the validation
 * stage.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web
 * @since 0.2.0
 */
class Form extends Element
{
	/**
	 * The unique form identifier.
	 *
	 * @type string
	 */
	private $id = 'form';

	/**
	 * Constructor.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct(array $configuration = null)
	{
		parent::__construct($configuration);
	}
	
	/**
	 * Returns the default id for an input given it name.
	 *
	 * @param string $name
	 *	The name of the input to get the default id for.
	 *
	 * @return string
	 *	The default input id.
	 */
	protected function getDefaultInputId($name)
	{
		return strtolower($this->id . '-input-' . trim(str_replace(array('][', '[', '_', '.'), '-', $name), " \t\n\r\0\x0B]["));
	}
	
	/**
	 * Builds a simple text field element.
	 *
	 * @param string $name
	 *	The name of the input field to build.
	 *
	 * @param string $value
	 *	The initial value for this input field.
	 *
	 * @param array $configuration
	 *	An array of additional express configuration settings to be
	 *	applied to the input field element, after it's construction.
	 *
	 * @return HtmlElement
	 *	The created input field element.
	 */
	protected function buildTextField($name, $value, array $configuration = null)
	{
		$element = new HtmlElement('input');
		$element->setClass(array('lh-form-input', 'lh-form-input-textfield'));
		$element->setAttributes(array(
			'id' => $this->getDefaultInputId($name),
			'name' => $name,
			'type' => 'text',
			'value' => $value
		));
		
		if (isset($configuration))
		{
			$element->configure($configuration);
		}
		
		return $element;
	}
	
	/**
	 * Builds a simple textarea field element.
	 *
	 * @param string $name
	 *	The name of the input field to build.
	 *
	 * @param string $value
	 *	The initial value for this input field.
	 *
	 * @param array $configuration
	 *	An array of additional express configuration settings to be
	 *	applied to the input field element, after it's construction.
	 *
	 * @return HtmlElement
	 *	The created input field element.
	 */
	protected function buildTextArea($name, $value, array $configuration = null)
	{
		$element = new HtmlElement('input');
		$element->setClass(array('lh-form-input', 'lh-form-input-textarea'));
		$element->setAttributes(array(
			'id' => $this->getDefaultInputId($name),
			'name' => $name
		));
		
		if (isset($value))
		{
			$element->setTextContent($value);
		}
		
		if (isset($configuration))
		{
			$element->configure($configuration);
		}
		
		return $element;
	}
	
	/**
	 * Builds a simple hidden field element.
	 *
	 * @param string $name
	 *	The name of the input field to build.
	 *
	 * @param string $value
	 *	The initial value for this input field.
	 *
	 * @param array $configuration
	 *	An array of additional express configuration settings to be
	 *	applied to the input field element, after it's construction.
	 *
	 * @return HtmlElement
	 *	The created input field element.
	 */
	protected function buildHiddenField($name, $value, array $configuration = null)
	{
		$element = new HtmlElement('input');
		$element->setClass(array('lh-form-input', 'lh-form-input-hidden'));
		$element->setAttributes(array(
			'id' => $this->getDefaultInputId($name),
			'name' => $name,
			'type' => 'hidden',
			'value' => $value
		));
		
		if (isset($configuration))
		{
			$element->configure($configuration);
		}
		
		return $element;
	}
	
	/**
	 * Builds a label for a specific input field.
	 *
	 * @param string $for
	 *	The ID of the field the label is meant to.
	 *
	 * @param string $label
	 *	The label text contents.
	 *
	 * @param array $configuration
	 *	An array of additional express configuration settings to be
	 *	applied to the input field element, after it's construction.
	 *
	 * @return HtmlElement
	 *	The created input field element.
	 */
	protected function buildLabel($for, $label, array $configuration = null)
	{
		$element = new HtmlElement('label');
		$element->setClass(array('lh-form-label'));
		$element->setAttribute('for', $for);
		$element->setTextContent($label);
		
		if (isset($configuration))
		{
			$element->configure($configuration);
		}
		
		return $element;
	}
	
	/**
	 * Builds a small report listing the errors for a specific
	 * input field.
	 *
	 * @param string[] $messages
	 *	The messages to be contained in the report.
	 */
	protected function buildInputErrors(array $messages)
	{
		$content = array();
		
		foreach ($messages as $message)
		{
			$li = new HtmlElement('li');
			$li->setClass(array('lh-form-input-error'));
			$li->setTextContent($message);
			$content[] = $li;
		}
		
		$ul = new HtmlElement('ul');
		$ul->setClass(array('lh-form-input-errors'));
		$ul->setContent($content);
		return $ul;
	}
	
	/**
	 * Builds a report listing the validation errors detected across all
	 * input fields defined within the form.
	 *
	 * @param string[] $messages
	 *	The messages to be contained in the report.
	 */
	protected function buildFormErrors(array $messages)
	{
		$content = array();
		
		foreach ($messages as $message)
		{
			$li = new HtmlElement('li');
			$li->setClass(array('lh-form-error'));
			$li->setTextContent($message);
			$content[] = $li;
		}
		
		$ul = new HtmlElement('ul');
		$ul->setClass(array('lh-form-errors'));
		$ul->setContent($content);
		return $ul;
	}
	
	/**
	 * A generic button builder that creates submit and reset buttons.
	 *
	 * @param string $type
	 *	The type of button to build (options: "submit", "reset").
	 *
	 * @param string $label
	 *	The button label.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 *
	 * @return HtmlElement
	 *	The generated html element instance.
	 */
	private function buildGenericButton($type, $label, $configuration)
	{
		$input = new HtmlElement('input');
		$input->setClass(array('lh-form-input', 'lh-form-input-' . $type));
		$input->setAttributes(array(
			'type' => $type,
			'value' => $label
		));
				
		if (isset($configuration))
		{
			$input->configure($configuration);
		}
		
		return $input;
	}
	
	/**
	 * Builds a submit button.
	 *
	 * @param string $label
	 *	The button label.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 *
	 * @return HtmlElement
	 *	The generated html element instance.
	 */
	protected function buildSubmitButton($label, array $configuration = null)
	{
		return $this->buildGenericButton('submit', $label, $configuration);
	}
	
	/**
	 * Builds a reset button.
	 *
	 * @param string $label
	 *	The button label.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 *
	 * @return HtmlElement
	 *	The generated html element instance.
	 */
	protected function buildResetButton($label, array $configuration = null)
	{
		return $this->buildGenericButton('reset', $label, $configuration);
	}
	
	/**
	 * Builds and deploys a text input field.
	 *
	 * @param string $name
	 *	The name of the field to deploy.
	 *
	 * @param string $value
	 *	The initial value for this input field.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 */
	public function textField($name, $value, array $configuration = null)
	{
		return $this->buildTextField($name, $value, $configuration)->render();
	}
	
	/**
	 * Builds and deploys a text input field.
	 *
	 * @param Model $model
	 *	The model to build the input for.
	 *
	 * @param string $attribute
	 *	The model attribute to build the input for.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 */
	public function activeTextField(Model $model, $attribute, array $configuration = null)
	{
		$input = $this->buildTextField(
			$model->getAttributeName($attribute),
			$model->getAttribute($attribute),
			$configuration
		);
	
		if ($model->hasAttributeErrors($attribute))
		{
			$input->setClass('lh-form-input-error');
		}
		
		return $input->render();
	}
	
	/**
	 * Builds and deploys a textarea input field.
	 *
	 * @param string $name
	 *	The name of the field to deploy.
	 *
	 * @param string $value
	 *	The initial value for this input field.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 */
	public function textArea($name, $value, array $configuration = null)
	{
		return $this->buildTextArea($name, $value, $configuration)->render();
	}
	
	/**
	 * Builds and deploys a textarea input field.
	 *
	 * @param Model $model
	 *	The model to build the input for.
	 *
	 * @param string $attribute
	 *	The model attribute to build the input for.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 */
	public function activeTextArea(Model $model, $attribute, array $configuration = null)
	{
		$input = $this->buildTextArea(
			$model->getAttributeName($model, $attribute),
			$model->getAttribute($attribute),
			$configuration
		);
		
		if ($model->hasAttributeErrors($attribute))
		{
			$input->setClass('lh-form-input-error');
		}
		
		return $input;
	}
	
	/**
	 * Builds and deploys a hidden input field.
	 *
	 * @param string $name
	 *	The name of the field to deploy.
	 *
	 * @param string $value
	 *	The initial value for this input field.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 */
	public function hiddenField($name, $value, array $configuration = null)
	{
		return $this->buildHiddenField($name, $value, $configuration)->render();
	}
	
	/**
	 * Builds and deploys a hidden input field.
	 *
	 * @param Model $model
	 *	The model to build the input for.
	 *
	 * @param string $attribute
	 *	The model attribute to build the input for.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 */
	public function activeHiddenField(Model $model, $attribute, array $configuration = null)
	{
		$input = $this->buildHiddenField(
			$model->getAttributeName($attribute),
			$model->getAttribute($attribute),
			$configuration
		);
	
		if ($model->hasAttributeErrors($attribute))
		{
			$input->setClass('lh-form-input-error');
		}
		
		return $input->render();
	}
	
	/**
	 * Builds and deploys a label for a specific input field.
	 *
	 * @param string $for
	 *	The ID of the field the label is meant to.
	 *
	 * @param string $label
	 *	The label text contents.
	 *
	 * @param array $configuration
	 *	An array of additional express configuration settings to be
	 *	applied to the input field element, after it's construction.
	 */
	public function label($for, $message, array $configuration = null)
	{
		return $this->buildLabel($for, $message, $configuration)->render();
	}
	
	/**
	 * Builds and deploys a label for a specific input field.
	 *
	 * @param Model $model
	 *	The model to build the label for.
	 *
	 * @param string $attribute
	 *	The model attribute to label the input for.
	 *
	 * @param array $configuration
	 *	An array of additional express configuration settings to be
	 *	applied to the input field element, after it's construction.
	 */
	public function activeLabel(Model $model, $attribute, array $configuration = null)
	{	
		return $this->buildLabel(
			$this->getDefaultInputId($model->getAttributeName($attribute)),
			$model->getAttributeLabel($attribute),
			$configuration
		)->render();
	}
	
	/**
	 * Builds and deploys a validation error report for a specific
	 * input.
	 *
	 * @param string[] $messages
	 *	The messages to contain in the generated report. If NULL or an
	 *	empty array is given the report will not be generated.
	 */
	public function inputErrors(array $messages = null)
	{
		if (!empty($messages))
		{
			$this->buildInputErrors($messages)->render();
		}
	}
	
	/**
	 * Builds and deploys a validation error report for a specific
	 * input.
	 *
	 * @param Model $model
	 *	The model to build the error report for.
	 *
	 * @param string $attribute
	 *	The model attribute to build the error report for. If no errors are
	 *	reported for this attribute the report will not be generated.
	 */
	public function activeInputErrors(Model $model, $attribute)
	{
		$this->inputErrors($model->getAttributeErrorMessages($attribute));
	}
	
	/**
	 * Builds and deploys a report listing the validation errors detected across
	 * all input fields defined within the form.
	 *
	 * @param string[] $messages
	 *	The messages to be contained in the report.
	 */
	public function formErrors(array $messages = null)
	{
		if (!empty($messages))
		{
			$this->buildFormErrors($messages)->render();
		}
	}
	
	/**
	 * Builds and deploys a report listing the validation errors detected across
	 * all input fields defined within the form.
	 *
	 * @param Model|string[]|string ...
	 *	A model to extract the messages from, an array of messages or a single
	 *	message to present in the report.
	 */
	public function activeFormErrors(/* ... */)
	{
		$messages = array();
	
		foreach (func_get_args() as $i => $value)
		{
			if ($value instanceof Model)
			{
				$messages = array_merge($messages, $value->getAttributeErrorMessages());
			}
			
			else if (is_array($value))
			{
				$messages = array_merge($messages, $value);
			}
			
			else if (is_string($value))
			{
				$messages[] = $value;
			}
			
			else
			{
				throw new RuntimeException('Invalid argument type ("' . gettype($value) . '") at index ' . $i . '.');
			}
		}
		
		$this->formErrors(array_unique($messages));
	}
	
	/**
	 * Builds and deploys a submit button.
	 *
	 * @param string $label
	 *	The button label.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 */
	public function submitButton($label = 'Submit', array $configuration = null)
	{
		$this->buildSubmitButton($label, $configuration)->render();
	}
	
	/**
	 * Builds and deploys a reset button.
	 *
	 * @param string $label
	 *	The button label.
	 *
	 * @param array $configuration
	 *	An additional express configuration array to configure the input
	 *	html element with.
	 */
	public function resetButton($label = 'Reset', array $configuration = null)
	{
		$this->buildResetButton($label, $configuration)->render();
	}
	
}


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

namespace system\web;

use \system\web\Html;

/**
 * This class is a standard builder class and can be used to build, modify
 * and render any HTML DOM Element.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web
 * @since 0.2.0
 */
class HtmlElement
{
	/**
	 * The element tag name.
	 *
	 * @type string
	 */
	private $tag;
	
	/**
	 * The element class(es).
	 *
	 * @type string[]
	 */
	private $class = array();
	
	/**
	 * The element attribute values, indexed by name.
	 *
	 * @type array
	 */
	private $attributes = array();
	
	/**
	 * An array of child contents, which may be represented as either a string
	 * or another HtmlElement instance.
	 *
	 * @type array
	 */
	private $children = array();

	/**
	 * Constructor.
	 *
	 * @param string $tag
	 *	The element tag name.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct($tag, array $configuration = null) 
	{
		parent::__construct($configuration);
		$this->tag = $tag;
	}
	
	/**
	 * Returns the element tag name.
	 *
	 * @return string
	 *	The element tag name.
	 */
	public function getTag() 
	{
		return $this->tag;
	}
	
	/**
	 * Defines the element tag name.
	 *
	 * @param string $tag
	 *	The the element tag name.
	 */
	public function setTag($tag)
	{
		$this->tag = $tag;
		return $this;
	}
	
	/**
	 * Defines the classes to be applied to this html element, overwritting
	 * any previously defined ones.
	 *
	 * @param string|string[] $class
	 *	The element class as string or array.
	 */
	public function setClass($class)
	{
		if (is_string($class)) 
		{
			$class = preg_split('/(\s+)/', $class, -1, PREG_SPLIT_NO_EMPTY);
		}
		
		$this->class = $class;
	}
	
	/**
	 * Applies the given classes to this html element.
	 *
	 * @param string|string[] $class
	 *	The class(es) to be added as a string or an array of strings.
	 */
	public function addClass($class)
	{
		if (is_string($class))
		{
			$class = preg_split('/(\s+)/', $class, -1, PREG_SPLIT_NO_EMPTY);
		}
		
		$this->class = array_unique(array_merge($this->class, $class));
	}
	
	/**
	 * Removes the given classes to this html element.
	 *
	 * @param string|string[] $class
	 *	The class(es) to be removed as a string or an array of strings.
	 */
	public function removeClass($class) 
	{
		if (is_string($class)) 
		{
			$class = preg_split('/(\s+)/', $class, -1, PREG_SPLIT_NO_EMPTY);
		}
		
		$this->class = array_diff($this->class, $class);
	}
	
	/**
	 * Returns a flag indicating wether or not this element has the
	 * specified class(es) applied to it.
	 *
	 * @param string|string[] $class
	 *	The class(es) to be verified against this instance.
	 *
	 * @param bool $exact
	 *	When set to TRUE this function will only return TRUE if all of the
	 *	specified classes are applied to this element.
	 *
	 * @return bool
	 *	Returns TRUE if the class is assigned, FALSE otherwise.
	 */
	public function hasClass($class, $exact = true) 
	{
		if (is_string($class)) 
		{
			$class = preg_split('/(\s+)/', $class, -1, PREG_SPLIT_NO_EMPTY);
		}
		
		$intersect = array_intersect($class, $this->class);
		
		return $exact ?
			(count($intersect) === count($class)) : isset($intersect[0]);
	}
	
	/**
	 * Toggles the specified classes.
	 *
	 * @param string|string[] $class
	 *	The class(es) to be modified against this instance.
	 *
	 * @param bool $present
	 *	When set to TRUE the classes will be added, removed when set to FALSE
	 *	and toggled when set to NULL.
	 *
	 * @return bool
	 *	Returns TRUE if the classes were added, FALSE otherwise.
	 */
	public function toggleClass($class, $present = null) 
	{
		if (is_string($class)) 
		{
			$class = preg_split('/(\s+)/', $class, -1, PREG_SPLIT_NO_EMPTY);
		}
	
		if (!isset($present))
		{
			$present = !$this->hasClass($class, true);
		}
		
		if ($present)
		{
			$this->addClass($class);
		} 
		else 
		{
			$this->removeClass($class);
		}
		
		return $present;
	}
	
	/**
	 * Defines the value for a specific attribute.
	 *
	 * @param string $attribute
	 *	The name of the attribute to be defined.
	 *
	 * @param mixed $value
	 *	The value to define the attribute with.
	 */
	public function set($attribute, $value = null)
	{
		$this->attributes[$attribute] = $value;
	}
	
	/**
	 * Defines the value for a set of attributes.
	 *
	 * @param array $attributes
	 *	An array of attribute values, indexed by name.
	 */
	public function setAttributes($attributes)
	{
		$this->attributes = array_merge($this->attributes, $attributes);
	}
	
	/**
	 * Returns the value of the specified attribute, if it's defined.
	 *
	 * @param string $attribute
	 *	The name of the attribute to return the value from.
	 *
	 * @return mixed
	 *	Returns the attribute value.
	 */
	public function get($attribute)
	{
		$attributes = $this->attributes;
		
		return isset($attributes[$attribute]) ?
			$attributes[$attribute] : null;
	}
	
	/**
	 * Returns a flag indicating wether or not the specified attribute
	 * is defined.
	 *
	 * @param string $attribute
	 *	The name of the attribute to be defined.
	 *
	 * @return bool
	 *	Returns TRUE if the attribute is defined, FALSE otherwise.
	 */
	public function has($attribute)
	{
		return isset($this->attributes[$attribute]);
	}
	
	/**
	 * Defines the entire inner content for this html element, overwritting
	 * any previously defined contents.
	 *
	 * @param string|HtmlElement $content
	 *	The inner content as either a string or another HtmlElement instance.
	 */
	public function setContent($content)
	{
		$this->children = is_array($content) ? 
			$content : array($content);
	}
	
	/**
	 * Adds child content to this element.
	 *
	 * @param string|HtmlElement|array $element
	 *	The element or content to add.
	 */
	public function addContent($element)
	{	
		if (is_array($element))
		{
			$this->children = array_merge($this->children, $element);
		}
		else
		{
			$this->children[] = $element;
		}		
	}
	
	/**
	 * Returns all child elements and content.
	 *
	 * @return string|HtmlElement|array $element
	 *	The element inner content array.
	 */
	public function getChildren()
	{
		return $this->children;
	}
	
	/**
	 * Returns a flag indicating wether or not this is a self closing element,
	 * meaning that it's empty and a valid self-closing tag element.
	 *
	 * @return bool
	 *	The self-closing element flag.
	 */
	public function isSelfClosing()
	{
		return empty($this->children) && in_array($this->tag, array(
			'base', 'link', 'input', 'hr', 'br', 'img', 'area', 'meta'
		));
	}
	
	/**
	 * Renders the element contents, recursively, optionally capturing and
	 * returning instead of sending them to the output buffer.
	 *
	 * @param bool $capture
	 *	When set to TRUE the element contents will be returned instead of
	 *	flushed into the currently active output buffer.
	 *
	 * @return string
	 *	The rendered contents, if applicable.
	 */
	public function render($capture = false)
	{
		$html = '<' . $this->tag;
		
		// Add all element attributes
		$attributes = $this->attributes;
		
		if (isset($attributes['class']))
		{
			$this->addClass($attributes['class']);
		}
		
		
		$class = $this->class;
		
		if (isset($class[0]))
		{
			$attributes['class'] = implode(' ', $class);
		}

		foreach ($attributes as $name => $value)
		{
			$html .= ' ' . Html::encode($name) . '="' . Html::encode($value) . '"';
		}
		
		// Self closing elements
		if ($this->isSelfClosing())
		{
			$html .= ' />';
		}
		else
		{		
			$html .= '>';
		
			foreach ($this->children as $child)
			{
				if ($child)
				{
					$html .= is_string($child) ?
						$child : $child->render(true);
				}
			}
			
			$html .= '</' . $this->tag . '>';
		}
		
		if ($capture)
		{
			return $html;
		}
		
		echo $html;
	}
}


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

use \system\base\Component;
use \system\core\exception\RuntimeException;
use \system\web\html\Html;

/**
 * The Document component acts as a repository for the HTML document
 * styles, scripts and meta information which is supposed to be changed by
 * the application controllers and views.
 *
 * This Component is defined by default for the web applications.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web
 * @since 0.2.0
 */
class Document extends Component
{
	/**
	 * The next auto incrementable asset ID.
	 *
	 * @type int
	 */
	private static $nextAssetId = 1;
	
	/**
	 * The names of the bundles applied to this document.
	 *
	 * @type string
	 */
	private $bundles = array(
	
		'jquery' => array(
			'class' => 'vendor\\jquery\\JQueryBundle'
		),
		
		'jqueryui' => array(
			'class' => 'vendor\\jqueryui\\JQueryUiBundle'
		),
		
		'bootstrap' => array(
			'class' => 'vendor\\bootstrap\\BootstrapBundle'
		)
		
	);
	
	/**
	 * The document bundle instances.
	 *
	 * @type array
	 */
	private $bundleInstances = array();
	
	/**
	 * An array holding the instances of the bundles currently applied to
	 * this document.
	 */
	private $appliedBundles = array();

	/**
	 * The document scripts.
	 *
	 * @type array
	 */
	private $scripts = array();
	
	/**
	 * The document styles.
	 *
	 * @type array
	 */
	private $styles = array();
	
	/**
	 * The document inline scripts.
	 *
	 * @type array
	 */
	private $inlineScripts = array();
	
	/**
	 * The document inline styles.
	 *
	 * @type array
	 */
	private $inlineStyles = array();

	/**
	 * The document meta data.
	 *
	 * @type array
	 */
	private $meta = array(
		'http-equiv:content-type' => array(array('http-equiv', 'content-type', 'text/html; charset=utf8'))
	);
	
	/**
	 * The document title.
	 *
	 * @type string
	 */
	private $title = 'Untitled Document';
	
	/**
	 * Adds a new script to the document head.
	 *
	 * @param string $script
	 *	The URL linking to the script.
	 *
	 * @param string $id
	 *	The ID of the script to add. If the given ID is already defined
	 *	it will be overwritten with the new script.
	 *
	 * @param string $position
	 *	The script position identifier.
	 */
	public function addScript($script, $id = null, $position = 'head')
	{
		if (!isset($id))
		{
			$id = 'script-' . self::$nextAssetId++;
		}
		
		$this->scripts[$id] = array($script, $position);
	}
	
	/**
	 * Adds a new inline script to the document head.
	 *
	 * @param string $script
	 *	The script raw contents.
	 *
	 * @param string $id
	 *	The ID of the script to add. If the given ID is already defined
	 *	it will be overwritten with the new script.
	 *
	 * @param string $position
	 *	The script position identifier.
	 */
	public function addInlineScript($script, $id = null, $position = 'head')
	{
		if (!isset($id))
		{
			$id = 'script-' . self::$nextAssetId++;
		}
		
		$this->inlineScripts[$id] = array($script, $position);
	}
	
	/**
	 * Checks wether or not the mentioned script is defined.
	 *
	 * @return bool
	 *	Returns TRUE if the script is defined, FALSE otherwise.
	 */
	public function hasScript($id)
	{
		return isset($this->scripts[$id]);
	}
	
	/**
	 * Checks wether or not the mentioned inline script is defined.
	 *
	 * @return bool
	 *	Returns TRUE if the script is defined, FALSE otherwise.
	 */
	public function hasInlineScript($id)
	{
		return isset($this->inlineScripts[$id]);
	}
	
	/**
	 * Returns the document scripts indexed by ID.
	 *
	 * @return array
	 *	The document scripts.
	 */
	public function getScripts()
	{
		return $this->scripts;
	}
	
	/**
	 * Returns the document inline scripts indexed by ID.
	 *
	 * @return array
	 *	The document scripts.
	 */
	public function getInlineScripts()
	{
		return $this->inlineScripts;
	}
	
	/**
	 * Adds a new style to the document head.
	 *
	 * @param string $style
	 *	The URL linking to the style.
	 *
	 * @param string $id
	 *	The ID of the style to add. If the given ID is already defined
	 *	it will be overwritten with the new style.
	 *
	 * @param string $position
	 *	The style position identifier.
	 */
	public function addStyle($style, $id = null, $position = 'head')
	{
		if (!isset($id))
		{
			$id = 'style-' . self::$nextAssetId++;
		}
		
		$this->styles[$id] = array($style, $position);
	}
	
	/**
	 * Adds a new inline style to the document head.
	 *
	 * @param string $style
	 *	The style raw contents.
	 *
	 * @param string $id
	 *	The ID of the style to add. If the given ID is already defined
	 *	it will be overwritten with the new style.
	 *
	 * @param string $position
	 *	The style position identifier.
	 */
	public function addInlineStyle($style, $id = null, $position = 'head')
	{
		if (!isset($id))
		{
			$id = 'style-' . self::$nextAssetId++;
		}
		
		$this->inlineStyles[$id] = array($style, $position);
	}
	
	/**
	 * Checks wether or not the mentioned style is defined.
	 *
	 * @return bool
	 *	Returns TRUE if the style is defined, FALSE otherwise.
	 */
	public function hasStyle($id)
	{
		return isset($this->styles[$id]);
	}
	
	/**
	 * Checks wether or not the mentioned inline style is defined.
	 *
	 * @return bool
	 *	Returns TRUE if the style is defined, FALSE otherwise.
	 */
	public function hasInlineStyle($id)
	{
		return isset($this->inlineStyles[$id]);
	}
	
	/**
	 * Returns the document styles indexed by ID.
	 *
	 * @return array
	 *	The document styles.
	 */
	public function getStyles()
	{
		return $this->styles;
	}
	
	/**
	 * Returns the document inline styles indexed by ID.
	 *
	 * @return array
	 *	The document styles.
	 */
	public function getInlineStyles()
	{
		return $this->inlineStyles;
	}
	
	/**
	 * Adds a new meta entry to the document.
	 *
	 * An previously defined meta data under the specified key and for a
	 * matching type will be discarded.
	 *
	 * @param string $key
	 *	The meta entry name or http-equiv identifier.
	 *
	 * @param string $value
	 *	The meta data, as a string.
	 *
	 * @param string $type
	 *	The type of meta entry to add. Valid values are: "name" 
	 *	and "http-equiv".
	 */
	public function setMeta($key, $value, $type = 'name')
	{
		$index = strtolower($type . ':' . $key);
		$this->meta[$index] = array(array($type, $key, $value));
	}
	
	/**
	 * Adds a new meta entry to the document.
	 *
	 * @param string $key
	 *	The meta entry name or http-equiv identifier.
	 *
	 * @param string $value
	 *	The meta data, as a string.
	 *
	 * @param string $type
	 *	The type of meta entry to add. Valid values are: "name" 
	 *	and "http-equiv".
	 */
	public function addMeta($key, $value, $type = 'name')
	{
		$index = strtolower($type . ':' . $key);
		$this->meta[$index][] = array($type, $key, $value);
	}
	
	/**
	 * Returns the document meta data.
	 *
	 * @return array
	 *	The document meta data.
	 */
	public function getMeta()
	{
		$collection = array();
		
		foreach(array_values($this->meta) as $entries)
		{
			$collection = array_merge($collection, $entries);
		}
		
		return $collection;
	}
	
	/**
	 * Defines the document title.
	 *
	 * @param string $title
	 *	The document title.
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 * Returns the document title.
	 *
	 * @return string
	 *	The document title.
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * Defines the bundles construction and configuration data.
	 *
	 * @param array $bundles
	 *	The bundles construction and configuration data, indexed by
	 *	bundle name.
	 *
	 * @param bool $merge
	 *	When set to TRUE the given bundle settings will be merged
	 *	with the previously defined ones.
	 */
	public function setBundles(array $bundles, $merge = true)
	{
		$this->bundles = $merge ?
			array_replace_recursive($this->bundles, $bundles) : $bundles;
	}
	
	/**
	 * Returns an asset bundle instance.
	 *
	 * @param string $bundle
	 *	The name of the bundle to get the instance of.
	 *
	 * @return Bundle
	 *	The asset bundle instance.
	 */
	public function getBundle($bundle)
	{
		if (isset($this->bundleInstances[$bundle]))
		{
			return $this->bundleInstances[$bundle];
		}
		
		if (isset($this->bundles[$bundle]))
		{
			$configuration = $this->bundles[$bundle];
			
			if (!isset($configuration['class']))
			{
				throw new RuntimeException('Required bundle "' . $bundle . '" property "class" is not defined.');
			}
			
			$class = $configuration['class'];
			unset($configuration['class']);
			
			$instance = new $class($this, $configuration);
			$this->bundleInstances[$bundle] = $instance;
			return $instance;
		}
		
		throw new RuntimeException('Bundle "' . $bundle . '" is not defined.');
	}
	
	/**
	 * Applies a bundle to the current document if it's not applied
	 * already.
	 *
	 * @param string $bundle
	 *	The name of the bundle to apply.
	 */
	public function requireBundle($bundle)
	{
		if (!isset($this->appliedBundles[$bundle]))
		{
			$instance = $this->getBundle($bundle);
			$this->appliedBundles[$bundle] = $instance;
			$instance->apply($this);
		}
	}
	
	/**
	 * Applies bundles to the current document if it's not applied
	 * already.
	 *
	 * @param string[] $bundles
	 *	The names of the bundles to apply.
	 */
	public function requireBundles(array $bundles)
	{
		foreach ($bundles as $bundle)
		{
			if (!isset($this->appliedBundles[$bundle]))
			{
				$instance = $this->getBundle($bundle);
				$this->appliedBundles[$bundle] = $instance;
				$instance->apply($this);
			}
		}
	}
	
	/**
	 * Deploys the current document state.
	 *
	 * @param string $position
	 *	The document position to deploy.
	 *
	 * @param bool $capture
	 *	When set to TRUE any generated output will be returned instead
	 *	of being dumped into the currently active output buffer.
	 *
	 * @return string
	 *	The generated output, if applicable.
	 */
	public function deploy($position = 'head', $capture = false)
	{
		$html = '';
	
		if ($position === 'head')
		{
			// Base url
			$html .= '<base href="' . Html::encode($this->getComponent('router')->getBaseUrl()) . '" />';
		
			// Meta data
			foreach ($this->getMeta() as $meta)
			{
				$html .= '<meta ' . Html::encode($meta[0]) . '="' . Html::encode($meta[1]) . '" content="' .
					Html::encode($meta[2]) . '" />';
			}
			
			// Document title
			$html .= '<title>' . Html::encode($this->title) . '</title>';
		}
		
		// Styles
		foreach ($this->styles as $id => $style)
		{
			if ($style[1] === $position)
			{
				$html .= '<link id="' . Html::encode($id) . '" rel="stylesheet" type="text/css" ' .
					'href="' . Html::encode($style[0]) . '" />';
			}
		}
		
		// Inline styles
		foreach ($this->inlineStyles as $id => $style)
		{
			if ($style[1] === $position)
			{
				$html .= '<style id="' . Html::encode($id) . '" type="text/css">' .
					$style[0] . '</style>';
			}
		}
		
		// Scripts
		foreach ($this->scripts as $id => $script)
		{
			if ($script[1] === $position)
			{
				$html .= '<script type="text/javascript" id="' . Html::encode($id) . '" src="' .
					Html::encode($script[0]) . '"></script>';
			}
		}
		
		// Inline scripts
		foreach ($this->inlineScripts as $id => $script)
		{
			if ($script[1] === $position)
			{
				$html .= '<script type="text/javascript" id="' . Html::encode($id) . '">' .
					$script[0] . '</script>';
			}
		}
		
		if ($capture)
		{
			return $html;
		}
		
		echo $html;
	}
		
}


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

namespace system\web\widget\navigation;

use \system\web\Widget;
use \system\web\html\Html;
use \system\web\html\HtmlElement;

/**
 * Creates a simple button based on a anchor, which may or may not contain
 * an image and a label.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.extension.widget.navigation
 * @since 0.2.0
 */
class ButtonWidget extends Widget
{
	/**
	 * The button label text.
	 *
	 * @type string
	 */
	private $label;
	
	/**
	 * The URL the button is to link to.
	 *
	 * @type string
	 */
	private $url;
	
	/**
	 * The URL linking to the button image (icon).
	 *
	 * @type string
	 */
	private $image;
	
	/**
	 * The explicitly defined image width.
	 *
	 * @type int
	 */
	private $imageWidth;
	
	/**
	 * The explicitly defined image height.
	 *
	 * @type int
	 */
	private $imageHeight;
	
	/**
	 * Defines the button label text contents.
	 *
	 * @param string $label
	 *	The button label text.
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}
	
	/**
	 * Returns the button label text contents.
	 *
	 * @return string
	 *	The button label text.
	 */
	public function getLabel()
	{
		return $this->label;
	}
	
	/**
	 * Defines the URL the button is to link to.
	 *
	 * @param string|array $url
	 *	The button link, either as a string or a route array.
	 */
	public function setUrl($url)
	{
		if (isset($url) && is_array($url))
		{
			$url = $this->getComponent('router')
				->createUrl($url[0], array_slice($url, 1));
		}
		
		$this->url = $url;
	}
	
	/**
	 * Defines the button image or icon.
	 *
	 * @param string $image
	 *	An URL linking to the button image.
	 */
	public function setImage($image)
	{	
		$this->image = $image;
	}
	
	/**
	 * Returns the button image or icon.
	 *
	 * @return string
	 *	An URL linking to the button image.
	 */
	public function getImage()
	{
		return $this->image;
	}
	
	/**
	 * Explicitly defines the button image width.
	 *
	 * @param int $width
	 *	The button image width.
	 */
	public function setImageWidth($width)
	{
		$this->imageWidth = $width;
	}
	
	/**
	 * Returns the explicitly defined button image width.
	 *
	 * @return int
	 *	The button image width.
	 */
	public function getImageWidth()
	{
		return $this->imageWidth;
	}
	
	/**
	 * Explicitly defines the button image height.
	 *
	 * @param int $height
	 *	The button image height.
	 */
	public function setImageHeight($height)
	{
		$this->imageHeight = $height;
	}
	
	/**
	 * Returns the explicitly defined button image height.
	 *
	 * @return int
	 *	The button image height.
	 */
	public function getImageHeight()
	{
		return $this->imageHeight;
	}
	
	/**
	 * Builds the entire image (img) element to be placed within
	 * this button.
	 *
	 * @param string $image
	 *	The URL linking to the button image or icon.
	 *
	 * @param int $width
	 *	The explicitly defined width to be applied to the generated
	 *	image element.
	 *
	 * @param int $height
	 *	The explicitly defined height to be applied to the generated
	 *	image element.
	 *
	 * @return HtmlElement
	 *	Returns the generated html element instance.
	 */
	protected function buildImage($image, $width, $height)
	{
		$img = new HtmlElement('img');
		$img->setClass(array('lw-button-image'));
		$img->setAttribute('src', $image);
		
		if (isset($width))
		{
			$img->setAttribute('width', $width);
			
			if ($width === $height)
			{
				$img->setClass(array('lw-button-image-' . $height));
			}
		}
		
		if (isset($height))
		{
			$img->setAttribute('height', $height);
		}
		
		return $img;		
	}
	
	/**
	 * Builds the entire label (span) element to be placed within
	 * this button.
	 *
	 * @param string $label
	 *	The label text contents.
	 *
	 * @return HtmlElement
	 *	Returns the generated html element instance.
	 */
	protected function buildLabel($label)
	{
		$span = new HtmlElement('span');
		$span->setClass(array('lw-button-label'));
		$span->setTextContent($label);
		return $span;
	}
	
	/**
	 * Builds the entire button element.
	 *
	 * @return HtmlElement
	 *	Returns the generated html element instance.
	 */
	protected function build()
	{
		$a = new HtmlElement('a');
		$classes = array('lw-button');
		$content = array();
		
		if (isset($this->url))
		{
			$a->setAttribute('href', $this->url);
		}
		else
		{
			$classes[] = 'disabled';
		}
		
		if (isset($this->image))
		{
			$content[] = $this->buildImage($this->image, $this->imageWidth, $this->imageHeight);
		}
		
		if (isset($this->label))
		{
			$content[] = $this->buildLabel($this->label);
		}
		
		$a->setClass($classes);
		$a->setContent($content);
		return $a;
	}
}


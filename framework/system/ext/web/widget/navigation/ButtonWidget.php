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

namespace system\ext\web\widget\navigation;

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
	private $label;
	
	private $url;
	
	private $image;
	
	private $imageWidth;
	
	private $imageHeight;
	
	public function setLabel($label)
	{
		$this->label = $label;
	}
	
	public function getLabel()
	{
		return $this->label;
	}
	
	public function setUrl($url)
	{
		if (isset($url) && is_array($url))
		{
			$url = $this->getComponent('router')
				->createUrl($url[0], array_slice($url, 1));
		}
		
		$this->url = $url;
	}
	
	public function setImage($image)
	{	
		$this->image = $image;
	}
	
	public function getImage()
	{
		return $this->image;
	}
	
	public function setImageWidth($width)
	{
		$this->imageWidth = $width;
	}
	
	public function getImageWidth()
	{
		return $this->imageWidth;
	}
	
	public function setImageHeight($height)
	{
		$this->imageHeight = $height;
	}
	
	public function getImageHeight()
	{
		return $this->imageHeight;
	}
	
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
				$img->addClass(array('lw-button-image-' . $height));
			}
		}
		
		if (isset($height))
		{
			$img->setAttribute('height', $height);
		}
		
		return $img;		
	}
	
	protected function buildLabel($label)
	{
		$span = new HtmlElement('span');
		$span->setClass(array('lw-button-label'));
		$span->setTextContent($label);
		return $span;
	}
	
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


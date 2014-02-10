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

namespace system\web\asset;

use \system\base\Component;
use \system\core\Lumina;
use \system\core\exception\RuntimeException;

/**
 * The AssetManager allows the application modules to publish assets into
 * a public directory that can be accessed through the browser.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.asset
 * @since 0.2.0
 */
class AssetManager extends Component
{
	/**
	 * The absolute path to the directory where the assets should be
	 * published into.
	 *
	 * @type string
	 */
	private $publishDirectoryPath;
	
	/**
	 * The absolute URL linking to the directory where the assets should
	 * be available in.
	 *
	 * @type string
	 */
	private $publishDirectoryUrl;

	/**
	 * This method is invoked during the application construction procedure,
	 * before the configuration takes place.
	 *
	 * This method encapsulates the "construction" event.
	 *
	 * @return bool
	 *	Returns TRUE to continue with the event, FALSE to cancel it.
	 */
	protected function onConstruction()
	{
		if (parent::onConstruction())
		{
			$this->publishDirectoryPath = L_PUBLIC . DIRECTORY_SEPARATOR . 'assets';
			$this->publishDirectoryUrl = $this->getComponent('router')->
				getBaseUrl() . 'assets/';
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns the absolute path to the directory where the assets should be
	 * published into.
	 *
	 * @return string
	 *	The publish directory path.
	 */
	public function getPublishDirectoryPath()
	{
		return $this->publishDirectoryPath;
	}
	
	/**
	 * Defines the absolute path to the directory where the assets should be
	 * published into.
	 *
	 * @param string $publishDirectoryPath
	 *	An absolute alias resolving to the publish directory path.
	 */
	public function setPublishDirectoryPath($publishDirectoryPath)
	{
		$this->publishDirectoryPath = Lumina::getAliasPath($publishDirectoryPath, null);
	}
	
	/**
	 * Returns the absolute URL linking to the directory where the assets should
	 * be available in.
	 *
	 * @return string
	 *	The publish directory URL.
	 */
	public function getPublishDirectoryUrl()
	{
		return $this->publishDirectoryUrl;
	}
	
	/**
	 * Defines the absolute URL linking to the directory where the assets should
	 * be available in.
	 *
	 * @param string $publishDirectoryUrl
	 *	The absolute URL linking to the directory where the assets should
	 *	be available in, which must end with a slash ("/").
	 */
	public function setPublishDirectoryUrl($publishDirectoryUrl)
	{
		$this->publishDirectoryUrl = $publishDirectoryUrl;
	}
	
	/**
	 * Copies a directory recursively.
	 *
	 * This method expects the source and destination directories to exist
	 * and will not check against that!
	 *
	 * @throws RuntimeException
	 *	Thrown when the directory fails to be copied.
	 *
	 * @param string $source
	 *	The absolute path to the source directory.
	 *
	 * @param string $destination
	 *	The absolute path to the destination directory.
	 */
	private function copyDirectoryContents($source, $destination)
	{		
		// Open the directory handle
		$sh = opendir($source);
		
		if (!$sh)
		{
			throw new RuntimeException('Failed to open "' . $source . '" source directory.');
		}
		
		while ($file = readdir($sh))
		{
			// Skip hidden files and previous directory links
			if ($file[0] === '.')
			{
				continue;
			}
			
			$spath = $source . $file;
			$dpath = $destination . $file;
			
			// Copy the files or directories recursively
			if (is_dir($spath))
			{
				try
				{
					if (!mkdir($dpath))
					{
						throw new RuntimeException('Unable to create "' . $dpath . '" sub directory.');
					}
				
					$this->copyDirectoryContents($spath . DIRECTORY_SEPARATOR, $dpath . DIRECTORY_SEPARATOR);
				}
				catch (RuntimeException $e)
				{
					closedir($sh);
					throw $e;
				}
			}
			else if (!copy($spath, $dpath))
			{
				closedir($sh);
				throw new RuntimeException('Unable to copy "' . $spath . '" to destination directory.');
			}
		}
		
		closedir($sh);		
	}
	
	/**
	 * Publishes a directory.
	 *
	 * @throws RuntimeException
	 *	Thrown when the directory fails to be published.
	 *
	 * @param string $directory
	 *	An absolute alias to the directory to publish.
	 *
	 * @param bool $refresh
	 *	When set to TRUE the published directory will be refreshed with the
	 *	new contents.
	 *
	 * @return string
	 *	Returns the published directory URL, including the ending slash, which
	 *	can than be used to link the assets within the document.
	 */
	public function publish($directory, $refresh = false)
	{
		// Get the source directory path and hash
		$directory = Lumina::getAliasPath($directory, null) . DIRECTORY_SEPARATOR;
		$hash = hash('md5', $directory);
		
		// Determine the destination directory and publish status
		$destination = $this->publishDirectoryPath . DIRECTORY_SEPARATOR 
			. $hash . DIRECTORY_SEPARATOR;
		
		$unpublished = !file_exists($destination);
	
		if ($refresh || $unpublished)
		{
			// Make sure the source directory exists
			if (!file_exists($directory))
			{
				throw new RuntimeException('Source directory "' . $directory . '" does not exist.');
			}
			
			// Create the destination directory as needed
			if ($unpublished)
			{
				if (!mkdir($destination, 0777, true))
				{
					throw new RuntimeException('Unable to create "' . $destination . '" publish directory.');
				}
			}
			else
			{
				if (!rmdir($destination) || !mkdir($destination, 0777, true))
				{
					throw new RuntimeException('Failed to recreate existing "' . $destination . '" publish directory.');
				}
			}
		
			$this->copyDirectoryContents($directory, $destination);
		}
		
		return $this->publishDirectoryUrl . $hash . '/';
	}

}


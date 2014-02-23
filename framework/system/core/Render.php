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

namespace system\core;

use \system\core\Extension;
use \system\core\Lumina;

/**
 * This class is used to limit the view context scope and disallow it to
 * access or modify private members.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.core
 * @since 0.2.0
 */
class Render extends Extension
{	
	/**
	 * Renders a script file.
	 *
	 * Please note the 'self' variable is not defined by default, meaning that
	 * you will have to call this function again if you wish to render a
	 * child view from that context.
	 *
	 * Make sure you can not render the view file from a controller before
	 * using this function.
	 *
	 * @param string $file
	 *	The absolute path to the file being rendered.
	 *
	 * @param array $variables
	 *	The variables to be extracted into the script context.
	 *
	 * @param bool $capture
	 *	When set to TRUE the rendered contents will be captured instead
	 *	of sent to the currently active output buffer.
	 */
	protected static function renderFileEx($__FILE__, array $__VARIABLES__ = null, $__CAPTURE__ = true)
	{
		if (isset($__VARIABLES__))
		{
			extract($__VARIABLES__);
		}
		
		if ($__CAPTURE__)
		{
			ob_start();
			require($__FILE__);
			return ob_get_clean();
		}
		
		require($__FILE__);
	}
	
	/**
	 * Renders a script file from this context.
	 *
	 * Make sure you can not render the view file from a controller before
	 * using this function.
	 *
	 * @param string $file
	 *	The absolute path to the file being rendered.
	 *
	 * @param array $variables
	 *	The variables to be extracted into the script context.
	 *
	 * @param bool $capture
	 *	When set to TRUE the rendered contents will be captured instead
	 *	of sent to the currently active output buffer.
	 */
	protected function renderFile($__FILE__, array $__VARIABLES__ = null, $__CAPTURE__ = true)
	{
		if (isset($__VARIABLES__))
		{
			extract($__VARIABLES__);
		}
		
		if ($__CAPTURE__)
		{
			ob_start();
			require($__FILE__);
			return ob_get_clean();
		}
		
		require($__FILE__);
	}
}


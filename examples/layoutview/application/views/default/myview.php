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

$document = $self->getComponent('document');

// You can define custom styles and scripts from the view
$document->addInlineStyle(
<<<CSS

	.italic {
		font-style: italic;
	}

CSS
);

// And you can also require bundles
$document->requireBundle('jquery');

?>
<h2>Behold, the MESSAGE!</h2>
<p><span class="italic">"<?php echo $message ?>"</span></p>


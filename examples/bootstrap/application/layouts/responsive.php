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

//
// This example contains large portions of code provided by the Bootstrap team,
// as seen on Bootstrap's official website:
//
// > http://getbootstrap.com/examples/jumbotron/
// > http://getbootstrap.com/
// 

use \system\web\html\Html;
use \system\web\extension\widget\DocumentWidget;

// Load the Bootstrap bundle (which also loads jQuery)
$document = $this->getComponent('document');
$document->requireBundle('bootstrap');

?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php $document->deploy('head'); ?>
	</head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#"><?php echo Html::encode($document->getTitle()) ?></a>
				</div>
				<div class="navbar-collapse collapse">
					<form class="navbar-form navbar-right" role="form">
						<div class="form-group">
							<input type="text" placeholder="Email" class="form-control">
						</div>
						<div class="form-group">
							<input type="password" placeholder="Password" class="form-control">
						</div>
						<button type="submit" class="btn btn-success">Sign in</button>
					</form>
				</div><!--/.navbar-collapse -->
			</div>
		</div>
		
		<div id="viewContents"><?php echo $viewContents ?></div>
		
		<div class="container">
			<hr />

			<footer>
				<p>&copy; Company 2014</p>
			</footer>
		</div>
		
		<?php $document->deploy('footer') ?>
	</body>
</html>

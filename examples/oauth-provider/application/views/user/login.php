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

?>
<form method="POST">
	<fieldset>
		<legend>Authentication</legend>
		
		<div> 
			<label>Username</label>
			<input name="username" type="text" value="<?= $username ?>" placeholder="Username" required autofocus />
		</div>

		<div> 
			<label>Password</label>
			<input name="password" type="password" placeholder="Password" required />
		</div>
		
		<?php if($error === true) : ?>
		<div>
			<p><?= $errorMsg ?></p>
		</div>
		<?php endif; ?>
		
		<div>
			<button type="submit">Log In</button>
		</div>

	</fieldset>
</form>

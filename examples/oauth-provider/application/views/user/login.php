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

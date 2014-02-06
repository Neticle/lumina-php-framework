<?php if ($model->hasAttributeErrors()) : ?>
<ul>
	<?php foreach($model->getAttributeErrorMessages() as $message) : ?>
	<li><?php echo $message ?></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	<div>
		<label for="User_username">Username</label>
		<input id="User_username" type="text" name="User[username]" value="<?php echo $model->getAttribute('username') ?>" />
	</div>
	<div>
		<label for="User_password">Password</label>
		<input id="User_password" type="password" name="User[password]" value="" />
	</div>
	<div>
		<label for="User_email">Email</label>
		<input id="User_email" type="text" name="User[email]" value="<?php echo $model->getAttribute('email') ?>" />
	</div>
	<div>
		<label for="User_age">Age</label>
		<input id="User_age" type="text" name="User[age]" value="<?php echo $model->getAttribute('age') ?>" />
	</div>
	<div><input type="submit" value="Submit" /></div>
</form>

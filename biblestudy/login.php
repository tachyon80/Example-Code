<?php

ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

require_once 'bootstrap.php';
if ($_POST) {
	$user = new User();
	$user->username = $_POST['username'];
	$user->password = $_POST['password'];
	$_SESSION['user'] = $user;
	if ($user->authenticate()) {
		Util::redirect('/biblestudy/');
	} else {
		$error_message = 'Invalid username or password';
	}
}
if ($error_message) {
	echo $error_message;
}
?>
<form method="post" action="login.php">
	<label>Username</label>
	<input type="text" name="username" /><br>
	<label>Password</label>
	<input type="password" name="password" /><br>
	<input type="submit" />
</form>
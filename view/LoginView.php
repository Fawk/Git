<?php

class LoginView {

	private static $loginKey = "login";
	private static $logoutKey = "logout";

	public function generateForm($action, $user = "") {

		echo "<form action='$action' method='post' class='login'>
		<p>
			<label for='login'>Username:</label>
			<input type='text' id='login' name='username' value='$user' />
			</p>
		<p>
			<label for='password'>Password:</label>
			<input type='password' id='password' name='password' value='' />
		</p>
		<div class='center'>
			<input type='checkbox' id='remember' name='remember' value='1' />
			<label for='remember'>Remember me<span></span></label>
		</div>
			<p class='login-submit'><button type='submit' class='login-button'>Login</button></p>
		</form>
		<br/><br/>";
	}

	public function isLoggingIn() {

		return isset($_GET[self::$loginKey]);
	}

	public function isLoggingOut() {

		return isset($_GET[self::$logoutKey]);
	}

	public function handleMessage($msg, $isError = false) {

		$class = "";
		$isError ? $class = "error" : $class = "message";

		echo "<div class='$class'>$msg</div><br/><br/>";
	}

	public function userSavedLogin() {

		return isset($_POST["remember"]);
	}

	public function generateLogout() {

		echo "<a href='?logout'>Logout</a><br/><br/>";
	}

	public function generateReturnLink() {

		echo "<a href='?'>Return to login</a>";
	}
}

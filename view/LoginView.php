<?php

require_once("./model/Message.php");

class LoginView {

	/**
	* @var String - all below
	*/
	private static $loginKey = "login";
	private static $logoutKey = "logout";
	private static $usernameFieldKey = "username";
	private static $passwordFieldKey = "password";
	private static $rememberFieldKey = "remember";

	/**
	* @param String - key for message constant
	* @return String from constant
	*/
	private function getMessageFromEnum($key) {

		return constant('Message::' . $key);
	}

	/**
	* @param String - username to input into form if password was missing
	* Writes out an HTML form
	*/
	public function generateForm($user = "") {

		echo "<form action='?" . self::$loginKey ."' method='post' class='login'>
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

	/**
	* @return String - user input username
	*/
	public function getUser() {

		return $_POST[self::$usernameFieldKey];
	}

	/**
	* @return String - user input password
	*/
	public function getPassword() {

		return $_POST[self::$passwordFieldKey];
	}

	/**
	* @return boolean - if user wants to login
	*/
	public function isLoggingIn() {

		return isset($_GET[self::$loginKey]);
	}

	/**
	* @return boolean - if user wants to logout
	*/
	public function isLoggingOut() {

		return isset($_GET[self::$logoutKey]);
	}

	/**
	* @param String - key for constant message
	* @param boolean - is message error?
	* Writes out an HTML message
	*/
	public function handleMessage($key, $isError = false) {

		$msg = $this->getMessageFromEnum($key);
		$class = "";
		$isError ? $class = "error" : $class = "message";

		echo "<div class='$class'>$msg</div><br/><br/>";
	}

	/**
	* @return boolean - did user check the remember box?
	*/
	public function userSavedLogin() {

		return isset($_POST[self::$rememberFieldKey]);
	}

	/**
	* Writes out a HTML logout link
	*/
	public function generateLogout() {

		echo "<a class='transition' href='?logout'>Logout</a><br/><br/>";
	}

	public function getUserInfo()
	{

	}
}

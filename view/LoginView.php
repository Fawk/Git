<?php

require_once("Message.php");
require_once("./model/LoginObserver.php");

class LoginView implements LoginObserver {

	/**
	* @var String - all below
	*/
	private static $loginKey = "login";
	private static $logoutKey = "logout";
	private static $usernameFieldKey = "username";
	private static $passwordFieldKey = "password";
	private static $rememberFieldKey = "remember";
	private static $usernameCookieKey = "username";
	private static $passwordCookieKey = "password";
	private static $cookieFileLocation = "./40221345130b5d464279e518446f69ae.txt";

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
	* update function from LoginObserver
	*/
	public function update($subject) {

		$msg = new Message();

		if($subject->getState() == 7) {

			$extra = "";
			if($this->userSavedLogin()) {

				$extra = "<br/><br/>" . $msg->fetchMessage(6);
			}
				
				$this->handleMessage($msg->fetchMessage($subject->getState()) . $extra);

		}
		else {

			$this->handleMessage($msg->fetchMessage($subject->getState()), $subject->isError());
		}
	}

	/**
	* @return String - user input username
	*/
	public function getUser() {
		
		if(isset($_POST[self::$usernameFieldKey]))
			return $_POST[self::$usernameFieldKey];
	}

	/**
	* @return String - user input password
	*/
	public function getPassword() {

		if(isset($_POST[self::$passwordFieldKey]))
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
	* @param String - msg - the message
	* @param boolean - is message error?
	* Writes out an HTML message
	*/
	public function handleMessage($msg, $isError = false) {

		//$msg = $this->getMessageFromEnum($key);
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

	public function setLoginCookies($cookieLength = 0) {
		
		if($this->loginCookieStored()) {

			setcookie(self::$usernameCookieKey, "", time() - 3600);
			setcookie(self::$passwordCookieKey, "", time() - 3600);

		} else {

			setcookie(self::$usernameCookieKey, $this->getUser(), time() + $cookieLength);
			setcookie(self::$passwordCookieKey, sha1($this->getPassword()), time() + $cookieLength);	
		}
	}

	/**
	* @return boolean - Is a login cookie stored?
	*/
	public function loginCookieStored() {

		return isset($_COOKIE[self::$usernameCookieKey]) && isset($_COOKIE[self::$passwordCookieKey]);
	}

	/**
	* @param ClientInfo
	* @return boolean - Is the login cookie valid?
	*/
	public function loginCookieValid(ClientInfo $info) {

		$cookiedata = file_get_contents(self::$cookieFileLocation);

		return $info->isExistsAndValid($cookiedata);	
	}

	public function getCookieUser()
	{
		return $_COOKIE[self::$usernameCookieKey];
	}

	public function getCookiePassword()
	{
		return $_COOKIE[self::$passwordCookieKey];
	}

		/**
	* @param String - username
	* @param String - password
	* @return String - if either was missing else returns empty string
	*/
	public function checkEmpty($user, $pass) {		
		
		if(empty($user) || preg_match('/\s/', $user)) {
			 return 0;
		}
		else if(empty($pass)) {
			 return 1;
		}

		return -1;
	}
}

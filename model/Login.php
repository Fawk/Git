<?php

session_start();

class Login {

	private static $admin = "Admin";
	private static $adminpass = "dc647eb65e6711e155375218212b3964";
	private static $errorSessionKey = "login::error";
	private static $authSessionKey = "login::auth";

	public function __construct() {

		$_SESSION[self::$errorSessionKey] = false;
	}

	public function checkLogin($user, $pass) {

		$missing = $this->checkEmpty($user, $pass);

		if($missing === false) {

			return $user == self::$admin && $pass == self::$adminpass;
		}

		return $missing;
	}

	public function checkEmpty($user, $pass) {		
		
		$msg = false;

		if(empty($user) || preg_match('/\s/', $user)) {
			 $msg = "Username is missing!"; 
		}
		else if(empty($pass)) {
			 $msg = "Password is missing!"; 
		}

		return $msg;
	}

	public function setAuthSession() {

		$_SESSION[self::$authSessionKey] = rand(1,99999);	
	}

	public function unsetAuthSession() {

		unset($_SESSION[self::$authSessionKey]);
	}

	public function isAuthed() {

		return isset($_SESSION[self::$authSessionKey]);
	}

	public function setLoginCookies($username = "", $password = "", $cookieLength = 0) {

		if($this->loginCookieStored()) {

			setcookie("username", "", time() - 3600);
			setcookie("password", "", time() - 3600);

		} else {

			setcookie("username", $username, time() + $cookieLength);
			setcookie("password", $password, time() + $cookieLength);	
		}
	}

	public function loginCookieStored() {

		return isset($_COOKIE["username"]) && isset($_COOKIE["password"]);
	}

	public function loginCookieValid() {

		return $_COOKIE["username"] == self::$admin && $_COOKIE["password"] == self::$adminpass;
	}
}
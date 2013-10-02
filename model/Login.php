<?php

session_start();

class Login {

	private static $admin = "Admin";
	private static $adminpass = "dc647eb65e6711e155375218212b3964";
	private static $authSessionKey = "login::auth";
	private static $usernameCookieKey = "username";
	private static $passwordCookieKey = "password";

	/**
	* @param String - username
	* @param String - password
	* @return boolean - was the login info correct?
	*/
	public function checkLogin($user, $pass) {

		return $user == self::$admin && md5($pass) == self::$adminpass;
	}

	/**
	* @param String - username
	* @param String - password
	* @return String - if either was missing else returns empty string
	*/
	public function checkEmpty($user, $pass) {		
		
		$msg = "";

		if(empty($user) || preg_match('/\s/', $user)) {
			 $msg = "USERNAME_MISSING"; 
		}
		else if(empty($pass)) {
			 $msg = "PASSWORD_MISSING"; 
		}

		return $msg;
	}

	/**
	* Sets the auth session used to determine if a user is logged in
	*/
	public function setAuthSession() {

		$_SESSION[self::$authSessionKey] = rand(1,99999);	
	}

	/**
	* Unsets the auth session
	*/
	public function unsetAuthSession() {

		unset($_SESSION[self::$authSessionKey]);
	}

	/**
	* @return boolean - is the user logged in?
	*/
	public function isAuthed() {

		return isset($_SESSION[self::$authSessionKey]);
	}

	/**
	* @param String - username
	* @param String - password
	* @param int - cookie alivetime
	* Sets a cookie with user info if it doesn't exist, otherwise removes it
	*/
	public function setLoginCookies($username = "", $password = "", $cookieLength = 0) {

		if($this->loginCookieStored()) {

			setcookie(self::$usernameCookieKey, "", time() - 3600);
			setcookie(self::$passwordCookieKey, "", time() - 3600);

		} else {

			setcookie(self::$usernameCookieKey, $username, time() + $cookieLength);
			setcookie(self::$passwordCookieKey, $password, time() + $cookieLength);	
		}
	}

	/**
	* @return boolean - Is a login cookie stored?
	*/
	public function loginCookieStored() {

		return isset($_COOKIE[self::$usernameCookieKey]) && isset($_COOKIE[self::$passwordCookieKey]);
	}

	/**
	* @return boolean - Is the login cookie valid?
	*/
	public function loginCookieValid() {

		return $_COOKIE[self::$usernameCookieKey] == self::$admin && $_COOKIE[self::$passwordCookieKey] == self::$adminpass;
	}
}
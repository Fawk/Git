<?php

session_start();

class Login {

	/**
	* @var String - all below
	*/
	private static $admin = "Admin";
	private static $adminpass = "dc647eb65e6711e155375218212b3964";
	private static $authSessionKey = "login::auth";
	private static $usernameCookieKey = "username";
	private static $passwordCookieKey = "password";
	private static $cookieFileLocation = "./40221345130b5d464279e518446f69ae.txt";

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
	public function setAuthSession(ClientInfo $info) {

		$info->setSession();

		$_SESSION[self::$authSessionKey] = true;	
	}

	/**
	* Unsets the auth session
	*/
	public function unsetAuthSession(ClientInfo $info) {

		$info->unsetSession();

		unset($_SESSION[self::$authSessionKey]);
	}

	/**
	* @return boolean - is the user logged in?
	*/
	public function isAuthed(ClientInfo $info) {

		if(isset($_SESSION[self::$authSessionKey]))
		{
			if($info->isSessionValid())
			{
				return true;
			}
		}
		return false;
	}

	/**
	* @param String - username
	* @param String - password
	* @param int - cookie alivetime
	* If a cookie exists, unset it otherwise read the savedcookies file and determine if user has a saved row already, if user does update row with new timestamp.
	* If the user doesn't have a saved row, creates one and adds it to the file.
	* Also saves the time and values to cookies on the client browser
	*/
	public function setLoginCookies($username = "", $password = "", $cookieLength = 0) {

		if($this->loginCookieStored()) {

			setcookie(self::$usernameCookieKey, "", time() - 3600);
			setcookie(self::$passwordCookieKey, "", time() - 3600);

		} else {

			$ip = $_SERVER["REMOTE_ADDR"];
			$agent = $_SERVER["HTTP_USER_AGENT"];

			$data = "";

			if(filesize(self::$cookieFileLocation) > 0)
			{
				$data = file_get_contents(self::$cookieFileLocation);

				$str = explode("\r\n", $data);

				foreach($str as $key => $value)
				{
					if($value != "")
					{
						$e = explode("+", $value);
						if($e[1] == $ip && $e[2] == $agent)
						{
							unset($str[$key]);
						}
					}
				}

				$data = "";

				foreach($str as $key => $value)
				{
					if($value != "")
					{
						$data .= $value . "\r\n";
					}
				}
			}

			$time = time() + $cookieLength;
			$newdata = $data . $time . "+" . $ip . "+" . $agent . "\r\n";

			file_put_contents(self::$cookieFileLocation, "");
			file_put_contents(self::$cookieFileLocation, $newdata, FILE_APPEND);

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
	public function loginCookieValid(ClientInfo $info) {

		$cookiedata = file_get_contents(self::$cookieFileLocation);

		if($info->isExistsAndValid($cookiedata))
		{
			return $_COOKIE[self::$usernameCookieKey] == self::$admin && $_COOKIE[self::$passwordCookieKey] == self::$adminpass;
		}
		else
		{
			return false;
		}
	}
}
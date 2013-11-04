<?php

session_start();

require_once("Subject.php");

class Login extends Subject {

	/**
	* @var String - all below
	*/
	private static $admin = "Admin";
	private static $adminpass = "8be3c943b1609fffbfc51aad666d0a04adf83c9d";
	private static $authSessionKey = "login::auth";
	private static $cookieFileLocation = "./40221345130b5d464279e518446f69ae.txt";

	/**
	* @param String - username
	* @param String - password
	* @return boolean - was the login info correct?
	*/
	public function checkLogin($user, $pass, $isCookie = false) {

		if(!$isCookie)
		{
			$pass = sha1($pass);
		}
		return $user == self::$admin && $pass == self::$adminpass;
	}

	/**
	* @param ClientInfo
	* Sets the auth session used to determine if a user is logged in
	*/
	public function setAuthSession(ClientInfo $info) {

		$info->setSession();

		$_SESSION[self::$authSessionKey] = true;	
	}

	/**
	* @param ClientInfo
	* Unsets the auth session
	*/
	public function unsetAuthSession(ClientInfo $info) {

		$info->unsetSession();

		unset($_SESSION[self::$authSessionKey]);
	}

	/**
	* @param ClientInfo
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
		
	}
}
<?php
namespace view;

class Login {

	private $admin = "oskar";
	private $adminpass = "e99a18c428cb38d5f260853678922e03";
	
	public function generateForm($action, $user = "", $pass = "") {
		
		return "<form action='$action' method='post'>
		Username: <input type='text' name='username' value='$user' /><br/>
		Password: <input type='password' name='password' value='$pass' /><br/>
		Remember me: <input type='checkbox' name='remember' value='1' /><br />
		<input type='submit' value='Login' />
	</form>
		";
	}
	
	public function checkEmpty($user, $pass) {
		
		$return = "";
		
		if(empty($user) || preg_match('/\s/', $user)) {
			 $return = "Username is missing!"; 
		}
		else if(empty($pass)) {
			 $return = "Password is missing!"; 
		}
		
		return $return;
	}

	public function checkLogin($user, $pass) {

		return $user == $this->admin && md5($pass) == $this->adminpass;
	}
}

<?php

class Login {

	private $admin = "Admin";
	private $adminpass = "dc647eb65e6711e155375218212b3964";
	
	public function generateForm($action, $user = "", $pass = "") {
		
		return "<fieldset><legend>Login - Please type in username and password</legend>
		<form action='$action' method='post'>
		Username: <input type='text' name='username' value='$user' /><br/>
		Password: <input type='password' name='password' value='$pass' /><br/>
		Remember me: <input type='checkbox' name='remember' value='1' /><br />
		<input type='submit' value='Login' />
	</form>
	</fieldset>
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

		return $user == $this->admin && $pass == $this->adminpass;
	}
}
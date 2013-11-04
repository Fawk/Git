<?php

/**
* Message class which holds all messages that are displayed
*/

class Message {

	private $messages = array("Logged out.",
							  "Admin logged in.",
							  "Logged in by using cookies.",
							  "Information in cookies was invalid!",
							  "Username is missing!",
							  "Password is missing!",
							  "Your login has been saved.",
							  "Successful login.",
							  "Wrong username or password!");

	/**
	* Function to return appropriate message to the view
	* 
	* 0 : Logged out.
	* 1 : Admin logged in.
	* 2 : Logged in by using cookies.
	* 3 : Information in cookies was invalid!
	* 4 : Username is missing!
	* 5 : Password is missing!
	* 6 : Your login has been saved.
	* 7 : Successful login.
	* 8 : Wrong username or password!
	*/
	public function fetchMessage($int)
	{
		return $this->messages[$int];
	}
}
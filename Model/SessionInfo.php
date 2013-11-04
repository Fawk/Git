<?php

/**
 * SessionInfo short summary.
 *
 * SessionInfo description.
 *
 * @version 1.0
 * @author Fawk
 */
class SessionInfo
{
    /**
    * @var User $user
    */
    public $user;
    
    /**
    * @var string - both below
    */
    public $ip;
    public $agent;
    
    /**
    * Construct function set the user object to the SessionInfo object and get browser information
    * @param User $user
    */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->ip = $_SERVER["REMOTE_ADDR"];
        $this->agent = $_SERVER["HTTP_USER_AGENT"];
    }
    
    /**
    * Function to check if the session is the same
    * @return bool
    */
    public function isSameSession() {
		if($this->ip != $_SERVER["REMOTE_ADDR"] || 
		   $this->agent != $_SERVER["HTTP_USER_AGENT"]) {  
			return false;
		}
		return true;
	}
}

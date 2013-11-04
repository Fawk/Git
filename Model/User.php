<?php

// Password class used to create a strong hash for the password and cookie
// https://github.com/ircmaxell/password_compat
require_once("./vendors/password.php");

/**
 * User short summary.
 *
 * User description.
 *
 * @version 1.0
 * @author Fawk
 */
class User
{
    /**
    * @var int $id
    */
    private $id;
    
    /**
    * @var string $username
    */
    private $username;
    
    /**
    * @var string $password
    */
    private $password;
    
    /**
    * @var int $cookieTime - timestamp
    */
    private $cookieTime;
    
    /**
    * @var string $agent
    */
    private $agent;
    
    /**
    * @var string $ip
    */
    private $ip;
    
    /**
    * Construct function to init a User object with username and password
    */
    public function __construct($user, $pass)
    {
        $this->username = $user;
        $this->password = $pass;
    }
    
    /**
    * Function to set id
    * @param int
    */
    public function SetId($id)
    {
        $this->id = $id;
    }
    
    /**
    * Function to get id
    * @return int
    */
    public function GetId()
    {
        return $this->id;
    }
    
    /**
    * Function to get username
    * @return string
    */
    public function GetUsername()
    {
        return $this->username;
    }
    
    /**
    * Function to get password
    * @return string
    */
    public function GetPassword()
    {
        return $this->password;
    }
    
    /**
    * Check whether the applied user matches this user according to hash
    * @param User $user
    * @return bool
    */
    public function CheckValid(User $user)
    {
        return $this->username == $user->GetUsername() &&
               password_verify($user->GetPassword(), $this->password);
    }
    
    /**
    * Hash the current password on the user
    */
    public function HashPassword()
    {
        $options = array('cost' => 11);
        $this->password = password_hash($this->password, PASSWORD_BCRYPT, $options);
    }
    
    /**
    * Function to check if the cookie is valid according to hash and various other aspects
    * @param string $hash
    * @return bool
    */
    public function CompareCookieHash($hash)
    {
        $ex = explode(":", $hash);
        if(count($ex == 3))
        {
            list($uid, $ip, $agent) = explode(":", $hash);

            return password_verify($this->id, $uid) &&
                    password_verify($this->ip, $ip) &&
                    password_verify($this->agent, $agent) &&
                    $this->agent == $_SERVER['HTTP_USER_AGENT'] &&
                    $this->ip == $_SERVER['REMOTE_ADDR'] &&
                    time() < $this->cookieTime;
        }
        return false;
    }
    
    /**
    * Function to set cookietime
    * @param int $time
    */
    public function SetCookieTime($time)
    {
        $this->cookieTime = $time;
    }
    
    /**
    * Function to get cookietime
    * @return int
    */
    public function GetCookieTime()
    {
        return $this->cookieTime;
    }
    
    /**
    * Function to set agent
    * @param string $agent
    */
    public function SetAgent($agent)
    {
        $this->agent = $agent;
    }
    
    /**
    * Function to get agent
    * @return string
    */
    public function GetAgent()
    {
        return $this->agent;
    }
    
    /**
    * Function to set ip
    * @param string $ip
    */
    public function SetIp($ip)
    {
        $this->ip = $ip;
    }
    
    /**
    * Function to get ip
    * @return string
    */
    public function GetIp()
    {
        return $this->ip;
    }
}

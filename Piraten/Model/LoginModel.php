<?php

session_start();

/**
 * LoginModel short summary.
 *
 * LoginModel description.
 *
 * @version 1.0
 * @author ok222ax
 */
class LoginModel
{
    private static $adminUser = "Roger";
    private static $adminPass = "4329dacf6626615456c6746bce789e87f5f678c4";
    private static $authSessionKey = "login::auth";
    
    public function checkLogin($user, $pass)
    {
        return $user == self::$adminUser && sha1($pass) == self::$adminPass;
    }
        
    public function isAuthed()
    {
        return isset($_SESSION[self::$authSessionKey]);
    }
    
    public function setSession()
    {
        if(isset($_SESSION[self::$authSessionKey]))
        {
            unset($_SESSION[self::$authSessionKey]);
        }
        else
        {
            $_SESSION[self::$authSessionKey] = true;
        }
    }
    
    public function handleMessage($msg, $isError = false)
    {
        $class = "";
        $isError ? $class = "error" : $class = "message";
        echo "<div class='$class'>$msg</div>";
    }
}

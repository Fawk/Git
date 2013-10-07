<?php

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
    private static $adminPass = "e10adc3949ba59abbe56e057f20f883e";
    private static $authSessionKey = "login::auth";
    
    public function checkLogin($user, $pass)
    {
        return $user == $adminUser && md5($pass) == $adminPass;
    }
    
    public function setSession()
    {
        if(isset($_SESSION[self::$authSessionKey]))
            unset($_SESSION[self::$authSessionKey]);
            
        $_SESSION[self::$authSessionKey] = true;
    }
    
    public function handleMessage($msg, $isError = false)
    {
        $class = "";
        $isError ? $class = "error" : $class = "message";
        echo "<div class='$class'>$msg</div>";
    }
}

<?php

require_once("BaseView.php");

/**
 * LoginView short summary.
 *
 * LoginView description.
 *
 * @version 1.0
 * @author ok222ax
 */
class LoginView extends BaseView
{
    private static $loginKey = "login";
    private static $logoutKey = "logout";
    private static $usernameKey = "username";
    private static $passwordKey = "password";
    

    public function isLoggingIn()
    {
        return isset($_GET[self::$loginKey]);
    }
    
    public function isLoggingOut()
    {
        return isset($_GET[self::$logoutKey]);
    }
    
    public function isAuthed()
    {
        return isset($_SESSION[$sessionKey]);
    }
    
    public function generateForm()
    {
        echo "
          <form action='?" . self::$loginKey . "' method='post'>
            <input type='text' id='username' name='username' />
            <input type='password' id='password' name='password' />
            <input type='submit' value='Logga in' />
          </form>
        ";
    }
    
    public function generateLogout()
    {
        echo "<a href='?" . self::$logoutKey . "'>Logga ut</a>";
    }
    
    public function getUser()
    {
        return $_POST[self::$usernameKey];
    }
    
        public function getPassword()
    {
        return $_POST[self::$passwordKey];
    }
}

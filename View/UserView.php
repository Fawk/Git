<?php

require_once("./Model/User.php");
require_once("./Model/UserObserver.php");
require_once("Message.php");

/**
 * UserView short summary.
 *
 * UserView description.
 *
 * @version 1.0
 * @author Fawk
 */
class UserView implements UserObserver
{
    /**
    * @var string - all below - keys used in the view
    */
    private static $baseKey = "do";
    private static $loginKey = "login";
    private static $logoutKey = "logout";
    private static $registerFormSubmitKey = "Registrera";
    private static $loginFormSubmitKey = "Logga in";
    private static $logoutLinkKey = "Logga ut";
    private static $usernameKey = "username";
    private static $passwordKey = "password";
    private static $secondPasswordKey = "secondpassword";
    private static $registerKey = "register";
    private static $registeringKey = "doregister";
    private static $cookieKey = "SSID";
    private static $idKey = "id";
    private static $saveLoginKey = "remember";
    private static $formKey = "form";
    private static $creatingFormKey = "creatingForm";
    
    /**
    * @var const int - used in length rulings
    */
    const USERNAME_MIN_CHAR = 3;
    const USERNAME_MAX_CHAR = 15;
    const PASSWORD_MIN_CHAR = 6;
    const PASSWORD_MAX_CHAR = 20;
    
    /**
    * @var string - all below - Used in the view to pass messages
    */
    private $formError = "";
    private $UserLoggedString = "";
    private $formMessage = "";
    private $message;
    
    /**
    * Construct function - inits the message class
    */
    public function __construct()
    {
        $this->message = new Message();
    }
    
    /**
    * @param User $user
    * Sets the logged in string used in the view
    */
    public function SetLoggedInUser(User $user)
    {
        $this->UserLoggedString = "Inloggad som <a href='?id=" . $user->GetId() . "'>" . $user->GetUsername() . "</a> - <a href='?" . self::$logoutKey . "'>" . self::$logoutLinkKey . "</a>";
    }
    
    /**
    * @param string $username
    * View function to create the login/register panel html
    */
    public function MakeForm($username = "")
    {
        $str = "<div class='topbar'>
                    <div class='forms'>
                        <div class='message'>$this->formMessage</div>
                        <div class='error'>$this->formError</div>
                        <form action='?" . self::$loginKey . "' method='post'>
                            <div class='loginfield'>
                                <input type='text' name='" . self::$usernameKey . "' placeholder='Användarnamn' value='$username' /><br>
                                <input type='password' name='" . self::$passwordKey . "' placeholder='Lösenord' />
                                <label class='checkbox'>
                                    <input type='checkbox' name='" . self::$saveLoginKey . "' id='remember' value='true' />
                                    Kom ihåg mig
                                </label>
                                <input type='submit' class='test' value='" . self::$loginFormSubmitKey . "' /><br/> 
                                <a href='#' class='register'>Registrera ny användare</a>
                            </div>
                        </form>
                        <form action='?" . self::$registerKey . "' method='post'>
                            <div class='registerfield'>
                                <input type='text' name='" . self::$usernameKey . "' placeholder='Användarnamn'  value='$username' /><br>
                                <input type='password' name='" . self::$passwordKey . "' placeholder='Lösenord' /><br/>
                                <input type='password' name='" . self::$secondPasswordKey . "' placeholder='Bekräfta Lösenord' /><br/>
                                <input type='submit' value='" . self::$registerFormSubmitKey . "' /><br/>
                                <a href='#' class='login'>Logga in med en existerande användare</a>
                            </div>
                        </form>
                    </div>
                    <div class='loggedin'>$this->UserLoggedString</div>
                    <a href='#' class='togglepanel'>Visa Panel</a>
                </div>";
        
        echo $str;
    }
    
    /**
    * Function that creates the logged out html
    */
    public function GetLoggedOutScreen()
    {
        $str = "<div class='container'>
                    <article>
                        <header>
                            <h1>okForms - Det enkla sättet att skapa formulär!</h1>
                        </header>
                        <section>
                            Hejsan! Du loggar in uppe till höger.
                        </section>
                    </article>
                <div>";
        
        echo $str;
    }
    
    /**
    * @param Subject $subject
    * Observer function that is called by the subject with updated state
    */
    public function Update($subject)
    {
        if($subject->hasError())
        {
            $this->formError = $this->message->get($subject->getError());
        }
        else
        {
            $this->formMessage = $this->message->get($subject->getSuccess());
        }
    }
    
    /**
    * Is the user logging in?
    * @return bool
    */
    public function LoggingIn()
    {
        return isset($_GET[self::$loginKey]);
    }
    
    /**
    * Is the user logging out?
    * @return bool
    */
    public function LoggingOut()
    {
        return isset($_GET[self::$logoutKey]);
    }
    
    /**
    * Is the user registering?
    * @return bool
    */
    public function Registering()
    {
        return isset($_GET[self::$registerKey]);
    }
    
    /**
    * Function to return a User object containing relevant information or set error message
    * @return User
    */
    public function GetUser()
    {       
        if(isset($_POST[self::$usernameKey]) && 
            isset($_POST[self::$passwordKey]))
        {
            $u = $_POST[self::$usernameKey];
            $p = $_POST[self::$passwordKey];
                
            if($this->Registering())
            {
                if($this->CheckInput($u, "user") && $this->CheckInput($p, "pass"))
                {
                    if($p != $_POST[self::$secondPasswordKey])
                    {
                        $this->formError = $this->message->get(5);
                    }
                    elseif(strip_tags($_POST[self::$usernameKey]) != $_POST[self::$usernameKey])
                    {
                        $this->formError = $this->message->get(24);
                    }
                    else
                    {
                        return new User($u, $p);
                    } 
                }
            }
            elseif($this->LoggingIn())
            {
                if(empty($_POST[self::$usernameKey]))
                {
                    $this->formError = $this->message->get(3);
                }
                elseif(empty($_POST[self::$passwordKey]))
                {
                    $this->formError = $this->message->get(4);
                }
                else
                {
                    return new User($u, $p);
                }
            }
        }
    }
    
    /**
    * Get username from $_POST
    * @return string
    */
    public function GetUsername()
    {
        if(isset($_POST[self::$usernameKey]))
            return $_POST[self::$usernameKey];
    }
    
    /**
    * Get id from $_POST if it's set otherwise -1
    * @return int
    */
    public function GetId()
    {
        if(isset($_GET[self::$idKey]))
            return $_GET[self::$idKey];
        
        return -1;
    }
    
    /**
    * Function to check the input of a $_POST value
    * @param string $str
    * @param string $which
    */
    public function CheckInput($str, $which)
    {
        switch($which)
        {
            case "user":
                if(strlen($str) < self::USERNAME_MIN_CHAR)
                {
                    $this->formError = sprintf($this->message->get(7), self::USERNAME_MIN_CHAR); 
                    return false;
                }
                if(strlen($str) > self::USERNAME_MAX_CHAR)
                {
                    $this->formError = sprintf($this->message->get(8), self::USERNAME_MAX_CHAR); 
                    return false;
                }
                break;
                
            case "pass":
                if(strlen($str) < self::PASSWORD_MIN_CHAR)
                {
                    $this->formError = sprintf($this->message->get(9), self::PASSWORD_MIN_CHAR); 
                    return false;
                }
                if(strlen($str) > self::USERNAME_MAX_CHAR)
                {
                    $this->formError = sprintf($this->message->get(10), self::PASSWORD_MAX_CHAR);
                    return false;
                }
                break;
        }
        return true;
    }
    
    /**
    * Does the browser hold a cookie with the right name?
    */
    public function HasCookie()
    {
        return isset($_COOKIE[self::$cookieKey]);
    }
    
    /**
    * Does the user want to save his/her login information?
    * @return bool
    */
    public function SaveLogin()
    {
        return isset($_POST[self::$saveLoginKey]);
    }
    
    /**
    * Sets the cookie with user information and cookietime
    * @param User $user
    * @param int $cookieLength
    */
    public function SetCookie(User $user, $cookieLength = 0)
    {
        $id = $user->GetId();
        $options = array('cost' => 11);
        $hash = password_hash("$id", PASSWORD_BCRYPT, $options) . ":" .
                password_hash($user->GetIp(), PASSWORD_BCRYPT, $options) . ":" .
                password_hash($user->GetAgent(), PASSWORD_BCRYPT, $options);
        setcookie(self::$cookieKey, $hash, time() + $cookieLength, "/", $_SERVER['SERVER_NAME']);
    }
    
    /**
    * Unsets the cookie
    */
    public function UnsetCookie()
    {
        setcookie(self::$cookieKey, "", 1, "/");
    }
    
    /**
    * Gets the cookie
    * @return string
    */
    public function GetCookie()
    {
        return $_COOKIE[self::$cookieKey];
    }
}

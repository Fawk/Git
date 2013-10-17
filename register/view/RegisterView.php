<?php

namespace register\view;

/**
 * RegisterView short summary.
 *
 * RegisterView description.
 *
 * @version 1.0
 * @author Fawk
 */

require_once("./common/Filter.php");

class RegisterView
{
    private static $registerKey = "register";
    private static $registeringKey = "doregister";
    private static $userNameKey = "RegisterView::UserName";
    private static $passwordKey = "RegisterView::Password";
    private static $secondPasswordKey = "RegisterView::Password::2";
    private $errorMessages = array("Lösenorden matchar inte!", "Användarnamnet är redan upptaget!", "Användarnamnet har ogiltiga tecken!");
    
    private $message = "";
    
    public function getRegisterForm()
    {
        $html = "
			<form action='?" . self::$registeringKey . "' method='post' enctype='multipart/form-data'>
				<fieldset>
					$this->message
					<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
					<label for='UserNameID'>Användarnamn :</label>
					<input type='text' size='20' name='" . self::$userNameKey . "' id='UserNameID' value='" . $this->getUserName() ."' /><br/>
					<label for='PasswordID'>Lösenord  :</label>
					<input type='password' size='20' name='" . self::$passwordKey . "' id='PasswordID' /><br/>
                    <label for='Password2'>Upprepa lösenord:</label>
                    <input type='password' size='20' name='" .self::$secondPasswordKey . "' id='Password2' /><br/>
					<input type='submit' value='Registera' />
				</fieldset>
			</form>";
        
        return $html;
    }
    
    public function getReturnLink()
    {
        return "<a href='?'>Tillbaka</a>";
    }
    
    public function doesUserWantToRegister()
    {
        return isset($_GET[self::$registerKey]);
    }
    
    public function isRegistering()
    {
        return isset($_GET[self::$registeringKey]);
    }
    
    public function getPassword()
    {
        return $this->returnValidString(self::$passwordKey);
    }
    
    public function getSecondPassword()
    {   
        return $this->returnValidString(self::$secondPasswordKey);
    }
    
    public function getUserName()
    {
        return $this->returnValidString(self::$userNameKey);
    }
    
    private function returnValidString($str)
    {
        if(isset($_POST[$str]))
        {
            return \common\Filter::sanitizeString($_POST[$str]);
        }
        return "";
    }
    
    /**
     * @return UserCredentials
     */
	public function getUserCredentials() 
    {
        if(\common\Filter::hasTags($_POST[self::$userNameKey]))
        {
            $this->setMessage(2);
        }
        else
        {           
            $user = new \login\model\UserName($this->getUserName());
            $password = new \login\model\Password($this->getPassword());
            
            if(!$user->hasError() && !$password->hasError())
            {
                return \login\model\UserCredentials::createFromClientData($user, $password);
            }
            else
            {
                $this->message .= $user->getError() . $password->getError();
            }
        }
	}
    
    public function setMessage($key)
    {
        $this->message = $this->errorMessages[$key];
    }
    
    public function RegisterSuccess()
    {
        return "Registrering av ny användare lyckades";
    }
}

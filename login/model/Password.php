<?php

namespace login\model;

class Password {
    
	const MINIMUM_PASSWORD_CHARACTERS = 6;
	const MAXIMUM_PASSWORD_CHARACTERS = 16;

	/**
	 * @var String
	 */
	private $encryptedPassword;
    
    private $error = "";
    private $errorStrings;
    private static $passwordLessError;
    private static $passwordMoreError;

	public function __construct($cleartext = "", $isEnc = false) {
        
        self::$passwordLessError = "Lösenordet har för få tecken. Minst " . self::MINIMUM_PASSWORD_CHARACTERS . " tecken!";
        self::$passwordMoreError = "Lösenordet har för många tecken. Max " . self::MAXIMUM_PASSWORD_CHARACTERS . " tecken!";
        
        $this->errorStrings = array(self::$passwordLessError, self::$passwordMoreError);
        
        if(!$isEnc)
        {         
            switch(self::isOkPassword($cleartext)) {
                case 0:
                    $this->error = $this->errorStrings[0] . "<br/>";
                    break;
                case 1:
                    $this->error = $this->errorStrings[1] . "<br/>";
            }
            
            if($this->hasError())
            {
                return $this;
            }
            else
            {
                $this->encryptedPassword = $this->encryptPassword($cleartext, "kvittar ju vad jag skriver här.....");
                return $this;
            }
        }
        else
        {
            $this->encryptedPassword = $cleartext;
            return $this;
        }
	}

	/**
	 * Create password from encrypted String
	 * @param  String $encryptedPassword
	 * @return Password
	 */
	public static function fromEncryptedString($encryptedPassword) {
		return new Password($encryptedPassword, true);	
	}

	/**
	 * Create empty/nonvalid password 
	 * @return Password
	 */
	public static function emptyPassword() {
		return new Password();
	}

	/**
	 * @return String
	 */
	public function __toString() {
		return $this->encryptedPassword;
	}

	

	/**
	 * @param  String  $string 
	 * @return boolean         
	 */
	private static function isOkPassword($string) {
		
		if (strlen($string) < self::MINIMUM_PASSWORD_CHARACTERS) {
			return 0;
		} else if (strlen($string) > self::MAXIMUM_PASSWORD_CHARACTERS) {
			return 1;
		}
		return -1;
	}
	
	/**
	 * @param  String $rawPassword 
	 * @return String              
	 */
	private function encryptPassword($rawPassword, $salt) {
		return sha1($rawPassword . "Password_SALT");
	}
    
    public function hasError()
    {
        return $this->error != "";
    }
    
    public function getError()
    {
        return $this->error;
    }
}
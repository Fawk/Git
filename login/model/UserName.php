<?php

namespace login\model;

/**
 * Represents a valid Username
 */
class UserName {
    
	const MINIMUM_USERNAME_LENGTH = 3;
	const MAXIMUM_USERNAME_LENGTH = 9;
    
    private $error = "";
    private static $userLessError;
    private static $userMoreError;
    private $errorStrings;

	/**
	 * @param String $userName
	 * @throws Exception if not ok
	 */
	public function __construct($userName) {
        
        self::$userLessError = "Användarnamnet har för få tecken. Minst " . self::MINIMUM_USERNAME_LENGTH . " tecken!";
        self::$userMoreError = "Användarnamnet har för många tecken. Max " . self::MAXIMUM_USERNAME_LENGTH . " tecken!";
        
        $this->errorStrings = array(self::$userLessError, self::$userMoreError);
        
		switch($this->isOkUserName($userName)) {
			case 0:
                $this->error = $this->errorStrings[0] . "<br/>";
                break;
            case 1:
                $this->error = $this->errorStrings[1] . "<br/>";
                break;
		}
        if($this->hasError())
        {
            $this->userName = "";
        }
        else
        {
            $this->userName = $userName;
        }
	}

	/**
	 * @return String
	 */
	public function __toString() {
		return $this->userName;
	}

	/**
	 * @param  String  $string 
	 * @return boolean         
	 */
	private function isOkUserName($string) {
        if (strlen($string) < self::MINIMUM_USERNAME_LENGTH) {
			return 0;
		} else if (strlen($string) > self::MAXIMUM_USERNAME_LENGTH) {
			return 1;
		}
        return -1;
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
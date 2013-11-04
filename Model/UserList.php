<?php

require_once("User.php");
require_once("FileSystem.php");
require_once("Subject.php");
require_once("SessionInfo.php");

/**
 * UserList short summary.
 *
 * UserList description.
 *
 * @version 1.0
 * @author Fawk
 */
class UserList extends Subject
{
    /**
    * @var Array<User> $allUsers
    */
    private $allUsers;
    
    /**
    * @var string $sessionKey
    */
    private static $sessionKey = "auth::user";
    
    /**
    * Function to load all users to the UserList
    */
    public function LoadUsers()
    {
        $this->allUsers = FileSystem::loadUsers();
    }
    
    /**
    * Get all users
    * @return Array<User>
    */
    public function GetList()
    {
        return $this->allUsers;
    }
    
    /**
    * Get certain user by id
    * @param int $id
    * @return int $id
    */
    public function GetUser($id)
    {
        foreach($this->allUsers as $user)
        {
            if($user->GetId() == $id)
            {
                return $user;
            }
        }
    }
    
    /**
    * Function to handle register
    * @param User $user
    * @return bool
    */
    public function Registering(User $user)
    {
        if($user != NULL)
        {
            if($this->UserAlreadyExist($user))
            {
                $this->setError(11);
            }
            else
            {
                $user->SetId($this->getNextId());
                $user->HashPassword();
                $this->allUsers[] = $user;
                $this->Save();
                $this->setSuccess(0);
                return true;
            }
        }
        return false;
    }
    
    /**
    * Does user already exist?
    * @param User $other
    * @return bool
    */
    private function UserAlreadyExist(User $other)
    {
        foreach($this->allUsers as $user)
        {
            if($user->GetUsername() == $other->GetUsername())
                return true;
        }      
        return false;
    }

    /**
    * Function check if the user logging in is valid and set information to session
    * @param User $other
    * @param bool $saveLogin
    * @param int $cookieLength
    * @return User
    */
    public function IsUserValid(User $other, $saveLogin, $cookieLength)
    {
        foreach($this->allUsers as $user)
        {
            if($user->CheckValid($other))
            {
                if($saveLogin)
                {
                    $user->SetAgent($_SERVER['HTTP_USER_AGENT']);
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $id = $user->GetId();
                    $user->SetIp("$ip");
                    $user->SetCookieTime(time() + $cookieLength);

                }
                $this->SetSession($user);
                return $user;
            }
        }
    }
    
    /**
    * Save all users
    */
    public function Save()
    {
        FileSystem::saveUsers($this->allUsers);
    }
    
    /**
    * Function to get next available id for a user
    * @return int
    */
    public function getNextId()
    {
        $id = 0;
        if(count($this->allUsers) != 0)
        {
            foreach($this->allUsers as $user)
            {
                if($user->getId() > $id)
                    $id = $user->getId();
            }
        }
        return $id + 1;
    }
    
    /**
    * Function to set a SessionInfo object containing information and a user object to the session
    * @param User $user
    */
    public function SetSession(User $user)
    {   
        $_SESSION[self::$sessionKey] = new SessionInfo($user);
    }
    
    /**
    * Unset the active session
    */
    public function UnsetSession()
    {
        unset($_SESSION[self::$sessionKey]);
    }
    
    /**
    * Does the session exist?
    * @return bool
    */
    public function IsUserAuthed()
    {
        return isset($_SESSION[self::$sessionKey]);
    }
    
    /**
    * Get the SessionInfo object from the session
    * @return SessionInfo
    */
    public function GetSessionInfo()
    {
        return $_SESSION[self::$sessionKey];
    }
    
    /**
    * Check whether the cookie is valid towards the hash or not and return the user which it matches
    * @param string $cookie
    * @return User
    */
    public function IsCookieValid($cookie)
    {
        foreach($this->allUsers as $user)
        {
            if($user->CompareCookieHash($cookie))
            {
                return $user;
            }
        }
        $this->userList->setError(2);
    }
}

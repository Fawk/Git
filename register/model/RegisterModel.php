<?php

namespace register\model;

require_once("/login/model/UserList.php");

/**
 * RegisterModel short summary.
 *
 * RegisterModel description.
 *
 * @version 1.0
 * @author Fawk
 */

class RegisterModel
{
    private $allUsers;
    
    public function __construct()
    {
        $this->allUsers = new \login\model\UserList();
    }
    
    public function isPasswordsTheSame($p1, $p2)
    {
        return $p1 == $p2;
    }
    
    public function saveRegisterInfo($credentials)
    {
        $credentials->newTemporaryPassword();
        $this->allUsers->update($credentials);
        return true;       
    }
    
    public function isSameUserName($user)
    {
        return $this->allUsers->isSameUserName($user);
    }
}

<?php

namespace login\model;


require_once("UserCredentials.php");
require_once("common/model/PHPFileStorage.php");

/**
 * represents All users in the system
 *

 */
class UserList {
	/**
	 * @var \common\model\PHPFileStorage
	 */
	private $usersFile;

	/**
	 * We only have one user in the system right now.
	 * @var array of UserCredentials
	 */
	private $users;


	public function  __construct( ) {
		$this->users = array();
		
		$this->loadUsers();
	}

	/**
	 * Do we have this user in this list?
	 * @throws  Exception if user provided is not in list
	 * @param  UserCredentials $fromClient
	 * @return UserCredentials from list
	 */
	public function findUser($fromClient) {
  
		foreach($this->users as $user) {          
			if ($user->isSame($fromClient) ) {
				\Debug::log("found User");
				return  $user;
			}
		}
		throw new \Exception("could not login, no matching user");
	}
    
    public function isSameUserName($other)
    {
        foreach($this->users as $user)
        {
            if($user->isSameUserName($other))
                return true;
        }
        return false;
    }

	public function update($changedUser) {
		//this user needs to be saved since temporary password changed
		$this->usersFile->writeItem($changedUser->getUserName(), $changedUser->toString());

		\Debug::log("wrote changed user to file", true, $changedUser);
		$this->users[$changedUser->getUserName()->__toString()] = $changedUser;
	}

    private function loadUsers()
    {
        $this->usersFile = new \common\model\PHPFileStorage("data/admin.php");
        
        $users = $this->usersFile->readAll();
        
        foreach($users as $key => $value)
        {
            $user = UserCredentials::fromString($value);
            $this->users[$user->getUserName()->__toString()] = $user;
        }
    }
}
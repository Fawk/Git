<?php

require_once("./Model/UserList.php");
require_once("./View/UserView.php");

/**
 * UserController short summary.
 *
 * UserController description.
 *
 * @version 1.0
 * @author Fawk
 */
class UserController
{
    /**
    * @var UserView $view
    * @var UserList $userList
    */
    private $view;
    private $userList;
    
    /**
    * Construct function that takes a UserView as param and inits the UserList, 
    * loads all users and attaches the view as observer
    * @param UserView $view
    */
    public function __construct(UserView $view)
    {
        $this->view = $view;
        $this->userList = new UserList();
        $this->userList->LoadUsers();
        $this->userList->attach($this->view);
    }
    
    /**
    * Function to determine which state the user is in, whether it be logging in, out, registering or authed
    * Also sets the cookieLength passed along if the user wants to save their login
    * @param int $cookieLength
    */
    public function toggleLoginOrRegister($cookieLength)
    {
        if($this->view->LoggingIn() && !$this->userList->IsUserAuthed())
        {  
            $user = $this->view->GetUser();
            if($user != null)
            {
                $u = $this->userList->IsUserValid($user, $this->view->SaveLogin(), $cookieLength);
                if($u != null)
                {
                    if($this->view->SaveLogin())
                    {
                        $this->view->SetCookie($u, $cookieLength);
                    }
                    $this->userList->Save();
                    $this->view->SetLoggedInUser($u);
                }
                else
                {
                    $this->userList->setError(6);
                }
            }
        }
        elseif($this->view->LoggingOut())
        {
            $this->userList->UnsetSession();
            $this->view->UnsetCookie();
        }
        elseif($this->view->Registering() && !$this->userList->IsUserAuthed())
        {
            $user = $this->view->GetUser();
            if($user != null)
            {
                $this->userList->Registering($user);
            }
        }
        else
        {
            if($this->userList->IsUserAuthed())
            {
                $sessionInfo = $this->userList->GetSessionInfo();
                if($sessionInfo->isSameSession())
                {
                    $this->view->SetLoggedInUser($sessionInfo->user);
                }
            }
            elseif($this->view->HasCookie())
            {
                $user = $this->userList->IsCookieValid($this->view->GetCookie());
                if($user != null)
                {
                    $this->userList->SetSession($user);
                    $this->view->SetLoggedInUser($user);
                }
                else
                {
                    $this->view->SetCookie();
                }
            }
        }
        $this->view->MakeForm($this->view->GetUserName());
        if($this->view->LoggingOut() || !$this->userList->IsUserAuthed())
            $this->view->GetLoggedOutScreen();
    }
    
    /**
    * Is the user authed?
    * @return bool
    */
    public function IsUserAuthed()
    {
        return $this->userList->IsUserAuthed();
    }
    
    /**
    * Return the authed user from the session
    * @return User
    */
    public function GetAuthedUser()
    {
        return $this->userList->GetSessionInfo()->user;
    }
    
    /**
    * Get a user with certain id
    * @param int $userid
    * @return User
    */
    public function GetUserFromId($userid)
    {
        $id = $this->view->GetId();
        $id == -1 ? $id = $userid : "";
        return $this->userList->GetUser($id);
    }
}

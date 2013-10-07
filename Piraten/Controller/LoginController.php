<?php

require_once("./Model/LoginModel.php");
require_once("./View/LoginView.php");

/**
 * LoginController short summary.
 *
 * LoginController description.
 *
 * @version 1.0
 * @author ok222ax
 */
class LoginController {

    /**
    * @var 
    */
    private $loginView;
    
    /**
    * @var 
    */
    private $loginModel;

    public function __construct() {
    
        $this->loginView = new LoginView();
        $this->loginModel = new LoginModel();
        $this->handleInput();
    }
    
    public function handleInput()
    {
        if($this->loginView->isLoggingIn())
        {
            $username = $this->loginView->getUser();
            $password = $this->loginView->getPassword();
        
            $this->handleLogin($username, $password);
        } 
        
        elseif($this->loginView->isLoggingOut())
        {
            $this->login->setSession();
        }
        
        else 
        {
            if($this->loginView->isAuthed())
            {
                $this->loginView->generateLogout();
            } 
            else 
            {
                $this->loginView->generateForm();
            }    
        }
    }
    
    public function handleLogin($user, $pass)
    {
        if($this->login->checkLogin($user, $pass))
        {
            $this->login->setSession();
            $this->loginView->handleMessage("LOGIN_SUCCESS");
        } 
        else 
        {
            $this->loginView->handleMessage("LOGIN_FAILED", true);
        }
    }
}

<?php

require_once("./Model/LoginModel.php");
require_once("./View/LoginView.php");
require_once("./Model/Message.php");

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
    
    private $message;

    public function __construct() {
    
        $this->loginView = new LoginView();
        $this->loginModel = new LoginModel();
        $this->message = new Message();
        $this->handleInput();
    }
    
    public function isAuthed()
    {
        return $this->loginModel->isAuthed();
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
            $this->loginModel->setSession();
        }
        
        else 
        {
            if($this->loginModel->isAuthed())
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
        if($this->loginModel->checkLogin($user, $pass))
        {
            $this->loginModel->setSession();
            $this->loginView->printMessage($this->message->fetchMessage(0));
        } 
        else 
        {
            $this->loginView->printMessage($this->message->fetchMessage(2), true);
        }
    }
}

<?php

namespace register\controller;

/**
 * RegisterController short summary.
 *
 * RegisterController description.
 *
 * @version 1.0
 * @author Fawk
 */

require_once("/register/view/RegisterView.php");
require_once("/register/model/RegisterModel.php");

class RegisterController
{
    private $view;
    private $model;
    
    public function __construct(\register\view\RegisterView $view)
    {
        $this->view = $view;
        $this->model = new \register\model\RegisterModel();
    }
    
    public function doesUserWantToRegister()
    {
        return $this->view->doesUserWantToRegister();
    }
    
    public function isRegistering()
    {
        return $this->view->isRegistering();
    }
 
    public function getRegisterFeedBack()
    {
        $password = $this->view->getPassword();
        $secondpass = $this->view->getSecondPassword();
        
        if($this->model->isPasswordsTheSame($password, $secondpass))
        {
            $credentials = $this->view->getUserCredentials();
            
            if($credentials == NULL)
            {
                return $credentials;
            }
            if($this->model->isSameUserName($credentials->getUserName()))
            {
                $this->view->setMessage(1);
            }
            else
            {
                if($this->model->saveRegisterInfo($credentials))
                {
                    return $this->view->RegisterSuccess();
                }
            }         
        }
        else
        {
            $this->view->setMessage(0);
        }
    }
}

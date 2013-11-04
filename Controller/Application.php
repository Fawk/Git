<?php

require_once("UserController.php");
require_once("FormController.php");

/**
 * Application short summary.
 *
 * Application description.
 *
 * @version 1.0
 * @author Fawk
 */
class Application
{
    /**
    * @var UserController $userController
    * @var FormController $formController
    */
    private $userController;
    private $formController;
    
    /**
    * Construct function inits the controllers and passes on the applied cookielength
    * @param int $cookieLength
    */
    public function __construct($cookieLength)
    {      
        $this->userController = new UserController(new UserView());
        $this->formController = new FormController(new FormView());
        $this->doApp($cookieLength);
    }
    
    /**
    * Main function for the application which calls appropriate functions on both controllers to control everything
    * @param int $cookieLength
    */
    public function doApp($cookieLength)
    {
        $this->userController->toggleLoginOrRegister($cookieLength);
        if($this->userController->IsUserAuthed())
        {
            $user = $this->userController->GetAuthedUser();
            $userfromid = $this->userController->GetUserFromId($this->formController->GetUserIdFromForm());
            if($userfromid == null)
            {
                $userfromid = $user;
            }
            $this->formController->toggleFormAction($user, $userfromid);
        }
    }
}

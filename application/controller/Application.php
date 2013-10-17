<?php

namespace application\controller;

require_once("application/view/View.php");
require_once("login/controller/LoginController.php");
require_once("/register/controller/RegisterController.php");

/**
 * Main application controller
 */
class Application {
	/**
	 * \view\view
	 * @var [type]
	 */
	private $view;

	/**
	 * @var \login\controller\LoginController
	 */
	private $loginController;
    
    /**
     * @var \register\controller\RegisterController
     */
	private $registerController;
	
	public function __construct() {
		$loginView = new \login\view\LoginView();
		$registerView = new \register\view\RegisterView();
		$this->loginController = new \login\controller\LoginController($loginView);
        $this->registerController = new \register\controller\RegisterController($registerView);
		$this->view = new \application\view\View($loginView, $registerView);
	}
	
	/**
	 * @return \common\view\Page
	 */
	public function doFrontPage() {
        
		$this->loginController->doToggleLogin();
        
        if($this->registerController->doesUserWantToRegister())
        {
            return $this->view->getRegisterPage();
        }
	    elseif($this->registerController->isRegistering())
        {
            $result = $this->registerController->getRegisterFeedBack();
            if($result == NULL)
            {
                return $this->view->getRegisterPage();
            }
            return $this->view->getLoggedOutPage($result);
        }
        else
        {
            if ($this->loginController->isLoggedIn()) {
                $loggedInUserCredentials = $this->loginController->getLoggedInUser();
                return $this->view->getLoggedInPage($loggedInUserCredentials);	
            } else {
                return $this->view->getLoggedOutPage();
            }
        }
	}
}

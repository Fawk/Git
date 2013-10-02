<?php 

require_once("./model/Login.php");
require_once("./view/LoginView.php");

class Controller {

	/**
	* @var Login
	*/
	private $login;

	/**
	* @var LoginView
	*/
	private $loginView;

	/**
	* @var integer
	*/
	private $cookieLength;

	/**
	* @var String
	*/
	private $userName;

	/**
	* @param integer - cookie alivetime in seconds
	*/
	public function __construct($cookieLength) {

		$this->login = new Login();
		$this->loginView = new LoginView();
		$this->cookieLength = $cookieLength;
		$this->handleInput();
	}

	/**
	* The main handle function which is started at each page call
	*/
	private function handleInput() {

		try {

			if($this->loginView->isLoggingIn() && !$this->login->isAuthed()) {

				$this->handleLogin();
				
			} elseif($this->loginView->isLoggingOut() && $this->login->isAuthed()) {

				$this->login->unsetAuthSession();
				$this->loginView->handleMessage("LOGGED_OUT");
				$this->login->setLoginCookies();
				$this->loginView->generateForm();

			} else {
				
				if($this->login->isAuthed()) {

					$this->loginView->handleMessage("ADMIN_LOGGED");
					$this->loginView->generateLogout();

				} elseif($this->login->loginCookieStored()) {

					if($this->login->loginCookieValid()) {

						$this->loginView->handleMessage("COOKIES_LOGGED");
						$this->loginView->generateLogout();
						$this->login->setAuthSession();

					} else {

						$this->loginView->handleMessage("COOKIES_INVALID", true);
						$this->loginView->generateForm();
						$this->login->setLoginCookies();
					}

				} else {
					
					$this->loginView->generateForm($this->userName);
				}
			}			
		} catch (Exception $ex) {

		}
	}

	/**
	* Function to handle a login call and determine if user is elligable or not
	*/
	private function handleLogin() {

		$this->userName = $this->loginView->getUser();
		$password = $this->loginView->getPassword();

		$EmptyError = $this->login->checkEmpty($this->userName, $password);
					
		if(!empty($EmptyError)) {

			$this->loginView->handleMessage($EmptyError, true);
			$this->loginView->generateForm($this->userName);

		} else {

			if($this->login->checkLogin($this->userName, $password)) {
						
				$this->login->setAuthSession();

				if($this->loginView->userSavedLogin()) {

					$this->login->setLoginCookies($this->userName, md5($password), $this->cookieLength);
					$this->loginView->handleMessage("SUCCESS_COOKIES");

				} else {

					$this->loginView->handleMessage("SUCCESS");
				}

				$this->loginView->generateLogout();

			} else {

				$this->loginView->handleMessage("INVALID_CRED", true);
				$this->loginView->generateForm();
			}
		}
	} 
}


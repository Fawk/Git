<?php 

require_once("./model/Login.php");
require_once("./view/LoginView.php");
require_once("./model/ClientInfo.php");

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
	* @var ClientInfo
	*/
	private $clientInfo;

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
		$this->login->attach($this->loginView);
		$this->cookieLength = $cookieLength;
		$this->clientInfo = new ClientInfo();
		$this->handleInput();
	}

	/**
	* The main handle function which is started at each page call
	*/
	private function handleInput() {

		if($this->loginView->isLoggingIn() && !$this->login->isAuthed($this->clientInfo)) {

			$this->handleLogin();
			
		} elseif($this->loginView->isLoggingOut() && $this->login->isAuthed($this->clientInfo)) {

			if($this->loginView->loginCookieStored()) {
				$this->loginView->setLoginCookies();
			}

			$this->login->unsetAuthSession($this->clientInfo);
			$this->login->setLoginCookies();
			$this->login->setState(0);
			$this->loginView->generateForm();

		} else {
			
			if($this->login->isAuthed($this->clientInfo)) {

				$this->login->setState(1);
				$this->loginView->generateLogout();

			} elseif($this->loginView->loginCookieStored()) {

				if($this->loginView->loginCookieValid($this->clientInfo)) {

					if($this->login->checkLogin(
						$this->loginView->getCookieUser(), 
						$this->loginView->getCookiePassword(), 
						true)) {

						$this->login->setState(2);
						$this->loginView->generateLogout();
						$this->login->setAuthSession($this->clientInfo);

					} else {
						
						$this->login->setState(3, true);
						$this->loginView->setLoginCookies();
						$this->loginView->generateForm();
					}

				} else {

					$this->loginView->generateForm();
					$this->login->setLoginCookies();
					$this->loginView->setLoginCookies();
				}

			} else {
				
				$this->loginView->generateForm($this->userName);
			}
		}			
	}

	/**
	* Function to handle a login call and determine if user is elligable or not
	*/
	private function handleLogin() {

		$this->userName = $this->loginView->getUser();
		$password = $this->loginView->getPassword();

		$EmptyError = $this->loginView->checkEmpty($this->userName, $password);
		
		if($EmptyError != -1)
		{
			switch($EmptyError)
			{
				case 0:
					$this->login->setState(4, true);
					break;
				case 1:
					$this->login->setState(5, true);
					break;
			}

				$this->loginView->generateForm($this->userName);

		} else {

			if($this->login->checkLogin($this->userName, $password)) {
						
				$this->login->setAuthSession($this->clientInfo);

				if($this->loginView->userSavedLogin()) {

					$this->loginView->setLoginCookies($this->cookieLength);
					$this->login->setLoginCookies($this->userName, md5($password), $this->cookieLength);
					$this->login->setState(7);

				} else {

					$this->login->setState(7);
				}

				$this->loginView->generateLogout();

			} else {

				$this->login->setState(8, true);
				$this->loginView->generateForm();
			}
		}
	} 
}


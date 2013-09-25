<?php 

require_once("./model/Login.php");
require_once("./view/LoginView.php");

class Controller {

	private $login;
	private $loginView;
	private $cookieLength;
	private $userName;

	public function __construct($cookieLength) {

		$this->login = new Login();
		$this->loginView = new LoginView();
		$this->cookieLength = $cookieLength;
		$this->handleInput();
	}

	private function handleInput() {

		if($this->loginView->isLoggingIn()) {

			try {

				$username = $_POST["username"];
				$password = $_POST["password"];
				$this->userName = $username;

				$result = $this->login->checkLogin($username, md5($password));
				
				if($result === false) {

					$this->loginView->handleMessage("Wrong username or password", true);
					$this->loginView->generateForm("?login");

				} elseif($result === true) {
					
					$this->login->setAuthSession();
					$this->loginView->handleMessage("Successful login.");

					if($this->loginView->userSavedLogin()) {

						$this->login->setLoginCookies($username, md5($password), $this->cookieLength);
						$this->loginView->handleMessage("Your login has been saved.");
					}

					$this->loginView->generateLogout();

				} else {

					$this->loginView->handleMessage($result, true);
					$this->loginView->generateForm("?login");
				}

			} catch (Exception $ex) {

			}
		} 

		elseif($this->loginView->isLoggingOut()) {

			try {

				$this->login->unsetAuthSession();
				$this->loginView->handleMessage("Logged out.");
				$this->login->setLoginCookies();
				$this->loginView->generateForm("?login");

			} catch(Exception $ex) {


			}

		} else {

			if($this->login->isAuthed()) {

				$this->loginView->handleMessage("Admin is logged in.");
				$this->loginView->generateLogout();

			} elseif($this->login->loginCookieStored()) {

				if($this->login->loginCookieValid()) {

					$this->loginView->handleMessage("Logged in by using cookies.");
					$this->loginView->generateLogout();

				} else {

					$this->loginView->handleMessage("Information in cookies was invalid.", true);
					$this->loginView->generateForm("?login");
				}

			} else {
				
				$this->loginView->generateForm("?login", $this->userName);
			}
		}
	}
}


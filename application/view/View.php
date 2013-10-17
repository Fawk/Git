<?php

namespace application\view;

require_once("common/view/Page.php");
require_once("SwedishDateTimeView.php");

class View {
	/**
	 * @var \Login\view\LoginView
	 */
	private $loginView;
    
    /**
     * @var \Register\view\RegisterView
     */
	private $registerView;

	/**
	 * @var  SwedishDateTimeView $timeView;
	 */
	private $timeView;
	
	/**
	 * @param LoginviewLoginView $loginView 
	 */
	public function __construct($loginView, $registerView) {
		$this->loginView = $loginView;
        $this->registerView = $registerView;
		$this->timeView = new SwedishDateTimeView();
	}
	
	/**
	 * @return \common\view\Page
	 */
	public function getLoggedOutPage($msg = "") {
		$html = $this->getHeader(false);
        
        $html .= $this->loginView->getRegisterLink();
        
		$loginBox = $this->loginView->getLoginBox($msg); 

		$html .= "<h2>Ej Inloggad</h2>
				  	$loginBox
				 ";
		$html .= $this->getFooter();

		return new \common\view\Page("Laboration. Inte inloggad", $html);
	}
	
	/**
	 * @param \login\login\UserCredentials $user
	 * @return \common\view\Page
	 */
	public function getLoggedInPage($user) {
		$html = $this->getHeader(true);
		$logoutButton = $this->loginView->getLogoutButton(); 
		$userName = $user->getUserName();

		$html .= "
				<h2>$userName är inloggad</h2>
				 	$logoutButton
				 ";
		$html .= $this->getFooter();

		return new \common\view\Page("Laboration. Inloggad", $html);
	}
	
	
	/**
	 * @param boolean $isLoggedIn
	 * @return  String HTML
	 */
	private function getHeader($isLoggedIn) {
		$ret =  "<h1>Laborationskod xx222aa</h1>";
		return $ret;
		
	}

	/**
	 * @return [type] [description]
	 */
	private function getFooter() {
		$timeString = $this->timeView->getTimeString(time());
		return "<p>$timeString<p>";
	}
	
	public function getRegisterPage()
    {
        $html = $this->getHeader(false);
        
        $html .= $this->registerView->getReturnLink();
        
        $html .= "<h2>Ej Inloggad, Registrerar ny användare</h2>";
        
        $html .= $this->registerView->getRegisterForm();
        
        $html .= $this->getFooter();

		return new \common\view\Page("Laboration. Registrera ny användare", $html);
    }
}

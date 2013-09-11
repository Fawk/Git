<?php require_once("view/Login.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="sv-se">
<head>
    <link rel="Stylesheet" href="../css/style.css" />
    <title>Laboration 1 Login</title>
</head>
<body>
    <div id="container">
    	<?php session_start(); 
    	
    		$login = new Login();

			switch($_SERVER['QUERY_STRING']) {
				
				case "login":

					$remember = "";	
					$username = $_POST['username'];
					$password = $_POST['password'];

					if(isset($_POST['remember'])) {

						setcookie("username", $username, time() + 3600);
						setcookie("password", md5($password), time() + 3600);

						$remember = "Your login is stored for later use.";
					}
					
					$error = $login->checkEmpty($username, $password);
					
					if(!empty($error)) {
						
						echo $error;
						echo $login->generateForm("?login", $username, $password);
						exit();
					}
					
					if($login->checkLogin($username, $password)) {
						
						echo "Logged in. $remember<br/><br/><a href='?logout'>Logout</a>";
						$_SESSION['login::auth'] = true;

					} else {
						
						echo "Username or password is invalid!<br/>";
						echo $login->generateForm("?login");
					}

					break;
			
				case "logout":

					setcookie("username", "", time() - 3600);
					setcookie("password", "", time() - 3600);

					echo "Logged out.<br/>";
					echo $login->generateForm("?login");
					
					session_destroy();

					break;
					
				default: 

				if(isset($_COOKIE['username']) && isset($_COOKIE['password'])) {

	    			if($login->checkLogin($_COOKIE['username'], $_COOKIE['password'])) {
	    				echo "Logged in by stored information in cookies.<br/><br/><a href='?logout'>Logout</a>";
	    			}
					else {
						echo "Wrong information in cookies";
						setcookie("username", "", time() - 3600);
						setcookie("password", "", time() - 3600);
					}
	    			exit();
	    		}
	
				if(isset($_SESSION['login::auth'])) {

					echo "Welcome, Mr.Bond<br/><br/><a href='?logout'>Logout</a>";
					exit();
					
				}

	    		echo $login->generateForm("?login");
			}
    	?>
   	</div>
</body>
</html>

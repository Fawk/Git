<?php require_once("view/Login.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="sv-se">
<head>
    <link rel="Stylesheet" href="../css/style.css" />
    <title>Laboration 1 Login</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
    <div id="container">
		<?php session_start();

			setlocale(LC_ALL, sv_SE);
    	
    		$login = new Login();

			switch($_SERVER["QUERY_STRING"]) {
				
				case "login":

					$remember = "";	
					$username = $_POST["username"];
					$password = $_POST["password"];
					$error = $login->checkEmpty($username, $password);

					if(isset($_SESSION["login_auth"])) {

						echo "You are already logged in.<br/><br/><a href='?logout'>Logout</a>";
					}

					else if(isset($_POST["remember"])) {

						setcookie("username", $username, time() + 3600);
						setcookie("password", md5($password), time() + 3600);

						$remember = "Your login is stored for later use.";
					}

					else if(!empty($error)) {
						
						echo $error . "<br/><br/>";
						echo $login->generateForm("?login", $username);

					} else if($login->checkLogin($username, md5($password))) {
						
						echo "Admin is logged in.<br/><br/>Login successful. $remember<br/><br/><a href='?logout'>Logout</a><br/>";
						$_SESSION["login_auth"] = rand(1,99999);

					} else {
						
						echo "Username or password is invalid!<br/><br/>";
						echo $login->generateForm("?login", $username);
					}

					break;
			
				case "logout":

					setcookie("username", "", time() - 3600);
					setcookie("password", "", time() - 3600);

					echo "Logged out.<br/>";
					echo $login->generateForm("?login");
					
					unset($_SESSION["login_auth"]);

					break;
					
				default: 

	    		if(isset($_SESSION["login_auth"])) {

					echo "Admin is logged in.<br/><br/><a href='?logout'>Logout</a><br/>";	

				} else if(isset($_COOKIE["username"]) && isset($_COOKIE["password"])) {

	    			if($login->checkLogin($_COOKIE["username"], $_COOKIE["password"])) {

	    				echo "Logged in by stored information in cookies.<br/><br/><a href='?logout'>Logout</a><br/>";

	    			} else {

						echo "Wrong information in cookies";
						setcookie("username", "", time() - 3600);
						setcookie("password", "", time() - 3600);
					}

				} else {

					echo "Not logged in.<br/><br/>";
	    			echo $login->generateForm("?login");
	    		}
			}

			echo "<br/>" . strftime("%A, den %e %B år %Y. Klockan är [%T]") . "<br/>"; 
		?>
   	</div>
</body>
</html>

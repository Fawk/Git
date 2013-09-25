<?php require_once("controller/Controller.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="sv-se">
<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Laboration 1 Login</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
    <div id="container">
		<?php
		
    		$loginController = new Controller(3600);

		?>
		<div class="clock">
			<div id="Date"></div>
  			<ul>
				<li id="hours"></li>
			    <li id="point">:</li>
			    <li id="min"></li>
			    <li id="point">:</li>
			    <li id="sec"></li>
  			</ul>
		</div>
   	</div>
   	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
   	<script type="text/javascript" src="js/clock.js"></script>
</body>
</html>

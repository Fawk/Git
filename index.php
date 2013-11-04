<?php

require_once("Controller/Application.php");
require_once("View/PageView.php");

session_start();

$pageview = new PageView();
$pageview->AddStylesheet("css/master.less");
$pageview->AddJavascript("js/jquery-2.0.3.min.js");
$pageview->AddJavascript("js/System.js");
$pageview->AddJavascript("js/bootstrap.min.js");
$pageview->AddJavascript("js/less-1.5.0.min.js");
$pageview->AddJavascript("js/jquery-ui-1.10.3.custom.min.js");
$pageview->AddJavascript("js/bootstrap-select.min.js");

echo $pageview->GetHeader("-- okForms -- ");
$cookieLength = 120;
$app = new Application($cookieLength);
echo $pageview->GetFooter();

?>
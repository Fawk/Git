<?php

require_once("./Model/Message.php");

/**
 * BaseView short summary.
 *
 * BaseView description.
 *
 * @version 1.0
 * @author Fawk
 */
class BaseView
{
    private function getMessageFromEnum($key) {

		return constant('Message::' . $key);
	}
    
    public function handleMessage($msg, $isError = false)
    {
        $msg = $this->getMessageFromEnum($key);
		$class = "";
		$isError ? $class = "error" : $class = "message";

		echo "<div class='$class'>$msg</div><br/><br/>";
    }
}

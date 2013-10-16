<?php

class ClientInfo {

	private $agent;
	private $ip;
	private $sessionKey = "login::auth";
	private $sessionFileLocation = "./9e0db833ed11941b05090b77472b18ab.txt";

	public function __construct()
	{
		$this->agent = $_SERVER["HTTP_USER_AGENT"];
		$this->ip = $_SERVER["REMOTE_ADDR"];
	}

	public function isExistsAndValid($cookiedata)
	{
		return $this->compareFileValues($cookiedata, "cookie");
	}

	public function isSessionValid()
	{
		$data = file_get_contents($this->sessionFileLocation);
		return $this->compareFileValues($data, "session");
	}

	private function getSessionFileKeyIfExists()
	{
		$data = file_get_contents($this->sessionFileLocation);

		$str = explode("\r\n", $data);

		foreach($str as $key => $value)
		{
			if($value != "")
			{
				$e = explode("+", $value);
				if($e[0] == $this->ip && $e[1] == $this->agent)
				{
					return $key;
				}
			}
		}
		return -1;
	}

	private function returnSessionListWithoutKey($key)
	{
		$data = file_get_contents($this->sessionFileLocation);
		$str = explode("\r\n", $data);

		if($key != -1)
		{
			foreach($str as $k => $value)
			{
				if($k == $key)
				{
					unset($str[$k]);
				}
			}
		}

		$data = "";
		foreach($str as $k => $value)
		{
			if($value != "")
			{
				$data .= $value . "\r\n";
			}
		}

		return $data;
	}

	public function setSession()
	{
		$data = "";

		if(filesize($this->sessionFileLocation) > 0)
		{
			$key = $this->getSessionFileKeyIfExists();
			$data = $this->returnSessionListWithoutKey($key);
		}

		$newdata = $data . $this->ip . "+" . $this->agent . "\r\n";

		file_put_contents($this->sessionFileLocation, "");
		file_put_contents($this->sessionFileLocation, $newdata);
	}

	public function unsetSession()
	{
		$key = $this->getSessionFileKeyIfExists();
		$data = $this->returnSessionListWithoutKey($key);
		
		file_put_contents($this->sessionFileLocation, "");
		file_put_contents($this->sessionFileLocation, $data);
	}

	private function compareFileValues($filedata, $which)
	{
		$data = explode("\r\n", $filedata);

		foreach($data as $key => $value)
		{
			if($value != "")
			{
				$d = explode("+", $value);

				if($which == "cookie")
				{
					$time = $d[0] + 0;

					if($time > time() && 
					   $d[1] == $this->ip &&
					   $d[2] == $this->agent)
					{
						return true;
					}
				}
				else
				{
					if($d[0] == $this->ip &&
					   $d[1] == $this->agent)
					{
						return true;
					}
				}
			}
		}
		return false;
	}
}
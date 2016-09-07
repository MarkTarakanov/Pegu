<?php

class sqlConnection
{
	public $mysqli;

	public function __construct()
	{
		$this->mysqli = new mysqli('mysql13.000webhost.com', 'a3035397_Peng', 'pmonibuv123', 'a3035397_Peng');

		// Throw exception instead
		if ($this->mysqli->connect_errno) {
		    echo 'Connect failed: ', $this->mysqli->connect_error, '" }';
		    exit();
		}
	}
}

?>
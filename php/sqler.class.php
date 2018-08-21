<?php
//Class for a MySQL or MS SQL database connection, and accompanying functions.

//class SQLer extends mysqli
class SQLer
{
	var $con;
	var $host = "localhost";
	var $user = "root";
	var $password = "yanzhouhuang";
	var $database = "chat";
	var $resultSet;
	var $numRows = 0;
	var $salt1 = "qml*hui%12JKZ~";
	var $salt2 = "lm<?ghjCF45*(z";
		
	public function __construct() 
	{
		$this->connectMe();
	}

	
	//Creates a connection
	private function connectMe()
	{
		$this->con = new mysqli($this->host, $this->user, $this->password, $this->database);
		if ($this->con->connect_error)
		{
            die('Error connecting: ' . $this->con->connect_errno."\n".$this->con->connect_error);
        }
	}
	
	//Closes the connection.
	function closeMe()
	{
		$this->resultSet->close();
		$this->con->close();
	}

	//Removes suspect code and incompatible characters from the queries.
	function cleanData($SQLData)
	{
		if (get_magic_quotes_gpc())
		{
			$SQLData = stripslashes($SQLData);
		}
		
		$SQLData = $this->con->real_escape_string($SQLData);
		return htmlentities($SQLData);
	}
	
	//Sends a query to the database
	function sendQuery($SQLQuery)
	{
		if(!$this->con)
		{
			$this->connectMe();
		}
		
		//$SQLQuery = $this->cleanQuery($SQLQuery);
		$this->resultSet = $this->con->query($SQLQuery);
		
		if (!$this->resultSet)
		{
			echo "<br/><br/><br/>" . $this->con->error . "<br/><br/><br/>";
		}
		else
		{
			if ($this->numRows === NULL)
				$this->numRows = $this->resultSet->num_rows;
		}
	}
	
	//Return one row from the last query if one exists
	function getRow()
	{
		if($this->resultSet)
		{
			return($this->resultSet->fetch_array(MYSQLI_ASSOC));
		}
		return false;
	}
	
	function hashPass($pass)
	{
		return hash('ripemd160', "$this->salt1.$pass.$this->salt2");
	}

}
?>
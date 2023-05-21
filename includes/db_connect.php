<?php
	function db_connect()
	{
		if (php_sapi_name() === 'cli')
		{
			require(getenv('PWD')."/config.php");
		}
		else
		{
			require($_SERVER['DOCUMENT_ROOT']."/config.php");
		}
		
		$new_database_connection = new mysqli($config["db_host"], $config["db_username"], $config["db_password"], $config["db_name"]);

		if ($new_database_connection->connect_error)
		{
			die("Connection failed: " . $new_database_connection->connect_error);
		}
		else
		{
			return $new_database_connection;
		}
	}

	function db_disconnect($db_connection)
	{
		return $db_connection->close();
	}
?>
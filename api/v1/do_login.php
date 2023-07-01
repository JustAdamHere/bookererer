<?php
	$email    = strip_tags($_POST["email"]);
	$password = strip_tags($_POST["password"]);

	$JSON_response = new stdClass();

	if (!isset($_POST["email"]))
	{
		$JSON_response->status        = "error";	
		$JSON_response->error_message = "missing email address.";
	}
	elseif (!isset($_POST["password"]))
	{
		$JSON_response->status        = "error";	
		$JSON_response->error_message = "missing password.";
	}
	else
	{
		include($_SERVER['DOCUMENT_ROOT']."/includes/db_connect.php");
		$db_connection = db_connect();

		$select_query = $db_connection->query("SELECT `password`, `ID` FROM `logins` WHERE `email` = '".$email."' LIMIT 1");

		if ($select_query->num_rows == 1)
		{
			$select_result = $select_query->fetch_array();
			$db_password   = $select_result[0];
			$ID            = $select_result[1];
			$IP            = $_SERVER['REMOTE_ADDR'];

			if(password_verify($password, $db_password))
			{
				$session_ID = $db_connection->query("SELECT UUID()")->fetch_array()[0];
				$created_session = $db_connection->query("INSERT INTO `logins_sessions` (`ID`, `login_ID`, `start`, `expiry`, `IP`) VALUES ('".$session_ID."', '".$ID."', CURRENT_TIMESTAMP(), DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 7 DAY), '".$IP."')");

				$expiry_date = new DateTime('+1 week');

				// Return error message from query.
				if ($created_session === TRUE)
				{
					$JSON_response->status     = "success";
					$JSON_response->session_ID = $session_ID;
					$JSON_response->expiry     = $expiry_date->format(DateTime::COOKIE);
				}
				else
				{
					$JSON_response->status        = "error";	
					$JSON_response->error_message = "failed to create session: ".$db_connection->error;
				}

			}
			else
			{
				$JSON_response->status        = "error";	
				$JSON_response->error_message = "incorrect password.";		
			}

		}
		elseif ($select_query->num_rows == 0)
		{
			$JSON_response->status        = "error";	
			$JSON_response->error_message = "unknown email address: ".$_POST["email"];
		}
		else
		{
			$JSON_response->status        = "error";	
			$JSON_response->error_message = "failed to select logins: ".$db_connection->error;
		}
	}

	echo json_encode($JSON_response);
?>
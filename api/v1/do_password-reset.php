<?php
	$token    = htmlspecialchars($_POST["token"]);
  $password = htmlspecialchars($_POST["password"]);  

	$JSON_response = new stdClass();

	if (!isset($_POST["token"]))
	{
		$JSON_response->status        = "error";	
		$JSON_response->error_message = "missing token address.";
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

		$reset_token_query = $db_connection->prepare("SELECT `member_ID` FROM `password_reset_tokens` INNER JOIN `logins` ON `password_reset_tokens`.`member_ID`=`logins`.`ID` WHERE `password_reset_tokens`.`token` = ? AND `password_reset_tokens`.`expiry` > UNIX_TIMESTAMP()");
    $reset_token_query->bind_param("s", $token);
    $reset_token_query->execute();
    $reset_token_query->store_result();
    $reset_token_query->bind_result($member_ID);
    $reset_token_query->fetch();

		if ($reset_token_query->num_rows == 1)
		{
			$reset_token_query = $db_connection->prepare("DELETE FROM `password_reset_tokens` WHERE `token` = ?");
      $reset_token_query->bind_param("s", $token);
      $reset_token_query->execute();
      $reset_token_query->close();

      $password_hash = password_hash($password, PASSWORD_DEFAULT);

      $reset_password_query = $db_connection->prepare("UPDATE `logins` SET `password` = ? WHERE `ID` = ?");
      $reset_password_query->bind_param("si", $password_hash, $member_ID);
      $reset_password_query->execute();

      $JSON_response->status = "success";
		}
		elseif ($select_query->num_rows == 0)
		{
			$JSON_response->status        = "error";	
			$JSON_response->error_message = "unknown token: ".$token;
		}
		else
		{
			$JSON_response->status        = "error";	
			$JSON_response->error_message = "failed to select tokens: ".$db_connection->error;
		}
	}

	echo json_encode($JSON_response);
?>
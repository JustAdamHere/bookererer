<?php
  $booking_ID = $_POST["booking-id"];

  $JSON_response = new stdClass();

  include($_SERVER['DOCUMENT_ROOT']."/includes/db_connect.php");
	$db_connection = db_connect();

  $session_query = $db_connection->query("SELECT `logins_sessions`.`login_ID`, `logins`.`user_level` FROM `logins_sessions` INNER JOIN `logins` ON `logins_sessions`.`login_ID`=`logins`.`ID` WHERE `logins_sessions`.`ID`='".$session_id."'");

  if ($session_query)
  {
    if ($user_details["user_level"] >= 1)
    {
      $JSON_response->status = "success";

      $bookings_query = $db_connection->query("SELECT * FROM `bookings` WHERE `booking_ID`=".$booking_ID." LIMIT 1");

      if ($bookings_query)
      {
        $bookings = $bookings_query->fetch_assoc();

        $JSON_response->booking_name    = $bookings["name"];
        $JSON_response->status          = $bookings["status"];
        $JSON_response->ensemble_id     = $bookings["booking_ensemble"];
        $JSON_response->datetime        = $bookings["datetime"];
        $JSON_response->location        = $bookings["location"];
        $JSON_response->update_datetime = $bookings["update_datetime"];
        $JSON_response->updated_by      = $bookings["updated_by"];
        $JSON_response->deleted         = $bookings["deleted"];
      }
      else
      {
        $JSON_response->status        = "error";
        $JSON_response->error_message = "failed to select data; ".$db_connection->error;
      }
    }
    else
    {
      $JSON_response->status        = "error";
      $JSON_response->error_message = "you do not have permission to add new bookings with user level ".$user_details["user_level"];
    }
  }
  else
  {
    $JSON_response->status        = "error";
    $JSON_response->error_message = "invalid session_id; either login is invalid or you do not have permission to get bookings";
  }

  db_disconnect($db_connection);

  echo json_encode($JSON_response);
?>
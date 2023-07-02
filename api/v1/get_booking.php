<?php
  $booking_ID = $_POST["booking-id"];
  $session_id = $_POST["session-id"];

  $JSON_response = new stdClass();

  include($_SERVER['DOCUMENT_ROOT']."/includes/db_connect.php");
	$db_connection = db_connect();

  $session_query = $db_connection->query("SELECT `logins_sessions`.`login_ID`, `logins`.`user_level` FROM `logins_sessions` INNER JOIN `logins` ON `logins_sessions`.`login_ID`=`logins`.`ID` WHERE `logins_sessions`.`ID`='".$session_id."'");

  if ($session_query && $session_query->num_rows > 0)
  {
    $user_details = $session_query->fetch_assoc();

    if ($user_details["user_level"] >= 1)
    {
      $JSON_response->status = "success";

      $bookings_query = $db_connection->query("SELECT * FROM `bookings` WHERE `booking_ID`='".$booking_ID."' AND `deleted`='0' ORDER BY `status` DESC LIMIT 1");

      if ($bookings_query)
      {
        $bookings = $bookings_query->fetch_assoc();

        $booking_datetime_utc = new DateTime("now", new DateTimeZone("Europe/London"));
        $booking_datetime_utc->setTimestamp($bookings["datetime"]);
        // $booking_datetime_utc->setTimezone(new DateTimeZone("UTC"));

        $JSON_response->booking_name            = $bookings["name"];
        $JSON_response->booking_status          = $bookings["status"];
        $JSON_response->booking_ensemble_id     = $bookings["booking_ensemble"];
        $JSON_response->booking_date            = $booking_datetime_utc->format("Y-m-d");
        $JSON_response->booking_time            = $booking_datetime_utc->format("H:i");
        $JSON_response->booking_location        = $bookings["location"];
        $JSON_response->booking_update_datetime = $bookings["update_datetime"];
        $JSON_response->booking_updated_by      = $bookings["updated_by"];
        $JSON_response->booking_deleted         = $bookings["deleted"];
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
      $JSON_response->error_message = "you do not have permission to add new bookings with user level ".$user_details["user_level"]." and session_id ".$session_id;
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
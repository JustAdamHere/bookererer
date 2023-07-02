<?php
  $booking_name       = htmlspecialchars($_POST["booking-name"]);
  $ensemble_id        = $_POST["ensemble-id"];
  $booking_date       = $_POST["booking-date"];
  $booking_time       = $_POST["booking-time"];
  $booking_date_end   = $_POST["booking-date-end"];
  $booking_time_end   = $_POST["booking-time-end"];
  $booking_location   = htmlspecialchars($_POST["booking-location"]);
  $session_id         = $_POST["session-id"];
  $booking_id         = $_POST["booking-id"];
  $booking_status     = $_POST["booking-status"];
  $booking_status_new = $_POST["booking-status-new"];
  $clash_agreed       = $_POST["clash-agreed"];

  $JSON_response = new stdClass();

  include($_SERVER['DOCUMENT_ROOT']."/includes/db_connect.php");
  include($_SERVER['DOCUMENT_ROOT']."/includes/functions.php");
	$db_connection = db_connect();

  $session_query = $db_connection->query("SELECT `logins_sessions`.`login_ID`, `logins`.`user_level` FROM `logins_sessions` INNER JOIN `logins` ON `logins_sessions`.`login_ID`=`logins`.`ID` WHERE `logins_sessions`.`ID`='".$session_id."'");

  if ($session_query)
  {      
    $user_details = $session_query->fetch_assoc();

    if ($user_details["user_level"] >= 1)
    {
      // Hack to automatically approve Keiron's own bookings.
      if ($user_details["user_level"] == 2 && $ensemble_id == 2)
      {
        $booking_status_new = 4;
      }

      $booking_datetime = new DateTime();
      $booking_datetime->setDate(substr($booking_date, 0, 4), substr($booking_date, 5, 2), substr($booking_date, 8, 2));
      $booking_datetime->setTime(substr($booking_time, 0, 2), substr($booking_time, 3, 2));
      $booking_datetime->setTimezone(new DateTimeZone("Europe/London"));

      $booking_datetime_end = new DateTime();
      $booking_datetime_end->setDate(substr($booking_date_end, 0, 4), substr($booking_date_end, 5, 2), substr($booking_date_end, 8, 2));
      $booking_datetime_end->setTime(substr($booking_time_end, 0, 2), substr($booking_time_end, 3, 2));
      $booking_datetime_end->setTimezone(new DateTimeZone("Europe/London"));

      // Test for clashes.
      $clash = false;
      if ($booking_status == -1 || $booking_status == 0)
      {
        $clash = test_clash($booking_id, $booking_datetime->getTimestamp(), $booking_datetime_end->getTimestamp());

      }

      if ($clash && $clash_agreed == 0)
      {
        $JSON_response->status             = "warning";
        $JSON_response->warning_message    = "clash detected";
        $JSON_response->clash_booking_id   = $clash["booking_ID"];
        $JSON_response->clash_name         = $clash["name"];
        $JSON_response->clash_datetime     = $clash["datetime"];
        $JSON_response->clash_datetime_end = $clash["datetime_end"];
        $JSON_response->clash_location     = $clash["location"];

        $JSON_response->clash_ensemble_name = $db_connection->query("SELECT `name` FROM `logins` WHERE `ID`='".$clash["booking_ensemble"]."' LIMIT 1")->fetch_assoc()["name"];

        $JSON_response->clash_datetime_range = get_human_date_range($clash["datetime"], $clash["datetime_end"]);
      }
      else
      {
        if (0 <= $booking_status_new && $booking_status_new <= 5 || $booking_id == "not yet created")
        {
          $JSON_response->status = "success";

          if ($booking_id == "not yet created")
          {
            $booking_id = $db_connection->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'bookererer' AND TABLE_NAME = 'bookings'")->fetch_assoc()["AUTO_INCREMENT"];
          }

          $term_dates_query = $db_connection->query("INSERT INTO `bookings` (`booking_ID`, `name`, `status`, `booking_ensemble`, `datetime`, `datetime_end`, `location`, `updated_datetime`, `updated_by`, `deleted`) VALUES ('".$booking_id."', '".$booking_name."', '".($booking_status_new)."', '".$ensemble_id."', '".$booking_datetime->getTimestamp()."', '".$booking_datetime_end->getTimestamp()."', '".$booking_location."', '".time()."', '".$user_details["login_ID"]."', '0')");

          if (!$term_dates_query)
          {
            $JSON_response->status        = "error";
            $JSON_response->error_message = "failed to insert into database with booking_name=".$booking_name.", ensemble_id=".$ensemble_id.", booking_datetime=".$booking_datetime.", booking_datetime_end=".$booking_datetime_end.", booking_location=".$booking_location."; ".$db_connection->error;
          }
        }
        else
        {
          $JSON_response->status        = "error";
          $JSON_response->error_message = "invalid booking_status_new=".$booking_status_new;
        }
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
    $JSON_response->error_message = "invalid session_id; either login is invalid or you do not have permission to add new bookings";
  }

  db_disconnect($db_connection);

  echo json_encode($JSON_response);
?>
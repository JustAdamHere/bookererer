<?php
  $id     = strip_tags($_GET["id"]);
  $status = strip_tags($_GET["status"]);

  if ($id < 0 || $status < 0) 
  {
    echo "Incorrect URL.";
  }
  else if(login_valid())
  {
    $db_connection = db_connect();

    $booking = $db_connection ->query("SELECT * FROM `bookings` WHERE `booking_ID` = '".$id."' AND `status` = '".$status."' LIMIT 1")->fetch_assoc();

    $ensemble_query = $db_connection->prepare("SELECT `name`, `logo`, `email` FROM logins WHERE id = ?;");
    $ensemble_query->bind_param("i", $booking["booking_ensemble"]);
    $ensemble_query->execute();
    $ensemble = $ensemble_query->get_result()->fetch_assoc();
    $ensemble_name = $ensemble["name"];

    echo create_email($booking, $ensemble_name);

    db_disconnect($db_connection);
  }
  else
  {
    output_restricted_page();
  }
?>
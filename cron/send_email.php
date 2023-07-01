<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require_once($_SERVER['DOCUMENT_ROOT']."/dist/libs/phpmailer/src/Exception.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/dist/libs/phpmailer/src/PHPMailer.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/dist/libs/phpmailer/src/SMTP.php");

  require_once($_SERVER['DOCUMENT_ROOT']."/includes/kernel.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/includes/functions.php");

  $time = new DateTime();
  $time ->setTimestamp(time());
  $time ->setTimezone(new DateTimeZone('Europe/London'));

  $db_connection = db_connect();

  $booking_emails_to_be_sent = $db_connection ->query("SELECT * FROM `bookings` WHERE `emails_sent` = '0'");

  while($booking = $booking_emails_to_be_sent->fetch_assoc())
  {
    $keiron_email = $db_connection->query("SELECT `email` FROM `logins` WHERE `user_level` = '2'")->fetch_assoc()["email"];

    $ensemble_query = $db_connection->prepare("SELECT `name`, `logo`, `email` FROM logins WHERE id = ?;");
    $ensemble_query->bind_param("i", $booking["booking_ensemble"]);
    $ensemble_query->execute();
    $ensemble = $ensemble_query->get_result()->fetch_assoc();
    $ensemble_name = $ensemble["name"];
    $ensemble_logo = $ensemble["logo"];
    $ensemble_email = $ensemble["email"];

    $subject = "Update to booking: ".$booking["name"];
    $message = create_email($booking, $ensemble_name);
    
    $mail = new PHPMailer(true);

    try
    {
      $mail->isSMTP();
      $mail->Host       = $config["smtp_host"];
      $mail->SMTPAuth   = true;
      $mail->Username   = $config["smtp_username"];
      $mail->Password   = $config["smtp_password"];
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port       = $config["smtp_port"];

      $mail->setFrom($config["email_from"], $config["software_name"]);

      switch ($booking["status"]) {
        case 0:
          $mail->addAddress($ensemble_email);
          $mail->addCC     ($keiron_email);
          break;
  
        case 1:
          $mail->addAddress($keiron_email);
          $mail->addCC     ($ensemble_email);
          break;
  
        case 2:
          $mail->addAddress($keiron_email);
          $mail->addCC     ($ensemble_email);
          break;
  
        case 3:
          $mail->addAddress($ensemble_email);
          $mail->addCC     ($keiron_email);
          break;
      
        case 4:
          $mail->addAddress($keiron_email);
          $mail->addCC     ($ensemble_email);
          break;
  
        case 5:
          $mail->addAddress($keiron_email);
          $mail->addCC     ($ensemble_email);
          break;
      }

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $message;

      $mail->send();

      echo "Email succeeded.";

      $update_query = $db_connection ->query("UPDATE `bookings` SET `emails_sent` = '1' WHERE `booking_ID` = '".$booking["booking_ID"]."' AND `status` = '".$booking["status"]."'");
    }
    catch (Exception $e)
    {
      echo "Email failed; ".$mail->ErrorInfo;
    }
  }

?>
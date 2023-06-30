<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require_once($_SERVER['DOCUMENT_ROOT']."/dist/libs/phpmailer/src/Exception.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/dist/libs/phpmailer/src/PHPMailer.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/dist/libs/phpmailer/src/SMTP.php");

  require_once($_SERVER['DOCUMENT_ROOT']."/includes/kernel.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/cron/generate-seating-plan-pdf.php");

  $time = new DateTime();
  $time ->setTimestamp(time());
  $time ->setTimezone(new DateTimeZone('Europe/London'));
?>
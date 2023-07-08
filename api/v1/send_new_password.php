<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	function send_token_email($token, $email)
	{
		require($_SERVER['DOCUMENT_ROOT']."/config.php");

		$to       = $email;
		$subject  = "Password Reset for ".$config["software_name"];

		$message  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		$message .= '<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">';
		    $message .= '<head>';
		        $message .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		        $message .= '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
		        $message .= '<meta content="telephone=no" name="format-detection" />';
		        $message .= '<title>'.$subject.'</title>';
		        $message .= '<style type="text/css" data-premailer="ignore">';
		            $message .= '@import url(https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700);';
		        $message .= '</style>';
		        $message .= '<style data-premailer="ignore">';
		            $message .= '@media screen and (max-width: 600px) {';
		                $message .= 'u+.body {';
		                    $message .= 'width: 100vw !important;';
		                $message .= '}';
		            $message .= '}';
		            $message .= 'a[x-apple-data-detectors] {';
		                $message .= 'color: inherit !important;';
		                $message .= 'text-decoration: none !important;';
		                $message .= 'font-size: inherit !important;';
		                $message .= 'font-family: inherit !important;';
		                $message .= 'font-weight: inherit !important;';
		                $message .= 'line-height: inherit !important;';
		            $message .= '}';
		        $message .= '</style>';
		        $message .= '<!--[if mso]>';
		          $message .= '<style type="text/css">';
		            $message .= 'body, table, td {';
		            	$message .= 'font-family: Arial, Helvetica, sans-serif !important;';
		            $message .= '}';
		            $message .= 'img {';
		            	$message .= '-ms-interpolation-mode: bicubic;';
		            $message .= '}';
		            $message .= '.box {';
		            	$message .= 'border-color: #eee !important;';
		            $message .= '}';
		          $message .= '</style>';
		        $message .= '<![endif]-->';
		        $message .= '<style>';
		            $message .= 'body {';
		                $message .= 'margin: 0;';
		                $message .= 'padding: 0;';
		                $message .= 'background-color: #f5f7fb;';
		                $message .= 'font-size: 15px;';
		                $message .= 'line-height: 160%;';
		                $message .= 'mso-line-height-rule: exactly;';
		                $message .= 'color: #444444;';
		                $message .= 'width: 100%;';
		            $message .= '}';
		            $message .= 'body {';
		                $message .= 'font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif;';
		            $message .= '}';
		            $message .= 'img {';
		                $message .= 'border: 0 none;';
		                $message .= 'line-height: 100%;';
		                $message .= 'outline: none;';
		                $message .= 'text-decoration: none;';
		                $message .= 'vertical-align: baseline;';
		                $message .= 'font-size: 0;';
		            $message .= '}';
		            $message .= 'a:hover {';
		                $message .= 'text-decoration: underline;';
		            $message .= '}';
		            $message .= '.btn:hover {';
		                $message .= 'text-decoration: none;';
		            $message .= '}';
		            $message .= '.btn.bg-bordered:hover {';
		                $message .= 'background-color: #f9fbfe !important;';
		            $message .= '}';
		            $message .= 'a.bg-blue:hover {';
		                $message .= 'background-color: #3a77cc !important;';
		            $message .= '}';
		            $message .= 'a.bg-azure:hover {';
		                $message .= 'background-color: #37a3f1 !important;';
		            $message .= '}';
		            $message .= 'a.bg-indigo:hover {';
		                $message .= 'background-color: #596ac9 !important;';
		            $message .= '}';
		            $message .= 'a.bg-purple:hover {';
		                $message .= 'background-color: #9d50e8 !important;';
		            $message .= '}';
		            $message .= 'a.bg-pink:hover {';
		                $message .= 'background-color: #f55f91 !important;';
		            $message .= '}';
		            $message .= 'a.bg-red:hover {';
		                $message .= 'background-color: #c01e1d !important;';
		            $message .= '}';
		            $message .= 'a.bg-orange:hover {';
		                $message .= 'background-color: #fd8e35 !important;';
		            $message .= '}';
		            $message .= 'a.bg-yellow:hover {';
		                $message .= 'background-color: #e3b90d !important;';
		            $message .= '}';
		            $message .= 'a.bg-lime:hover {';
		                $message .= 'background-color: #73cb2d !important;';
		            $message .= '}';
		            $message .= 'a.bg-green:hover {';
		                $message .= 'background-color: #56ab00 !important;';
		            $message .= '}';
		            $message .= 'a.bg-teal:hover {';
		                $message .= 'background-color: #28beae !important;';
		            $message .= '}';
		            $message .= 'a.bg-cyan:hover {';
		                $message .= 'background-color: #1596aa !important;';
		            $message .= '}';
		            $message .= 'a.bg-gray:hover {';
		                $message .= 'background-color: #95a9b0 !important;';
		            $message .= '}';
		            $message .= 'a.bg-secondary:hover {';
		                $message .= 'background-color: #ecf0f2 !important;';
		            $message .= '}';
		            $message .= '.img-hover:hover img {';
		                $message .= 'opacity: .64;';
		            $message .= '}';
		            $message .= '.theme-dark a.bg-secondary:hover {';
		                $message .= 'background-color: #354258 !important;';
		            $message .= '}';
		            $message .= '.theme-dark .btn.bg-bordered:hover {';
		                $message .= 'background-color: #467fcf !important;';
		                $message .= 'color: #fff !important;';
		            $message .= '}';
		            $message .= '.theme-dark .btn.bg-bordered:hover .btn-span {';
		                $message .= 'color: #fff !important;';
		            $message .= '}';
		            $message .= '@media only screen and (max-width: 560px) {';
		                $message .= 'body {';
		                    $message .= 'font-size: 14px !important;';
		                $message .= '}';
		                $message .= '.content {';
		                    $message .= 'padding: 24px !important;';
		                $message .= '}';
		                $message .= '.content-image-text {';
		                    $message .= 'padding: 24px !important;';
		                $message .= '}';
		                $message .= '.content-image {';
		                    $message .= 'height: 100px !important;';
		                $message .= '}';
		                $message .= '.content-image-text {';
		                    $message .= 'padding-top: 96px !important;';
		                $message .= '}';
		                $message .= 'h1 {';
		                    $message .= 'font-size: 24px !important;';
		                $message .= '}';
		                $message .= '.h1 {';
		                    $message .= 'font-size: 24px !important;';
		                $message .= '}';
		                $message .= 'h2 {';
		                    $message .= 'font-size: 20px !important;';
		                $message .= '}';
		                $message .= '.h2 {';
		                    $message .= 'font-size: 20px !important;';
		                $message .= '}';
		                $message .= 'h3 {';
		                    $message .= 'font-size: 18px !important;';
		                $message .= '}';
		                $message .= '.h3 {';
		                    $message .= 'font-size: 18px !important;';
		                $message .= '}';
		                $message .= '.col {';
		                    $message .= 'display: table !important;';
		                    $message .= 'width: 100% !important;';
		                $message .= '}';
		                $message .= '.col-spacer {';
		                    $message .= 'display: table !important;';
		                    $message .= 'width: 100% !important;';
		                $message .= '}';
		                $message .= '.col-spacer-xs {';
		                    $message .= 'display: table !important;';
		                    $message .= 'width: 100% !important;';
		                $message .= '}';
		                $message .= '.col-spacer-sm {';
		                    $message .= 'display: table !important;';
		                    $message .= 'width: 100% !important;';
		                $message .= '}';
		                $message .= '.col-hr {';
		                    $message .= 'display: table !important;';
		                    $message .= 'width: 100% !important;';
		                $message .= '}';
		                $message .= '.row {';
		                    $message .= 'display: table !important;';
		                    $message .= 'width: 100% !important;';
		                $message .= '}';
		                $message .= '.col-hr {';
		                    $message .= 'border: 0 !important;';
		                    $message .= 'height: 24px !important;';
		                    $message .= 'width: auto !important;';
		                    $message .= 'background: transparent !important;';
		                $message .= '}';
		                $message .= '.col-spacer {';
		                    $message .= 'width: 100% !important;';
		                    $message .= 'height: 24px !important;';
		                $message .= '}';
		                $message .= '.col-spacer-sm {';
		                    $message .= 'height: 16px !important;';
		                $message .= '}';
		                $message .= '.col-spacer-xs {';
		                    $message .= 'height: 8px !important;';
		                $message .= '}';
		                $message .= '.chart-cell-spacer {';
		                    $message .= 'width: 4px !important;';
		                $message .= '}';
		                $message .= '.text-mobile-center {';
		                    $message .= 'text-align: center !important;';
		                $message .= '}';
		                $message .= '.d-mobile-none {';
		                    $message .= 'display: none !important;';
		                $message .= '}';
		            $message .= '}';
		        $message .= '</style>';
		    $message .= '</head>';
		    $message .= '<body class="bg-body" style="font-size: 15px; margin: 0; padding: 0; line-height: 160%; mso-line-height-rule: exactly; color: #444444; width: 100%; font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif;" bgcolor="#f5f7fb">';
		        $message .= '<center>';
		            $message .= '<table class="main bg-body" width="100%" cellspacing="0" cellpadding="0" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; border-collapse: collapse; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" bgcolor="#f5f7fb">';
		                $message .= '<tr>';
		                    $message .= '<td align="center" valign="top" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif;">';
		                        $message .= '<!--[if (gte mso 9)|(IE)]>';
		                  $message .= '<table border="0" cellspacing="0" cellpadding="0">';
		                    $message .= '<tr>';
		                      $message .= '<td align="center" valign="top" width="640">';
		                $message .= '<![endif]-->';
		                        $message .= '<span class="preheader" style="font-size: 0; padding: 0; display: none; max-height: 0; mso-hide: all; line-height: 0; color: transparent; height: 0; max-width: 0; opacity: 0; overflow: hidden; visibility: hidden; width: 0;">Here is your password reset for '.$config["software_name"].'.</span>';
		                        $message .= '<table class="wrap" cellspacing="0" cellpadding="0" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; border-collapse: collapse; width: 100%; max-width: 640px; text-align: left;">';
		                            $message .= '<tr>';
		                                $message .= '<td class="p-sm" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; padding: 8px;">';
		                                    $message .= '<table cellpadding="0" cellspacing="0" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; border-collapse: collapse; width: 100%;">';
		                                        $message .= '<tr>';
		                                            $message .= '<td class="py-lg" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; padding-top: 24px; padding-bottom: 24px;">';
		                                                $message .= '<table cellspacing="0" cellpadding="0" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; border-collapse: collapse; width: 100%;">';
		                                                    $message .= '<tr>';
		                                                        $message .= '<td style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif;">';
		                                                            $message .= '<a href="'.$config["home_url"].'" style="color: #467fcf; text-decoration: none;"><img src="'.$config["logo_url"].'" width="116" alt="" style="line-height: 100%; outline: none; text-decoration: none; vertical-align: baseline; font-size: 0; border: 0 none;" /></a>';
		                                                        $message .= '</td>';
		                                                    $message .= '</tr>';
		                                                $message .= '</table>';
		                                            $message .= '</td>';
		                                        $message .= '</tr>';
		                                    $message .= '</table>';
		                                    $message .= '<div class="main-content">';
		                                        $message .= '<table class="box" cellpadding="0" cellspacing="0" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; border-collapse: collapse; width: 100%; border-radius: 3px; -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); border: 1px solid #f0f0f0;" bgcolor="#ffffff">';
		                                            $message .= '<tr>';
		                                                $message .= '<td style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif;">';
		                                                    $message .= '<table cellpadding="0" cellspacing="0" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; border-collapse: collapse; width: 100%;">';
		                                                        $message .= '<tr>';
		                                                            $message .= '<td class="content pb-0" align="center" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; padding: 40px 48px 0;">';
		                                                                $message .= '<table class="icon icon-lg bg-blue" cellspacing="0" cellpadding="0" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; padding: 0; border-collapse: separate; width: 72px; border-radius: 50%; line-height: 100%; font-weight: 300; height: 72px; font-size: 48px; text-align: center; color: #ffffff;" bgcolor="#467fcf">';
		                                                                    $message .= '<tr>';
		                                                                        $message .= '<td valign="middle" align="center" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif;">';
		                                                                            $message .= '<img src="'.$config["base_url"].'/email/assets/icons-white-unlock.png" class=" va-middle" width="40" height="40" alt="unlock" style="line-height: 100%; border: 0 none; outline: none; text-decoration: none; vertical-align: middle; font-size: 0; display: block; width: 40px; height: 40px;" />';
		                                                                        $message .= '</td>';
		                                                                    $message .= '</tr>';
		                                                                $message .= '</table>';
		                                                                $message .= '<h1 class="text-center m-0 mt-md" style="font-weight: 300; font-size: 28px; line-height: 130%; margin: 16px 0 0;" align="center">Reset password request</h1>';
		                                                            $message .= '</td>';
		                                                        $message .= '</tr>';
		                                                        $message .= '<tr>';
		                                                            $message .= '<td class="content text-center" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; padding: 40px 48px;" align="center">';
		                                                                $message .= '<p style="margin: 0 0 1em;">You recently requested to reset a password for your '.$config["software_name"].' account. Use the button below to reset it. This message will expire in 24 hours.</p>';
		                                                            $message .= '</td>';
		                                                        $message .= '</tr>';
		                                                        $message .= '<tr>';
		                                                            $message .= '<td class="content text-center pt-0 pb-xl" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; padding: 0 48px 48px;" align="center">';
		                                                                $message .= '<table cellspacing="0" cellpadding="0" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; border-collapse: collapse; width: 100%;">';
		                                                                    $message .= '<tr>';
		                                                                        $message .= '<td align="center" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif;">';
		                                                                            $message .= '<table cellpadding="0" cellspacing="0" border="0" class="bg-blue rounded w-auto" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; border-collapse: separate; width: auto; color: #ffffff; border-radius: 3px;" bgcolor="#467fcf">';
		                                                                                $message .= '<tr>';
		                                                                                    $message .= '<td align="center" valign="top" class="lh-1" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; line-height: 100%;">';
		                                                                                        $message .= '<a href="'.$config["base_url"].'/reset-password.php?token='.$token.'" class="btn bg-blue border-blue" style="color: #ffffff; padding: 12px 32px; border: 1px solid #467fcf; text-decoration: none; white-space: nowrap; font-weight: 600; font-size: 16px; border-radius: 3px; line-height: 100%; display: block; -webkit-transition: .3s background-color; transition: .3s background-color; background-color: #467fcf;">';
		                                                                                            $message .= '<span class="btn-span" style="color: #ffffff; font-size: 16px; text-decoration: none; white-space: nowrap; font-weight: 600; line-height: 100%;">Reset password</span>';
		                                                                                        $message .= '</a>';
		                                                                                    $message .= '</td>';
		                                                                                $message .= '</tr>';
		                                                                            $message .= '</table>';
		                                                                        $message .= '</td>';
		                                                                    $message .= '</tr>';
		                                                                $message .= '</table>';
		                                                            $message .= '</td>';
		                                                        $message .= '</tr>';
		                                                        $message .= '<tr>';
		                                                            $message .= '<td class="content text-muted pt-0 text-center font-sm" style="font-family: Open Sans, -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, Helvetica, Arial, sans-serif; color: #9eb0b7; font-size: 13px; padding: 0 48px 40px;" align="center">';
		                                                                $message .= 'Having trouble with the button above? Please copy this URL: <a href="'.$config["base_url"].'/reset-password.php?token='.$token.'" style="color: #467fcf; text-decoration: none;">'.$config["base_url"].'/reset-password.php?token='.$token.'</a> and paste it into your browser. If you did not request a password reset, please ignore this message or contact us if you have any questions.';
		                                                            $message .= '</td>';
		                                                        $message .= '</tr>';
		                                                    $message .= '</table>';
		                                                $message .= '</td>';
		                                            $message .= '</tr>';
		                                        $message .= '</table>';
		                                    $message .= '</div>';
		                                $message .= '</td>';
		                            $message .= '</tr>';
		                        $message .= '</table>';
		                        $message .= '<!--[if (gte mso 9)|(IE)]>';
		                $message .= '</td>';
		              $message .= '</tr>';
		            $message .= '</table>';
		                $message .= '<![endif]-->';
		                    $message .= '</td>';
		                $message .= '</tr>';
		            $message .= '</table>';
		        $message .= '</center>';
		    $message .= '</body>';
		$message .= '</html>';

		// $mail = mail($to, $subject, $message, $headers);

		// if ($mail)
		// {
		// 	return true;
		// }
		// else
		// {
		// 	return false;
		// }

		require_once($_SERVER['DOCUMENT_ROOT']."/dist/libs/phpmailer/src/Exception.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/dist/libs/phpmailer/src/PHPMailer.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/dist/libs/phpmailer/src/SMTP.php");

		$mail = new PHPMailer(true);

		try
		{
			$mail->isSMTP();
			$mail->Host       = $config["smtp_host"];
			$mail->SMTPAuth   = true;
			$mail->Username   = $config["smtp_username"];
			$mail->Password   = $config["smtp_password"];
			$mail->SMTPSecure = $config["smtp_encryption"];
			$mail->Port       = $config["smtp_port"];

			$mail->SMTPDebug = 0;

			$mail->setFrom($config["email_from"], $config["software_name"]);
			foreach (explode(",", $to) as $address)
			{
				$mail->addAddress($address);
			}

			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body    = $message;

			$send_email_result = $mail->send();

			$mail->smtpClose();

			if (!$send_email_result)
			{
				return "An error occurred while sending the email: ".$mail->ErrorInfo;
			}
			else
			{
				return true;
			}
		}
		catch (Exception $e)
		{
			return "An error occurred while sending the email: ".$mail->ErrorInfo;
		}
	}

	$username = strip_tags($_POST["username"]);

	$JSON_response = new stdClass();

	if ($username != "")
	{
		include($_SERVER['DOCUMENT_ROOT']."/includes/db_connect.php");
		$db_connection = db_connect();

		$username_query = $db_connection->query("SELECT `ID`, `email` FROM `logins` WHERE `username`='".$username."' ORDER BY ID ASC LIMIT 1");

		if ($username_query->num_rows == 0)
		{
			$JSON_response->status        = "error";
			$JSON_response->error_message = "no such username: ".$username;
		}
		else
		{
			$user_details = $username_query->fetch_assoc();
			$login_ID = $user_details["ID"];
			$token = $db_connection->query("SELECT UUID()")->fetch_array()[0];

			$current_timestamp = time();
			$expiry_timestamp  = $current_timestamp + 60*60*24;

			//$token_query = $db_connection->query("INSERT INTO `password_reset_tokens` (`login_ID`, `token`, `expiry`) VALUES ('".$login_ID."', '".$token."', DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY))");
			$token_query = $db_connection->query("INSERT INTO `password_reset_tokens` (`login_ID`, `token`, `expiry`) VALUES ('".$login_ID."', '".$token."', '".$expiry_timestamp."')");

			if ($token_query)
			{
				$send_email_result = send_token_email($token, $user_details['email']);

				if($send_email_result === true)
				{
					$JSON_response->status = "success";
				}
				else
				{
					$JSON_response->status        = "error";
					$JSON_response->error_message = "failed to send token email: ".$send_email_result;
				}	
			}
			else
			{
				$JSON_response->status        = "error";
				$JSON_response->error_message = "failed to insert token into database: ".$db_connection->error;
			}
		}

		db_disconnect($db_connection);
	}
	else
	{
		$JSON_response->status        = "error";
		$JSON_response->error_message = "no/too much data provided";
	}

	echo json_encode($JSON_response);
?>
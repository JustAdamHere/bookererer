<?php require_once($_SERVER['DOCUMENT_ROOT']."/includes/kernel.php"); ?>

<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta5
* @link https://tabler.io
* Copyright 2018-2022 The Tabler Authors
* Copyright 2018-2022 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
  <head>
    <?php include($_SERVER['DOCUMENT_ROOT']."/includes/head.php"); ?>
    <title>Forgot password | <?=$config["software_name"];?></title>
    <script type="text/javascript">
      window.onkeypress = function(e) {
        if(e.keyCode == 13) {
          sendNewPassword();
        }
      }

      async function sendNewPassword()
      {
        document.getElementById("send-new-password-button").innerHTML = "Checking details...";

        var xhttp = new XMLHttpRequest();

        xhttp.open("POST", "./api/v1/send_new_password.php", true);
        xhttp.timeout = 10000;
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        var emailField = encodeURIComponent(document.getElementById("emailField").value);

        xhttp.send("email=" + emailField);

        xhttp.onload = function() {
          if (xhttp.status == 200) {
            console.log(this.responseText);

            const JSON_response = JSON.parse(this.responseText);

            if (JSON_response.status == "success") {
              document.getElementById("send-new-password-button").innerHTML = "Success! Check your email.";
              document.getElementById("send-new-password-button").classList.add("disabled");
              document.getElementById("send-new-password-error").innerHTML = "";
            } 
            else {
              document.getElementById("send-new-password-button").innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="2" /><polyline points="3 7 12 13 21 7" /></svg>Send me new password';
              document.getElementById("send-new-password-error").innerHTML = "Error message: " + JSON_response.error_message;
            }
          }
        }

        xhttp.ontimeout = function() {
          document.getElementById("send-new-password-button").innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="2" /><polyline points="3 7 12 13 21 7" /></svg>Send me new password';
          document.getElementById("send-new-password-error").innerHTML = "Error message: " + "Request to API timed out.";
        }
      }
    </script>
  </head>
  <body  class=" border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
      <div class="container-tight py-4">
        <div class="text-center mb-4">
          <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?=$config["logo_url"];?>" height="36" alt=""></a>
        </div>
        <div class="card card-md" autocomplete="off">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Forgot password</h2>
            <p class="text-muted mb-4">Enter your email address and your password will be reset and emailed to you.</p>
            <div class="mb-3">
              <label class="form-label">Email address</label>
              <input type="email" id="emailField" name="emailField" class="form-control" placeholder="Enter email">
            </div>
            <div class="mb-2" id="send-new-password-error">
                  
            </div>
            <div class="form-footer">
              <button type="button" onclick="sendNewPassword()" id="send-new-password-button" class="btn btn-primary w-100">
                <!-- Download SVG icon from http://tabler-icons.io/i/mail -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="2" /><polyline points="3 7 12 13 21 7" /></svg>
                Send me new password
              </button>
            </div>
          </div>
        </div>
        <div class="text-center text-muted mt-3">
          Forget it, <a class="btn btn-link" onclick="history.back()">send me back</a> to the sign in screen.
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js"></script>
    <script src="./dist/js/demo.min.js"></script>
  </body>
</html>
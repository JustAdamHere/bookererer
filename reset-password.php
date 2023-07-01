<?php require_once($_SERVER['DOCUMENT_ROOT']."/includes/kernel.php"); ?>

<?php
  $token = isset($_GET["token"])?htmlspecialchars($_GET["token"]):NULL;

  $db_connection = db_connect();
?>

<?php
  if ($token == NULL)
  {
    ?>
    <!doctype html>
    <html lang="en">
      <head>
        <?php include($_SERVER['DOCUMENT_ROOT']."/includes/head.php"); ?>
        <title>Reset password | <?=$config["software_name"];?></title>
      </head>
      <body  class=" border-top-wide border-primary d-flex flex-column">
        <div class="page page-center">
          <div class="container-tight py-4">
            <div class="text-center mb-4">
              <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?=$config["logo_url"];?>" height="36" alt=""></a>
            </div>
            <div class="card card-md">
              <div class="card-body">
                <h2 class="card-title text-center mb-4">Reset password</h2>
                No token provided.
            </div>
          </div>
        </div>
        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="./dist/js/tabler.min.js"></script>
        <script src="./dist/js/demo.min.js"></script>
      </body>
    </html>
    <?php
  }
  else
  {
    $reset_token_query = $db_connection->prepare("SELECT `password_reset_tokens`.`login_ID`, `logins`.`email` FROM `password_reset_tokens` INNER JOIN `logins` ON `password_reset_tokens`.`login_ID`=`logins`.`ID` WHERE `password_reset_tokens`.`token` = ? AND `password_reset_tokens`.`expiry` > UNIX_TIMESTAMP()");
    $reset_token_query->bind_param("s", $token);
    $reset_token_query->execute();
    $reset_token_query->store_result();
    $reset_token_query->bind_result($login_ID, $email);
    $reset_token_query->fetch();

    if ($reset_token_query->num_rows == 0)
    {
      ?>
      <!doctype html>
      <html lang="en">
        <head>
          <?php include($_SERVER['DOCUMENT_ROOT']."/includes/head.php"); ?>
          <title>Reset password | <?=$config["software_name"];?></title>
        </head>
        <body  class=" border-top-wide border-primary d-flex flex-column">
          <div class="page page-center">
            <div class="container-tight py-4">
              <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?=$config["logo_url"];?>" height="36" alt=""></a>
              </div>
              <div class="card card-md">
                <div class="card-body">
                  <h2 class="card-title text-center mb-4">Reset password</h2>
                  Invalid token provided: <?=$token;?>
              </div>
            </div>
          </div>
          <!-- Libs JS -->
          <!-- Tabler Core -->
          <script src="./dist/js/tabler.min.js"></script>
          <script src="./dist/js/demo.min.js"></script>
        </body>
      </html>
      <?php
    }
    else
    {
      ?>
      <!doctype html>
      <html lang="en">
        <head>
          <?php include($_SERVER['DOCUMENT_ROOT']."/includes/head.php"); ?>
          <title>Reset password | <?=$config["software_name"];?></title>
          <script type="text/javascript">
            function togglePassword()
            {
              var passwordField = document.getElementById("passwordField");

              if (passwordField.type === "password")
              {
                passwordField.type = "text";
              }
              else
              {
                passwordField.type = "password";
              }
            }

            function togglePasswordConfirm()
            {
              var passwordField = document.getElementById("passwordConfirmField");

              if (passwordField.type === "password")
              {
                passwordField.type = "text";
              }
              else
              {
                passwordField.type = "password";
              }
            }

            function doReset()
            {
              document.getElementById("do-reset-button").innerHTML = "Checking details...";

              var xhttp = new XMLHttpRequest();

              xhttp.open("POST", "./api/v1/do_password-reset.php", true);
              xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

              var tokenField           = document.getElementById("token").value;
              var passwordField        = document.getElementById("passwordField").value;
              var passwordConfirmField = document.getElementById("passwordConfirmField").value;

              if (passwordField != passwordConfirmField)
              {
                document.getElementById("do-reset-button").innerHTML = "Reset password";
                document.getElementById("do-reset-error").innerHTML = "Error message: Passwords do not match.";
              }
              else
              {
                xhttp.send("token=" + tokenField + "&password=" + passwordField);

                xhttp.onload = function() {
                  if (xhttp.status == 200) {
                    const JSON_response = JSON.parse(this.responseText);

                    if (JSON_response.status == "success") {
                      document.getElementById("do-reset-button").innerHTML = "Password updated! You can close this window.";
                      document.getElementById("do-reset-error").innerHTML = "";
                      document.getElementById("do-reset-button").classList.add("disabled");
                    } 
                    else {
                      document.getElementById("do-reset-button").innerHTML = "Reset password";
                      document.getElementById("do-reset-error").innerHTML = "Error message: " + JSON_response.error_message;
                    }
                  }
                }
              }
            }

            window.onkeypress = function(e) {
            if(e.keyCode == 13) {
              doReset();
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
              <form class="card card-md" action="." method="get" autocomplete="off">
                <div class="card-body">
                  <h2 class="card-title text-center mb-4">Reset password</h2>
                  <input type="hidden" id="token" value="<?=$token;?>">
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" id="emailField" tabindex="1" value="<?=$email;?>" disabled>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">
                      Password
                    </label>
                    <div class="input-group input-group-flat">
                      <input type="password" class="form-control" id="passwordField" placeholder="Password"  autocomplete="off"  tabindex="2">
                      <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" onclick="togglePassword()" tabindex="3"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                        </a>
                      </span>
                    </div>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">
                      Password again
                    </label>
                    <div class="input-group input-group-flat">
                      <input type="password" class="form-control" id="passwordConfirmField" placeholder="Don't get it wrong"  autocomplete="off"  tabindex="4">
                      <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" onclick="togglePasswordConfirm()" tabindex="5"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                        </a>
                      </span>
                    </div>
                  </div>
                  <div class="mb-2" id="do-reset-error">
                    
                  </div>
                  <div class="form-footer">
                    <button type="button" id="do-reset-button" onclick="doReset()" class="btn btn-primary w-100" tabindex="4">Reset password</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- Libs JS -->
          <!-- Tabler Core -->
          <script src="./dist/js/tabler.min.js"></script>
          <script src="./dist/js/demo.min.js"></script>
        </body>
      </html>
      <?php
    }

    $reset_token_query->close();
  }

  db_disconnect($db_connection);
?>
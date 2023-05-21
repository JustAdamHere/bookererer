<?php require_once($_SERVER['DOCUMENT_ROOT']."/config.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/includes/kernel.php"); ?>

<?php
  $redirect_page = isset($_GET["redirect_page"])?urldecode($_GET["redirect_page"]):"/index.php";
?>

<?php
  if(login_valid())
  {
    ?>
    <!doctype html>
    <html lang="en">
      <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title>Page 403</title>
        <!-- CSS files -->
        <link href="<?=$config['base_url'];?>/dist/css/tabler.min.css" rel="stylesheet"/>
        <link href="<?=$config['base_url'];?>/dist/css/tabler-flags.min.css" rel="stylesheet"/>
        <link href="<?=$config['base_url'];?>/dist/css/tabler-payments.min.css" rel="stylesheet"/>
        <link href="<?=$config['base_url'];?>/dist/css/tabler-vendors.min.css" rel="stylesheet"/>
        <link href="<?=$config['base_url'];?>/dist/css/demo.min.css" rel="stylesheet"/>
      </head>
      <body  class=" border-top-wide border-primary d-flex flex-column">
        <div class="page page-center">
          <div class="container-tight py-4">
            <div class="empty">
              <div class="empty-header">Login page</div>
              <p class="empty-title">You're already logged in!</p>
              <p class="empty-subtitle text-muted">
                Please logout if you want to login as a different user.
              </p>
              <div class="empty-action">
                <a href="<?=$config['base_url'];?>/logout.php?redirect_page=<?=urlencode($redirect_page);?>" class="btn btn-primary">
                  <!-- Download SVG icon from http://tabler-icons.io/i/lock-off -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock-off" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="3" x2="21" y2="21" /><path d="M19 19a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2h4m4 0h2a2 2 0 0 1 2 2v2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-3m.712 -3.278a4 4 0 0 1 7.288 2.278v4" /></svg>
                  Logout
                </a>
              </div>
            </div>
          </div>
        </div>
        <script src="<?=$config['base_url'];?>/dist/js/tabler.min.js"></script>
        <script src="<?=$config['base_url'];?>/dist/js/demo.min.js"></script>
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
        <title>Login to <?=$config["software_name"];?></title>
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

          function doLogin()
          {
            document.getElementById("do-login-button").innerHTML = "Checking details...";

            var xhttp = new XMLHttpRequest();

            xhttp.open("POST", "<?=$config['base_url'];?>/api/v1/do_login.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            var emailField    = document.getElementById("emailField").value;
            var passwordField = document.getElementById("passwordField").value;

            xhttp.send("email=" + emailField + "&password=" + passwordField);

            xhttp.onload = function() {
              if (xhttp.status == 200) {
                const JSON_response = JSON.parse(this.responseText);

                if (JSON_response.status == "success") {
                  document.getElementById("do-login-button").innerHTML = "Success!";
                  document.cookie = 'session_ID='+JSON_response.session_ID+'; expires='+JSON_response.expiry+'; path=/';
                  window.location.href = "<?=$config["base_url"];?><?=$redirect_page;?>";
                } 
                else {
                  document.getElementById("do-login-button").innerHTML = "Log in";
                  document.getElementById("do-login-error").innerHTML = "Error message: " + JSON_response.error_message;
                }
              }
            }
          }

          window.onkeypress = function(e) {
            if(e.keyCode == 13) {
              doLogin();
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
            <form id="login-form" class="card card-md" autocomplete="off">
              <div class="card-body">
                <h2 class="card-title text-center mb-4">Login to your account</h2>
                <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input type="text" class="form-control" id="emailField" placeholder="Enter username" tabindex="1">
                </div>
                <div class="mb-2">
                  <label class="form-label">
                    Password
                    <span class="form-label-description">
                      <a href="<?=$config['base_url'];?>/forgot-password.php" tabindex="5">I forgot password</a>
                    </span>
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
                <div class="mb-2" id="do-login-error">
                  
                </div>
                <div class="form-footer">
                  <button type="button" id="do-login-button" onclick="doLogin()" class="btn btn-primary w-100" tabindex="4">Log in</button>
                </div>
                <div class="pb-2 pt-4 text-muted">
                    By logging in, you allow us to store a cookie in your browser with a unique session ID; this session ID is linked to your IP address, and is used only to monitor compromised logins and rate limit repeated requests.
                </div>
              </div>
            </form>
            <div class="d-flex py-1 align-items-center">
              <a class="btn btn-link" onclick="history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <line x1="5" y1="12" x2="11" y2="18" />
                  <line x1="5" y1="12" x2="11" y2="6" />
                </svg>
                Take me back
              </a>
            </div>
          </div>
        </div>
        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?=$config['base_url'];?>/dist/js/tabler.min.js"></script>
        <script src="<?=$config['base_url'];?>/dist/js/demo.min.js"></script>
      </body>
    </html>
    <?php
  }
?>
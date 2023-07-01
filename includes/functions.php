<?php require($_SERVER['DOCUMENT_ROOT']."/config.php"); ?>

<?php
function get_steps($status, $ensemble_logo, $keiron_logo)
{
  $step_1_colour = [
    0 => "bg-blue",
    1 => "bg-green",
    2 => "bg-green",
    3 => "bg-green",
    4 => "bg-green",
    5 => "bg-green"
  ];

  $step_2_colour = [
    0 => "bg-black",
    1 => "bg-blue",
    2 => "bg-red",
    3 => "bg-green",
    4 => "bg-green",
    5 => "bg-green"
  ];

  $step_3_colour = [
    0 => "bg-black",
    1 => "bg-black",
    2 => "bg-black",
    3 => "bg-blue",
    4 => "bg-green",
    5 => "bg-red"
  ];

  $step_1_opacity = [
    0 => "opacity-100",
    1 => "opacity-100",
    2 => "opacity-100",
    3 => "opacity-100",
    4 => "opacity-100",
    5 => "opacity-100"
  ];

  $step_2_opacity = [
    0 => "opacity-20",
    1 => "opacity-100",
    2 => "opacity-100",
    3 => "opacity-100",
    4 => "opacity-100",
    5 => "opacity-100"
  ];
  
  $step_3_opacity = [
    0 => "opacity-20",
    1 => "opacity-20",
    2 => "opacity-20",
    3 => "opacity-100",
    4 => "opacity-100",
    5 => "opacity-100"
  ];

  $output  = '<span class="avatar '.$step_1_opacity[$status].'"';
  $output .=   'style="background-image: url(\''.$ensemble_logo.'\')">';
  $output .=  '<span class="badge '.$step_1_colour[$status].' text-10">1</span>';
  $output .= '</span>';
  $output .= '<span class="avatar '.$step_2_opacity[$status].'"';
  $output .=   'style="background-image: url(\''.$keiron_logo.'\');">';
  $output .=   '<span class="badge '.$step_2_colour[$status].' text-10">2</span>';
  $output .= '</span>';
  $output .= '<span class="avatar '.$step_3_opacity[$status].'"';
  $output .=   'style="background-image: url(\''.$ensemble_logo.'\');">';
  $output .=   '<span class="badge '.$step_3_colour[$status].' text-10">3</span>';
  $output .= '</span>';

  return $output;
}

function output_missing_page()
{
  require($_SERVER['DOCUMENT_ROOT']."/config.php");

  http_response_code(404);

  ?>
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
      <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
      <title>Page 404</title>
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
            <div class="empty-header">404</div>
            <p class="empty-title">Oops… We can't find that page.</p>
            <p class="empty-subtitle text-muted">
              Please double-check the URL.
            </p>
            <div class="empty-action">
              <a href="<?=$config['base_url'];?>/login.php" class="btn btn-primary">
                <!-- Download SVG icon from http://tabler-icons.io/i/login -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="11" width="14" height="10" rx="2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                Login
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

function output_restricted_page()
{
  require($_SERVER['DOCUMENT_ROOT']."/config.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/includes/db_connect.php");
  
  http_response_code(403);

  $db_connection = db_connect();

  ?>
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
      <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
      <title>Page 403</title>
      <?php include($_SERVER['DOCUMENT_ROOT']."/includes/head.php"); ?>
    </head>
    <body class=" border-top-wide border-primary d-flex flex-column">
      <div class="page page-center">
        <div class="container-tight py-4">
          <?php
            if (login_valid())
            {
              $login_query = $db_connection->query("SELECT `members`.`ID` AS `member_ID`, `members`.`first_name`, `members`.`last_name`, `image` FROM `members` LEFT JOIN `logins_sessions` ON `members`.`ID`=`logins_sessions`.`member_ID` WHERE `logins_sessions`.`ID`='".$_COOKIE["session_ID"]."' LIMIT 1");

              if ($login_query->num_rows == 1)
              {
                $login_row = $login_query->fetch_assoc();

                ?>
                  <div class="empty">
                    <div class="empty-header">403</div>
                    <p class="empty-title">You don't have access to that.</p>
                    <p class="empty-subtitle text-muted pb-3">
                      You may need to logout and login with a different account.
                    </p>
                    <a href="<?=$config["base_url"];?>" class="btn btn-outline-primary w-100">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <line x1="5" y1="12" x2="11" y2="18"></line>
                        <line x1="5" y1="12" x2="11" y2="6"></line>
                      </svg>
                      View all polls
                    </a>
                    <hr />
                    <div class="col-md-6 col-lg-12">
                      <div class="card">
                        <div class="card-body p-4 text-center">
                          <?php
                            if ($login_row["image"] == "")
                            {
                              ?>
                              <span class="avatar avatar-xl mb-3 avatar-rounded"><?=substr($login_row["first_name"], 0, 1)." ".substr($login_row["last_name"], 0, 1);?></span>
                              <?php
                            }
                            else
                            {
                              ?>
                              <span class="avatar avatar-xl mb-3 avatar-rounded" style="background-image: url('<?=$login_row["image"];?>')"></span>
                              <?php
                            }
                          ?>
                          <h3 class="m-0 mb-1"><?=$login_row["first_name"]." ".$login_row["last_name"];?></h3>
                          <div class="mt-3">
                            <span class="badge bg-blue-lt">Ensemble</span> <!-- <== Need to change this at some point to something like "administrator", etc. -->
                          </div>
                        </div>
                        <div class="d-flex">
                        <a href="<?=$config['base_url'];?>/logout.php?redirect_page=<?=urlencode($_SERVER["REQUEST_URI"]);?>" class="btn card-btn">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock-open" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="11" width="14" height="10" rx="2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-5a4 4 0 0 1 8 0" /></svg>
                          Logout
                        </a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php
              }
              else
              {
                ?>
                <div class="empty">
                  <div class="empty-header">403</div>
                  <p class="empty-title">You may need to login.</p>
                  <p class="empty-subtitle text-muted">
                    Click the button below to login.
                  </p>
                  <div class="empty-action">
                    <a href="<?=$config['base_url'];?>/login.php?redirect_page=<?=urlencode($_SERVER["REQUEST_URI"]);?>" class="btn btn-primary">
                      <!-- Download SVG icon from http://tabler-icons.io/i/login -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="11" width="14" height="10" rx="2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                      Login
                    </a>
                  </div>
                </div>
                <?php
              }
            }
            else
            {
              ?>
              <div class="empty">
                <div class="empty-header">403</div>
                <p class="empty-title">You may need to login.</p>
                <p class="empty-subtitle text-muted">
                  Click the button below to login.
                </p>
                <div class="empty-action">
                  <a href="<?=$config['base_url'];?>/login.php?redirect_page=<?=urlencode($_SERVER["REQUEST_URI"]);?>" class="btn btn-primary">
                    <!-- Download SVG icon from http://tabler-icons.io/i/login -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="11" width="14" height="10" rx="2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                    Login
                  </a>
                </div>
              </div>
              <?php
            }
          ?>

        </div>
      </div>
      <script src="<?=$config['base_url'];?>/dist/js/tabler.min.js"></script>
    </body>
  </html>
  <?php

  db_disconnect($db_connection);
}

function do_logout()
{
  require_once($_SERVER['DOCUMENT_ROOT']."/includes/db_connect.php");

  $db_connection = db_connect();

  $logout_query = $db_connection->query("UPDATE `logins_sessions` SET `ended` = '1' WHERE `logins_sessions`.`ID` = '".$_COOKIE['session_ID']."';");

  db_disconnect($db_connection);

  setcookie('session_ID', null, -1, '/');
}

function login_valid()
{
  if (isset($_COOKIE['session_ID']))
  {
    $session_ID = $_COOKIE['session_ID'];

    require_once($_SERVER['DOCUMENT_ROOT']."/includes/db_connect.php");

    $db_connection = db_connect();

    $session_query = $db_connection->query("SELECT `expiry` FROM `logins_sessions` WHERE `ID`='".$session_ID."' AND `expiry`>CURRENT_TIMESTAMP() AND `ended`='0' ORDER BY `start` DESC LIMIT 1");

    $expiry_date = new DateTime('now');

    if ($session_query and $session_query->num_rows == 1)
    {
      return true;
    }
    else
    {
      do_logout();
      return false;
    }

    db_disconnect($db_connection);
  }
  else
  {
    return false;
  }
}

function get_user_level_and_id()
{
  if (login_valid())
  {
    require_once($_SERVER['DOCUMENT_ROOT']."/includes/db_connect.php");

    $db_connection = db_connect();

    $login_query = $db_connection->query("SELECT `logins`.`user_level`, `logins`.`ID` FROM `logins` LEFT JOIN `logins_sessions` ON `logins`.`ID`=`logins_sessions`.`login_ID` WHERE `logins_sessions`.`ID`='".$_COOKIE["session_ID"]."' LIMIT 1");

    if ($login_query->num_rows == 1)
    {
      $login = $login_query->fetch_assoc();

      return array('user_level' => $login['user_level'], 'user_id' => $login['ID']);
    }

    db_disconnect($db_connection);
  }
  else
  {
    return false;
  }
}

function login_restricted($user_level_required)
{
  require_once($_SERVER['DOCUMENT_ROOT']."/includes/db_connect.php");

  $db_connection = db_connect();

  if (isset($_COOKIE['session_ID']))
  {
    $login_query = $db_connection->query("SELECT `logins`.`user_level` FROM `logins` LEFT JOIN `logins_sessions` ON `logins`.`ID`=`logins_sessions`.`login_ID` WHERE `logins_sessions`.`ID`='".$_COOKIE["session_ID"]."' LIMIT 1");

    if ($login_query->num_rows == 0)
    {
      $current_user_level = 0;
    }
    else
    {
      $current_user_level = $login_query->fetch_assoc()["user_level"];
    }
  }
  else
  {
    $current_user_level = 0;
  }

  if (login_valid() && $current_user_level == $user_level_required)
  {
    return true;
  }
  else if (login_valid() && $current_user_level > 0 && $current_user_level > $user_level_required)
  {
    return true;
  }
  else
  {
    return false;
  }

  db_disconnect($db_connection);
}

// Returns true if you're able to INTERACT with this booking.
function booking_restricted($booking_ensemble, $status)
{
  // $current_user_level
  //  1: Ensemble
  //  2: Keiron
  //  3: Admin
  $user_level_and_id = get_user_level_and_id();
  if ($user_level_and_id)
  {
    $current_user_level = $user_level_and_id['user_level'];
    $current_user_id = $user_level_and_id['user_id'];
  }
  else
  {
    return false;
  }

  // See everything.
  if ($current_user_level == 3)
  {
    return true;
  }
  elseif ($current_user_level == 2 && $status == 1)
  {
    return true;
  }
  elseif ($current_user_level == 1 && $current_user_id == $booking_ensemble)
  {
    if ($status == 0 || $status == 3)
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  else
  {
    return false;
  }
}

// Returns true if you're able to VIEW this booking.
function booking_viewable($booking_ensemble)
{
  // $current_user_level
  //  1: Ensemble
  //  2: Keiron
  //  3: Admin
  $user_level_and_id = get_user_level_and_id();
  if ($user_level_and_id)
  {
    $current_user_level = $user_level_and_id['user_level'];
    $current_user_id = $user_level_and_id['user_id'];
  }
  else
  {
    return false;
  }

  // See everything.
  if ($current_user_level == 3)
  {
    return true;
  }
  elseif ($current_user_level == 2)
  {
    return true;
  }
  elseif ($current_user_level == 1 && $current_user_id == $booking_ensemble)
  {
    return true;
  }
  else
  {
    return false;
  }
}

// Courtesy of: https://www.idiotinside.com/2015/03/29/convert-timestamp-to-relative-time-in-php/
// Accessed: 2022-04-06 16:52:42
function findTimeAgo($past, $now = "now") {
  // sets the default timezone if required 
  // list of supported timezone identifiers 
  // http://php.net/manual/en/timezones.php
  // date_default_timezone_set("Asia/Calcutta"); 
  $secondsPerMinute = 60;
  $secondsPerHour = 3600;
  $secondsPerDay = 86400;
  $secondsPerMonth = 2592000;
  $secondsPerYear = 31104000;
  // finds the past in datetime
  //$past = strtotime($past);
  // finds the current datetime
  if ($now == "now")
  {
    $now = strtotime($now);
  }
  else
  {
    $now = time();
  }

  // creates the "time ago" string. This always starts with an "about..."
  $timeAgo = "";

  // finds the time difference
  $timeDifference = $now - $past;

  // less than 29secs
  if($timeDifference <= 29) {
    $timeAgo = "less than a minute ago";
  }
  // more than 29secs and less than 1min29secss
  else if($timeDifference > 29 && $timeDifference <= 89) {
    $timeAgo = "1 minute ago";
  }
  // between 1min30secs and 44mins29secs
  else if($timeDifference > 89 &&
    $timeDifference <= (($secondsPerMinute * 44) + 29)
  ) {
    $minutes = floor($timeDifference / $secondsPerMinute);
    if ($minutes == 1)
    {
      $timeAgo = "1 minute ago";
    }
    else
    {
      $timeAgo = $minutes." minutes ago";
    }
  }
  // between 44mins30secs and 1hour29mins29secs
  else if(
    $timeDifference > (($secondsPerMinute * 44) + 29)
    &&
    $timeDifference < (($secondsPerMinute * 89) + 29)
  ) {
    $timeAgo = "about 1 hour ago";
  }
  // between 1hour29mins30secs and 23hours59mins29secs
  else if(
    $timeDifference > (
      ($secondsPerMinute * 89) +
      29
    )
    &&
    $timeDifference <= (
      ($secondsPerHour * 23) +
      ($secondsPerMinute * 59) +
      29
    )
  ) {
    $hours = floor($timeDifference / $secondsPerHour);
    if ($hours == 1)
    {
      $timeAgo = "1 hour ago";
    }
    else
    {
      $timeAgo = $hours." hours ago";
    }
  }
  // between 23hours59mins30secs and 47hours59mins29secs
  else if(
    $timeDifference > (
      ($secondsPerHour * 23) +
      ($secondsPerMinute * 59) +
      29
    )
    &&
    $timeDifference <= (
      ($secondsPerHour * 47) +
      ($secondsPerMinute * 59) +
      29
    )
  ) {
    $timeAgo = "1 day ago";
  }
  // between 47hours59mins30secs and 29days23hours59mins29secs
  else if(
    $timeDifference > (
      ($secondsPerHour * 47) +
      ($secondsPerMinute * 59) +
      29
    )
    &&
    $timeDifference <= (
      ($secondsPerDay * 29) +
      ($secondsPerHour * 23) +
      ($secondsPerMinute * 59) +
      29
    )
  ) {
    $days = floor($timeDifference / $secondsPerDay);
    if ($days == 1)
    {
      $timeAgo = "1 day ago";
    }
    else
    {
      $timeAgo = $days." days ago";
    }
  }
  // between 29days23hours59mins30secs and 59days23hours59mins29secs
  else if(
    $timeDifference > (
      ($secondsPerDay * 29) +
      ($secondsPerHour * 23) +
      ($secondsPerMinute * 59) +
      29
    )
    &&
    $timeDifference <= (
      ($secondsPerDay * 59) +
      ($secondsPerHour * 23) +
      ($secondsPerMinute * 59) +
      29
    )
  ) {
    $timeAgo = "about 1 month ago";
  }
  // between 59days23hours59mins30secs and 1year (minus 1sec)
  else if(
    $timeDifference > (
      ($secondsPerDay * 59) + 
      ($secondsPerHour * 23) +
      ($secondsPerMinute * 59) +
      29
    )
    &&
    $timeDifference < $secondsPerYear
  ) {
    $months = round($timeDifference / $secondsPerMonth);
    // if months is 1, then set it to 2, because we are "past" 1 month
    if($months == 1) {
      $months = 2;
    }
    
    $timeAgo = $months." months ago";
  }
  // between 1year and 2years (minus 1sec)
  else if(
    $timeDifference >= $secondsPerYear
    &&
    $timeDifference < ($secondsPerYear * 2)
  ) {
    $timeAgo = "about 1 year ago";
  }
  // Assuming time of 0 to be never
  else if(
    $past == 0
  )
  {
    $timeAgo = "never";
  }
  // 2years or more
  else {
    $years = floor($timeDifference / $secondsPerYear);
    if ($years == 1)
    {
      $timeAgo = "over 1 year ago";
    }
    else
    {
      $timeAgo = "over ".$years." years ago";
    }
  }

  return $timeAgo;
}

function on_same_day($timestamp_start, $timestamp_end)
{
  $start_date = date("Y-m-d", $timestamp_start);
  $end_date = date("Y-m-d", $timestamp_end);

  return ($start_date == $end_date);
}

function timestamp_range($timestamp_start, $timestamp_end)
{
  $on_same_day = on_same_day($timestamp_start, $timestamp_end);

  if ($on_same_day)
  {
    $day        = date("D", $timestamp_start);
    $date       = date("jS F Y", $timestamp_start);
    $time_start = date("G:i", $timestamp_start);
    $time_end   = date("G:i", $timestamp_end);

    return "$day, $date $time_start — $time_end";
  }
  else
  {
    $day_start  = date("D", $timestamp_start);
    $date_start = date("jS F Y", $timestamp_start);
    $time_start = date("G:i", $timestamp_start);

    $day_end  = date("D", $timestamp_end);
    $date_end = date("jS F Y", $timestamp_end);
    $time_end = date("G:i", $timestamp_end);

    return "$day_start, $date_start $time_start — $day_end, $date_end $time_end";
  }
}

?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/kernel.php"); ?>
<?php $db_connection = db_connect(); ?>

<?php
	$sort_options = [
    "datetime" => "Date",
    "ensemble" => "Ensemble",
    "name" => "Name",
    "location" => "Location",
    "status" => "Status"
  ];

  $sort_directions = [
    "DESC" => "Desc.",
    "ASC" => "Asc."
  ];

  $sort  = isset($_GET["sort"]) ?htmlspecialchars($_GET["sort"]) :$sort_options[0];
  $order = isset($_GET["order"])?htmlspecialchars($_GET["order"]):$sort_directions[0];

  if (!in_array($sort, array_keys($sort_options)))
  {
    $sort = array_keys($sort_options)[0];
  }
  if (!in_array($order, array_keys($sort_directions)))
  {
    $order = array_keys($sort_directions)[0];
  }
?>

<?php

	function output_booking($booking, $db_connection)
	{
    // TODO: I know this is terrible programming. Please shoot me.
    $keiron_logo = "https://keironanderson.co.uk/wp-content/uploads/2020/09/keiron_anderson_24_feb.jpg";

    $booking_datetime = new DateTime();
    $booking_datetime->setTimestamp($booking["datetime"]);
    $booking_datetime->setTimezone(new DateTimeZone("Europe/London"));

    $booking_id = $booking["booking_ID"];
    $booking_name = $booking["name"];
    $status = $booking["status"];
    $booking_date = $booking_datetime->format("l, jS F Y");
    $booking_location = $booking["location"];
    $last_updated = FindTimeAgo($booking["updated_datetime"]);

    $ensemble_query = $db_connection->prepare("SELECT `name`, `logo` FROM logins WHERE id = ?;");
    $ensemble_query->bind_param("i", $booking["booking_ensemble"]);
    $ensemble_query->execute();
    $ensemble = $ensemble_query->get_result()->fetch_assoc();
    $ensemble_name = $ensemble["name"];
    $ensemble_logo = $ensemble["logo"];

    $first_status_query = $db_connection->prepare("SELECT a.* FROM `bookings` a INNER JOIN (SELECT `booking_ID`, min(`status`) `status` FROM `bookings` WHERE `deleted`=0 GROUP BY `booking_ID`) b USING(`booking_ID`, `status`) ORDER BY `status` ASC LIMIT 1");
    $first_status_query->execute();
    $first_created_result = $first_status_query->get_result()->fetch_assoc()["updated_datetime"];

    $first_created_datetime = new DateTime();
    $first_created_datetime->setTimestamp($first_created_result);
    $first_created_datetime->setTimezone(new DateTimeZone("Europe/London"));
    $first_created = $first_created_datetime->format("Y-m-d H:i:s");

    // $first_created = "2020-12-01";

    // $ensemble_name = "NSWO";
    // $ensemble_logo = "https://attendance.nsw.org.uk/uploads/ensemble-logos/nswo/NSWO%20social%20icon%20RGB-16.jpg";

    // Items dependend on status.
		$status_responses = [
			0 => "Ensemble created booking",
			1 => "Ensemble submitted booking to Keiron",
			2 => "Keiron declined booking",
			3 => "Keiron accepted booking",
			4 => "Ensemble confirmed final details",
			5 => "Ensemble cancelled booking"
		];
	
		$waiting_for = [
			0 => "Ensemble",
			1 => "Keiron",
			2 => "-",
			3 => "Ensemble",
			4 => "-",
			5 => "-"
		];

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

    $green_option = [
      "",
      "Accept",
      "",
      "",
      "",
      ""
    ];

    $red_option = [
      "",
      "Decline",
      "",
      "",
      "",
      ""
    ];

    $blue_option = [
      "Submit to Keiron",
      "",
      "",
      "Confirm final details",
      "",
      ""
    ];

		?>
		<tr class="<?=booking_viewable($booking["booking_ensemble"])?"opacity-100":"opacity-50";?>">
      <td>
        <?php 
          if (booking_restricted($booking["booking_ensemble"], $booking["status"]) && !($green_option[$status] == "" && $red_option[$status] == "" && $blue_option[$status] == ""))
          {
            ?>
            <div class="badge bg-primary" title="Status code: <?=$status;?>"></div>
            <?php
          }
        ?>
      </td>
			<td>
				<span class="avatar"
					style="background-image: url('<?=$ensemble_logo;?>')"
					title="<?=$ensemble_name;?>"></span>
			</td>
      <td>
				<?=$booking_name;?>
			</td>
			<td>
				<?=$booking_date;?>
				<div class="mt-n1">
					<a href="#" data-bs-toggle="modal" data-bs-target="#add-to-calendar_<?=$booking_id;?>">Add to calendar</a>
				</div>
			</td>
			<td>
				<?=$booking_location;?>
				<div class="mt-n1">
					<a href="https://www.google.com/maps/dir/?api=1&destination=<?=urlencode($booking_location);?>" target="_blank">Get directions</a>
				</div>
			</td>
			<td>
				<span class="text-muted"><?=$first_created;?></span>
			</td>
			<td>
				<?=$status_responses[$status];?>
				<div class="text-muted mt-nl"><?=$last_updated;?></span>
			</td>
			<td>
				<span class="avatar <?=$step_1_opacity[$status];?>"
					style="background-image: url('<?=$ensemble_logo;?>')">
					<span class="badge <?=$step_1_colour[$status];?> text-10">1</span>
				</span>
				<span class="avatar <?=$step_2_opacity[$status];?>"
					style="background-image: url('<?=$keiron_logo;?>');">
					<span class="badge <?=$step_2_colour[$status];?> text-10">2</span>
				</span>
				<span class="avatar <?=$step_3_opacity[$status];?>"
					style="background-image: url('<?=$ensemble_logo;?>');">
					<span class="badge <?=$step_3_colour[$status];?> text-10">3</span>
				</span>
			</td>
			<td>
				<span class="text-muted">
					<?=$waiting_for[$status];?>
				</span>
			</td>
      <td>
        <?php
          if (booking_restricted($booking["booking_ensemble"], $booking["status"]))
          {
            if ($green_option[$status] != "")
            {
              ?>
              <a href="#" class="btn btn-success w-40"><?=$green_option[$status];?></a>
              <?php
            }

            if ($red_option[$status] != "")
            {
              ?>
              <a href="#" class="btn btn-danger w-40"><?=$red_option[$status];?></a>
              <?php
            }

            if ($blue_option[$status] != "")
            {
              ?>
              <a href="#" class="btn btn-primary w-40" data-bs-toggle="modal" data-bs-target="#add-booking" onclick="loadBooking(<?=$booking_id;?>)"><?=$blue_option[$status];?></a>
              <?php
            }

            if ($green_option[$status] == "" && $red_option[$status] == "" && $blue_option[$status] == "")
            {
              ?>
              <span class="text-muted">-</span>
              <?php
            }
            
          }
          else
          {
            echo "-";
          }
        ?>
      </td>
		</tr>

    <?php
      $booking_datetime_utc = new DateTime("now", new DateTimeZone("Europe/London"));
      $booking_datetime_utc->setTimestamp($booking["booking_datetime"]);
      $booking_datetime_utc->setTimezone(new DateTimeZone("UTC"));

      $booking_datetime_end_utc = new DateTime("now", new DateTimeZone("Europe/London"));
      $booking_datetime_end_utc->setTimestamp($booking["booking_datetime"]);
      $booking_datetime_end_utc->setTimezone(new DateTimeZone("UTC"));
      $booking_datetime_end_utc->add(new DateInterval("PT180M"));
    ?>

    <div class="modal modal-blur fade" id="add-to-calendar_<?=$booking_id;?>" tabindex="-1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="modal-title">Add to calendar</div>
            <div class="row g-2 align-items-center">
              <div class="col-6 col-sm-4 col-md-2 col-xl-auto py-3">
                <a target="_blank" href="https://calendar.google.com/calendar/render?action=TEMPLATE&dates=<?=$booking_datetime_utc->format("Ymd\THisZ");?>%2F<?=$booking_datetime_end_utc->format("Ymd\THisZ");?>&details=Generated%20automatically%20by%20bookings.keironanderson.co.uk.&location=<?=urlencode($booking["location"]);?>&text=<?=urlencode($booking["name"]);?>" class="btn w-100 btn-icon" aria-label="Google Calendar" style="color: #ffffff; background-color: #3f7ee8;" onclick="$('#add-to-calendar_<?=$booking_id;?>').modal('hide')">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-google" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M17.788 5.108a9 9 0 1 0 3.212 6.892h-8"></path></svg>
                </a>
              </div>
              <div class="col-6 col-sm-4 col-md-2 col-xl-auto py-3">
                <a target="_blank" href="https://outlook.live.com/calendar/0/deeplink/compose?body=Generated%20automatically%20by%20bookings.keironanderson.co.uk.&enddt=<?=urlencode($booking_datetime_end_utc->format("Y-m-d\TH:i:s+00:00"));?>&location=<?=urlencode($booking["location"]);?>&path=%2Fcalendar%2Faction%2Fcompose&rru=addevent&startdt=<?=urlencode($booking_datetime_utc->format("Y-m-d\TH:i:s+00:00"));?>&subject=<?=urlencode($booking["name"]);?>" class="btn w-100 btn-icon disabled" aria-label="Outlook" style="color: #ffffff; background-color: #1175cc;">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path><path d="M3 7l9 6l9 -6"></path></svg>
                </a>
              </div>
              <div class="col-6 col-sm-4 col-md-2 col-xl-auto py-3">
                <a target="_blank" href="https://calendar.yahoo.com/?desc=Generated%20automatically%20by%20bookings.keironanderson.co.uk.&et=<?=urlencode($booking_datetime_end_utc->format("ymd\THisZ"));?>&in_loc=<?=urlencode($booking["location"]);?>&st=<?=urlencode($booking_datetime_utc->format("ymd\THisZ"));?>&title=<?=urlencode($booking["name"]);?>&v=60" class="btn w-100 btn-icon disabled" aria-label="Yahoo" style="color: #ffffff; background-color: #5b00c8;">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-yahoo" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 6l5 0"></path><path d="M7 18l7 0"></path><path d="M4.5 6l5.5 7v5"></path><path d="M10 13l6 -5"></path><path d="M12.5 8l5 0"></path><path d="M20 11l0 4"></path><path d="M20 18l0 .01"></path></svg>
                </a>
              </div>
              <div class="col-6 col-sm-4 col-md-2 col-xl-auto py-3">
                <a target="_blank" href="https://outlook.office.com/calendar/0/deeplink/compose?body=Generated%20automatically%20by%20bookings.keironanderson.co.uk.&enddt=<?=urlencode($booking_datetime_end_utc->format("Y-m-d\TH:i:s+00:00"));?>&location=<?=urlencode($booking["location"]);?>&path=%2Fcalendar%2Faction%2Fcompose&rru=addevent&startdt=<?=urlencode($booking_datetime_utc->format("Y-m-d\TH:i:s+00:00"));?>&subject=<?=urlencode($booking["name"]);?>" class="btn w-100 btn-icon disabled" aria-label="Office365" style="color: #ffffff; background-color: #cc3802;">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-office" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 18h9v-12l-5 2v5l-4 2v-8l9 -4l7 2v13l-7 3z"></path></svg>
                </a>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
		<?php
	}
?>

<?php
if (login_valid())
{
	?>
<!doctype html>
<html lang="en">

<head>
  <?php include($_SERVER['DOCUMENT_ROOT']."/includes/head.php"); ?>
  <meta name="robots" content="noindex,nofollow">
  <title><?=$title;?></title>
  <script>
    function addNewBooking()
    {
      document.getElementById("submit-add-booking").innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Creating...';
      document.getElementById("submit-add-booking").classList.add("disabled");
      document.getElementById("submit-add-booking").classList.remove("btn-success");
      document.getElementById("submit-add-booking").classList.add("btn-primary");

      var xhttp = new XMLHttpRequest();

      xhttp.open("POST", "<?=$config['base_url'];?>/api/v1/add_new-booking.php", true);
      xhttp.timeout = 5000;
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      xhttp.send(
        "booking-name="  + document.getElementById("booking-name").value +
        "&ensembe-id="   + document.getElementById("ensemble-id").value + 
        "&booking-date=" + document.getElementById("booking-date").value +
        "&booking-time=" + document.getElementById("booking-time").value +
        "&location="     + document.getElementById("location").value +
        "&session-id="   + document.getElementById("session-id").value
      );

      xhttp.onload = function () {
        try {
          var JSON_response = JSON.parse(this.responseText); 
        } catch (error) {
          var JSON_response = {"status": "error", "error_message": "Invalid JSON response from server."};
        }

        if (JSON_response.status == "success") {
          document.getElementById("add-booking-error").style.display = "none";
          document.getElementById("add-booking-error").innerHTML = "";

          document.getElementById("submit-add-booking").classList.remove("btn-primary");
          document.getElementById("submit-add-booking").classList.add("btn-success");
          document.getElementById("submit-add-booking").innerHTML  = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l5 5l10 -10"></path></svg>';
          document.getElementById("submit-add-booking").innerHTML += 'Created!';

          location.reload();

        }
        else {
          document.getElementById("submit-add-booking").classList.remove("disabled");
          document.getElementById("submit-add-booking").classList.remove("btn-success");
          document.getElementById("submit-add-booking").classList.add("btn-primary");
          document.getElementById("submit-add-booking").innerHTML  = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>';
          document.getElementById("submit-add-booking").innerHTML += 'Create draft booking';

          document.getElementById("add-booking-error").style.display = "block";
          document.getElementById("add-booking-error").innerHTML  = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exclamation-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M12 9v4"></path><path d="M12 16v.01"></path></svg>';
          document.getElementById("add-booking-error").innerHTML += 'Error: ' + JSON_response.error_message;
        }

      };

      xhttp.onabort = function (e) {
        document.getElementById("submit-add-booking").classList.remove("disabled");
        document.getElementById("submit-add-booking").classList.remove("btn-success");
        document.getElementById("submit-add-booking").classList.add("btn-primary");
        document.getElementById("submit-add-booking").innerHTML  = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>';
        document.getElementById("submit-add-booking").innerHTML += 'Create draft booking';
          
        document.getElementById("add-booking-error").style.display = "block";
        document.getElementById("add-booking-error").innerHTML  = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="#f44336" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="9"></circle><line x1="12" y1="8" x2="12.01" y2="8"></line><polyline points="11 12 12 12 12 16 13 16"></polyline></svg>';
        document.getElementById("add-booking-error").innerHTML += 'Error: Request aborted';
      };

      xhttp.onerror = function (e) {
        document.getElementById("submit-add-booking").classList.remove("disabled");
        document.getElementById("submit-add-booking").classList.remove("btn-success");
        document.getElementById("submit-add-booking").classList.add("btn-primary");
        document.getElementById("submit-add-booking").innerHTML  = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>';
        document.getElementById("submit-add-booking").innerHTML += 'Create draft booking';
          
        document.getElementById("add-booking-error").style.display = "block";
        document.getElementById("add-booking-error").innerHTML  = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exclamation-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M12 9v4"></path><path d="M12 16v.01"></path></svg>';
        document.getElementById("add-booking-error").innerHTML += 'An error occured.';
      }

      xhttp.ontimeout = function (e) {
        document.getElementById("submit-add-booking").classList.remove("disabled");
        document.getElementById("submit-add-booking").classList.remove("btn-success");
        document.getElementById("submit-add-booking").classList.add("btn-primary");
        document.getElementById("submit-add-booking").innerHTML  = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>';
        document.getElementById("submit-add-booking").innerHTML += 'Create draft booking';
          
        document.getElementById("add-booking-error").style.display = "block";
        document.getElementById("add-booking-error").innerHTML  = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exclamation-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M12 9v4"></path><path d="M12 16v.01"></path></svg>';
        document.getElementById("add-booking-error").innerHTML += 'Creation timed out.';
      };
    }

    function setToNewBooking() {
      document.getElementById("booking-id")    .value = 'not yet created';
      document.getElementById("booking-status").value = 0;
    }

    function loadBooking(booking_id) {
      document.getElementById("booking-id").value = booking_id;
    }
  </script>
</head>

<body>
  <div class="wrapper">
    <?php include($_SERVER['DOCUMENT_ROOT']."/includes/header.php"); ?>
    <?php include($_SERVER['DOCUMENT_ROOT']."/includes/navigation.php"); ?>

    <div class="page-wrapper">
      <div class="page-body">
        <div class="container-xl">

          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Book Keiron for events</h3>
                <div class="card-actions" style="float: right;">
                  <a href="#" data-bs-toggle="modal" data-bs-target="#add-booking" onclick="setToNewBooking()" class="btn btn-primary ms-auto my-2">Add booking</a>
                </div>
              </div>
              <div class="card-body border-bottom py-3 col-form-label">
                <div class="ms-auto text-muted">
                  <form method="get" action="" id="form-sort">
                    <div class="ms-2 d-inline-block">
                      <select class="form-select" name="sort" form="form-sort">
                        <?php
                          foreach ($sort_options as $value => $text)
                          {
                            ?>
                            <option value="<?=$value;?>" <?php if ($value == $sort) { echo "selected"; } ?>><?=$text;?></option>
                            <?php
                          }
                        ?>
                      </select>
                    </div>
                    <div class="ms-2 d-inline-block">
                      <select class="form-select" name="order" form="form-sort">
                        <?php
                          foreach ($sort_directions as $value => $text)
                          {
                            ?>
                            <option value="<?=$value;?>" <?php if ($value == $order) { echo "selected"; } ?>><?=$text;?></option>
                            <?php
                          }
                        ?>
                      </select>
                    </div>
                    <div class="ms-2 d-inline-block">
                      <button type="submit" class="btn btn-warning ms-auto my-2">Change sort</button>
                    </div>
                  </form>
                </div>

                <div class="modal modal-blur fade" id="add-booking" tabindex="-1" style="display: none;" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <form id="form-add-booking">
                        <div class="modal-header">
                          <h5 class="modal-title">Add booking</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="add-booking-error" style="display:none;">
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="mb-3">
                              <label class="form-label">Booking ID</label>
                              <input type="text" class="form-control" name="booking-id" id="booking-id" value="" disabled>
                            </div>
                          </div>
                          <div class="row">
                            <div class="mb-3">
                              <label class="form-label required">Booking name</label>
                              <input type="text" class="form-control" name="booking-name" id="booking-name" placeholder="Your booking name" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="mb-3">
                                <label class="form-label required">Ensemble</label>
                                <select class="form-select" name="ensemble-id" id="ensemble-id" required>
                                  <?php
                                    $ensembles_query = $db_connection->prepare("SELECT * FROM logins WHERE `is_ensemble` = 1 ORDER BY `name` ASC");
                                    $ensembles_query->execute();
                                    $ensembles_result = $ensembles_query->get_result();

                                    $user_level_and_id = get_user_level_and_id();

                                    while($ensemble = $ensembles_result->fetch_assoc())
                                    {
                                      ?>
                                      <option value="<?=$ensemble["ID"];?>" <?=($user_level_and_id["user_level"] == $ensemble["ID"])?"selected":"";?> <?=(($user_level_and_id["ID"] != $ensemble["ID"]) && ($user_level_and_id["user_level"] == 1))?"disabled":"";?>><?=$ensemble['name'];?></option>
                                      <?php
                                    }

                                  ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="mb-3">
                                <label class="form-label required">Event date</label>
                                <div class="input-icon">
                                  <input type="text" name="booking-date" id="booking-date" class="form-control" placeholder="Select a date" value="" style="min-width: 150px;" required>
                                  <span class="input-icon-addon"><!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><rect x="4" y="5" width="16" height="16" rx="2"></rect><line x1="16" y1="3" x2="16" y2="7"></line><line x1="8" y1="3" x2="8" y2="7"></line><line x1="4" y1="11" x2="20" y2="11"></line><line x1="11" y1="15" x2="12" y2="15"></line><line x1="12" y1="15" x2="12" y2="18"></line></svg>
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="mb-3">
                                <label class="form-label required">Event time</label>
                                <input type="time" name="booking-time" id="booking-time" class="form-control" autocomplete="off" value="" required>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <label class="form-label required">Location</label>
                              <input type="text" name="location" id="location" class="form-control" placeholder="Location" value="" required>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                          </a>
                          <button type="button" class="btn btn-primary ms-auto" id="submit-add-booking" onclick="addNewBooking()" disabled>
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                            Create draft booking
                          </button>
                        </div>
                        <input id="session-id" name="session-id" type="hidden" value="<?=$_COOKIE["session_ID"];?>">
                        <input id="booking-status" name="booking-status" type="hidden" value="">
                      </form>
                    </div>
                  </div>
                </div>

                <div class="table-responsive" id="main-content" style="display: block;">
                  <form id="update_bookings">
                    <table id="bookings-table" class="table card-table table-vcenter text-nowrap datatable">
                      <thead>
                        <tr>
                          <th class="sticky-top">
                            
                          </th>
                          <th class="sticky-top">
                            Ensemble
                          </th>
                          <th class="sticky-top">
                            Name
                          </th>
                          <th class="sticky-top">
                            Concert date
                          </th>
                          <th class="sticky-top">
                            Concert location
                          </th>
                          <th class="sticky-top">
                            First created
                          </th>
                          <th class="sticky-top">
                            Last updated
                          </th>
                          <th class="sticky-top">
                            Approval status
                          </th>
													<th class="sticky-top">
                            Waiting for
                          </th>
                          <th class="sticky-top">
                            Action
                          </th>
                        </tr>
                      </thead>
                      <tbody>

												<?php
                          $all_bookings_query = $db_connection->prepare("SELECT a.* FROM `bookings` a INNER JOIN (SELECT `booking_ID`, max(`status`) `status` FROM `bookings` WHERE `deleted`=0 GROUP BY `booking_ID`) b USING(`booking_ID`, `status`) ORDER BY `".$sort."` ".$order);
                          $all_bookings_query->execute();
                          $all_bookings_result = $all_bookings_query->get_result();

                          while($booking = $all_bookings_result->fetch_array(MYSQLI_ASSOC))
                          {
                            output_booking($booking, $db_connection);
                          }
												?>

                      </tbody>
                    </table>
                  </form>
                </div>

                <div class="card" id="placeholder-loading" style="display: none;">
                  <ul class="list-group list-group-flush placeholder-glow">
                    <li class="list-group-item opacity-100">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <div class="avatar avatar-rounded placeholder"></div>
                        </div>
                        <div class="col-7">
                          <div class="placeholder placeholder-xs col-9"></div>
                          <div class="placeholder placeholder-xs col-7"></div>
                        </div>
                        <div class="col-2 ms-auto text-end">
                          <div class="placeholder placeholder-xs col-8"></div>
                          <div class="placeholder placeholder-xs col-10"></div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item opacity-80">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <div class="avatar avatar-rounded placeholder"></div>
                        </div>
                        <div class="col-7">
                          <div class="placeholder placeholder-xs col-9"></div>
                          <div class="placeholder placeholder-xs col-7"></div>
                        </div>
                        <div class="col-2 ms-auto text-end">
                          <div class="placeholder placeholder-xs col-8"></div>
                          <div class="placeholder placeholder-xs col-10"></div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item opacity-60">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <div class="avatar avatar-rounded placeholder"></div>
                        </div>
                        <div class="col-7">
                          <div class="placeholder placeholder-xs col-9"></div>
                          <div class="placeholder placeholder-xs col-7"></div>
                        </div>
                        <div class="col-2 ms-auto text-end">
                          <div class="placeholder placeholder-xs col-8"></div>
                          <div class="placeholder placeholder-xs col-10"></div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item opacity-40">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <div class="avatar avatar-rounded placeholder"></div>
                        </div>
                        <div class="col-7">
                          <div class="placeholder placeholder-xs col-9"></div>
                          <div class="placeholder placeholder-xs col-7"></div>
                        </div>
                        <div class="col-2 ms-auto text-end">
                          <div class="placeholder placeholder-xs col-8"></div>
                          <div class="placeholder placeholder-xs col-10"></div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item opacity-20">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <div class="avatar avatar-rounded placeholder"></div>
                        </div>
                        <div class="col-7">
                          <div class="placeholder placeholder-xs col-9"></div>
                          <div class="placeholder placeholder-xs col-7"></div>
                        </div>
                        <div class="col-2 ms-auto text-end">
                          <div class="placeholder placeholder-xs col-8"></div>
                          <div class="placeholder placeholder-xs col-10"></div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>

              </div>
            </div>
          </div>

					<!-- <div class="col-12">
						<div class="card ">
							<div class="card-body">
								<h3 class="card-title">Process?</h3>
								<p class="text-muted">
									Easy as 1, 2, 3:
									<ol class="text-muted">
										<li>You send out provisional details</li>
										<li>Keiron confirms</li>
										<li>You confirm the final details</li>
									</ol>
								</p>
								<p class="text-muted">
									Stuck? Email Adam.
								</p>
							</div>
							<div class="card-footer">
								<a href="#" class="btn btn-primary">Email Adam</a>
							</div>
						</div>
					</div> -->

        </div>
      </div>
    </div>

    <?php include($_SERVER['DOCUMENT_ROOT']."/includes/footer.php"); ?>

    <script src="<?=$config['base_url'];?>/dist/js/tabler.min.js"></script>
    <script src="./dist/libs/list.js/dist/list.min.js"></script>
    <script src="./dist/libs/litepicker/dist/litepicker.js"></script>
    <script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function() {
        window.Litepicker && (new Litepicker({
          element: document.getElementById('booking-date'),
          buttonText: {
            previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15 6 9 12 15 18" /></svg>`,
            nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>`,
          },
        }));
      });
      // @formatter:on

      const addNewBookingForm = document.getElementById("form-add-booking");
      addNewBookingForm.addEventListener("change", () => {
        document.getElementById("submit-add-booking").disabled = !addNewBookingForm.checkValidity();
      });
    </script>
</body>

</html>
<?php
}
else
{
	output_restricted_page();
}
?>

<?php db_disconnect($db_connection); ?>
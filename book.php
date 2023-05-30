<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/kernel.php"); ?>
<?php $db_connection = db_connect(); ?>

<?php
	$status = 5;

	function output_booking($booking, $db_connection)
	{
    // TODO: I know this is terrible programming. Please shoot me.
    $keiron_logo = "https://keironanderson.co.uk/wp-content/uploads/2020/09/keiron_anderson_24_feb.jpg";

    $booking_datetime = new DateTime();
    $booking_datetime->setTimestamp($booking["datetime"]);
    $booking_datetime->setTimezone(new DateTimeZone("Europe/London"));

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

		?>
		<tr class="<?=booking_viewable($booking["booking_ensemble"])?"opacity-100":"opacity-50";?>">
			<td>
				<span class="avatar"
					style="background-image: url('<?=$ensemble_logo;?>')"
					title="<?=$ensemble_name;?>"></span>
			</td>
			<td>
				<?=$booking_date;?>
				<div class="mt-n1">
					<a href="">Add to calendar</a>
				</div>
			</td>
			<td>
				<?=$booking_location;?>
				<div class="mt-n1">
					<a href="">Get directions</a>
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
            ?>
            <a href="#" class="btn btn-success w-40">Accept</a>
            <a href="#" class="btn btn-danger w-40">Decline</a>
            <?php
          }
          else
          {
            echo "-";
          }
        ?>
      </td>
		</tr>
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
              </div>
              <div class="card-body border-bottom py-3 col-form-label">
                <div class="ms-auto text-muted">
                  <form method="get" action="" id="form-sort">
                    <input type="hidden" name="theme" value="" form="form-sort">
                    <input type="hidden" name="ensemble_ID" value="" form="form-sort">
                    <input type="hidden" name="term_ID" value="" form="form-sort">
                    <div class="ms-2 d-inline-block">
                      <select class="form-select" name="sortby" form="form-sort">
                        <option value="first_name">First name</option>
                        <option value="last_name" selected="">Last name</option>
                        <option value="instrument">Instrument</option>
                        <option value="setup_group">Setup group</option>
                      </select>
                    </div>
                    <div class="ms-2 d-inline-block">
                      <select class="form-select" name="sortdir" form="form-sort">
                        <option value="ASC" selected="">Asc.</option>
                        <option value="DESC">Desc.</option>
                      </select>
                    </div>
                    <div class="ms-2 d-inline-block">
                      <button type="submit" class="btn btn-warning ms-auto my-2">Change sort</button>
                    </div>
                  </form>
                </div>

                <div class="table-responsive" id="main-content" style="display: block;">
                  <form id="update_bookings">
                    <table id="bookings-table" class="table card-table table-vcenter text-nowrap datatable">
                      <thead>
                        <tr>
                          <th class="sticky-top">
                            Ensemble
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
                          $all_bookings_query = $db_connection->prepare("SELECT a.* FROM `bookings` a INNER JOIN (SELECT `booking_ID`, max(`status`) `status` FROM `bookings` WHERE `deleted`=0 GROUP BY `booking_ID`) b USING(`booking_ID`, `status`) ORDER BY `datetime` DESC");
                          $all_bookings_query->execute();
                          $all_bookings_result = $all_bookings_query->get_result();

                          while($booking = $all_bookings_result->fetch_array(MYSQLI_ASSOC))
                          {
                            output_booking($booking, $db_connection);
                          }
                          


													// output_booking(0, "Nottingham Symphonic Windws", "https://attendance.nsw.org.uk/uploads/ensemble-logos/nswo/NSWO%20social%20icon%20RGB-16.jpg", "https://keironanderson.co.uk/wp-content/uploads/2020/09/keiron_anderson_24_feb.jpg", "Saturday 7th October 2023, 19:30", "St John's Church, NG7 2RD", "1 week ago", "about 1 minute ago");
													// output_booking(1, "Nottingham Symphonic Windws", "https://attendance.nsw.org.uk/uploads/ensemble-logos/nswo/NSWO%20social%20icon%20RGB-16.jpg", "https://keironanderson.co.uk/wp-content/uploads/2020/09/keiron_anderson_24_feb.jpg", "Saturday 7th October 2023, 19:30", "St John's Church, NG7 2RD", "1 week ago", "about 1 minute ago");
													// output_booking(2, "Nottingham Symphonic Windws", "https://attendance.nsw.org.uk/uploads/ensemble-logos/nswo/NSWO%20social%20icon%20RGB-16.jpg", "https://keironanderson.co.uk/wp-content/uploads/2020/09/keiron_anderson_24_feb.jpg", "Saturday 7th October 2023, 19:30", "St John's Church, NG7 2RD", "1 week ago", "about 1 minute ago");
													// output_booking(3, "Nottingham Symphonic Windws", "https://attendance.nsw.org.uk/uploads/ensemble-logos/nswo/NSWO%20social%20icon%20RGB-16.jpg", "https://keironanderson.co.uk/wp-content/uploads/2020/09/keiron_anderson_24_feb.jpg", "Saturday 7th October 2023, 19:30", "St John's Church, NG7 2RD", "1 week ago", "about 1 minute ago");
													// output_booking(4, "Nottingham Symphonic Windws", "https://attendance.nsw.org.uk/uploads/ensemble-logos/nswo/NSWO%20social%20icon%20RGB-16.jpg", "https://keironanderson.co.uk/wp-content/uploads/2020/09/keiron_anderson_24_feb.jpg", "Saturday 7th October 2023, 19:30", "St John's Church, NG7 2RD", "1 week ago", "about 1 minute ago");
													// output_booking(5, "Nottingham Symphonic Windws", "https://attendance.nsw.org.uk/uploads/ensemble-logos/nswo/NSWO%20social%20icon%20RGB-16.jpg", "https://keironanderson.co.uk/wp-content/uploads/2020/09/keiron_anderson_24_feb.jpg", "Saturday 7th October 2023, 19:30", "St John's Church, NG7 2RD", "1 week ago", "about 1 minute ago");
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
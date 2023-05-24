<?php

/** 
 * @file calendarPage.php
 * @brief A web page that displays a calendar and events.
*/

// start session
session_start();

// include necessary files
include 'calClass/calClass.php';

// set paths for website header and footer sections of webpage
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";

// include default webpage header
include_once($header_path);



// get current date and render new instance of calendar
$currentDate = date('Y-m-d', strtotime("now"));
$calendar = new Calendar($currentDate);
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<!-- Calendar -->
	<link href="calendarPage.css" rel="stylesheet" type="text/css">
	<link href="calClass/calClass.css" rel="stylesheet" type="text/css">
	<title>Schedule-It Calendar</title>
</head>

<body>
	<br>
	<div class="content calendar-controls">

		<!-- change month-and-year view based on icon click -->
		<?php
		
		// set initial render date variables
		$renderDay = date('d');
		$renderMonth = date('m');
		$renderYear = date('Y');

		// initialize array of months
		$months = array(
			1 => "January",
			2 => "February",
			3 => "March",
			4 => "April",
			5 => "May",
			6 => "June",
			7 => "July",
			8 => "August",
			9 => "September",
			10 => "October",
			11 => "November",
			12 => "December"
		);

		// initialize array of years
		$years = array(
			2018 => "2018", 2019 => "2019", 2020 => "2020", 2021 => "2021", 2022 => "2022", 2023 => "2023",
			2024 => "2024", 2025 => "2025", 2026 => "2026", 2027 => "2027", 2028 => "2028", 2029 => "2029"
		);

		// check if month is going to be changed (1st step in changing month-year view)
		if (isset($_GET['month'])) {
			$newMonth = $_GET['month'];
		} else {
			// default to current month
			$newMonth = $renderMonth;
		}

		// check if year is going to be changed (2nd step in changing month-year view)
		if (isset($_GET['year'])) {
			$newYear = $_GET['year'];
		} else {
			// default to current year
			$newYear = $renderYear;
		}

		// handler to move back one month 
		// check if prev-month arrow was clicked (3rd step in changing month-year view)
		if (isset($_GET['back'])) {	
			// check if it's the edge case (Jan)
			if ($newMonth == 1) {
				// go back a year and set month to Dec if it is edge case
				$newMonth = 12;
				$newYear--;
			} else {
				// otherwise, go back a month
				$newMonth--;
			}
		}

		// handler to move forward one month 
		// check if next-month arrow was clicked (3rd step in changing month-year view)
		if (isset($_GET['forward'])) {
			// check if it's the edge case (Dec)
			if ($newMonth == 12) {
				// go forward a year and set month to Jan if it is edge case
				$newMonth = 1;
				$newYear++;
			} else {
				// otherwise, go forward a month
				$newMonth++;
			}
		}

		// return to current day on calendar
		echo "<div class= 'flex items-center'>";
		echo "Jump to current month: " . "<a href='?month=$renderMonth&year=$renderYear' title='Today' data-toggle='tooltip'><span> <img src='../../img/calendar3.svg'> </span></a> <br>";
		echo "</div>";

		// display arrows to change months
		echo "<div class= 'flex items-center'>";
		echo "Traverse months: <a href='?month=$newMonth&year=$newYear&back=true' justify-content=center title='Previous Month' data-toggle='tooltip'><span> <img src='../../img/chevron-left.svg'> </span></a>";
		echo "<a href='?month=$newMonth&year=$newYear&forward=true' title='Next Month' data-toggle='tooltip'><span> <img src='../../img/chevron-right.svg'> </span></a> <br>";
		echo "</div>";

		// render calendar with new month-year view
		$newDate = date('d-m-Y', strtotime($renderDay . "-" . $newMonth . "-" . $newYear));
		$calendar = new Calendar($newDate);
		?>
	</div>



	<!-- fetch events from database and display them on page -->
	<?php

	//select all timeslot from event to cal the amount of days
	$firstName = "John";
	$lastName = "Doe";
	$onid = "test_onid";
	$color = "#D73F09";
	$dayCount = 1;

	// query from database to get event topic, date, duration etc
	$sql = "SELECT event.topic, options.date
					FROM event
					INNER JOIN options
					ON event.idEvent = options.idEvent";
	
	// display queried event in calendar page after fetching columns specified from database
	if ($result = mysqli_query($conn, $sql)) {
		if (mysqli_num_rows($result) >= 0) {
			while ($row = mysqli_fetch_array($result)) {
				$name = $row['topic'];
				$date = $row['date'];
				// add event to calendar
				$calendar->addEvent($name, $date, $dayCount, $color);
			}

			// Free result set
			mysqli_free_result($result);
		} else {
			// if database is empty
			echo "<p class='lead'><em>No records of events were found.</em></p>";
		}
	} else {
		// if error occured while trying to access database
		echo "ERROR: Could not execute $sql. <br>" . mysqli_error($conn);
	}

	// Close connection to database
	mysqli_close($conn);
	?>




	<!-- Display calendar -->
	<div class="content calendar-home">
		<?= $calendar ?>
	</div>

</body>

</html>

<!-- include default webpage footer -->
<?php include_once($footer_path); ?>
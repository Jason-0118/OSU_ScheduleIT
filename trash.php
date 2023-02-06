<?php 
ob_start();
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .="/OSU_ScheduleIT/footer.php";
include_once($header_path);
?>


<?php
$topic = $_SESSION['topic'];
$location = $_SESSION['location'];
$method = $_SESSION['method'];
$start_date = $_SESSION['start_date'];
$end_date = $_SESSION['end_date'];
$time_duration = $_SESSION['time_duration'];
echo "previous page data => $topic - $location - $method - $start_date - $end_date - $time_duration";
?>

<?php
$topic = $_SESSION['topic'];
$location = $_SESSION['location'];
$method = $_SESSION['method'];
$start_date = $_SESSION['start_date'];
$end_date = $_SESSION['end_date'];
$time_duration = $_SESSION['time_duration'];
echo "previous page data => $topic - $location - $method - $start_date - $end_date - $time_duration";
?>



<?php
$getDay = array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');
//get previous variable
$start_date =  $_SESSION['start_date'];
$end_date =  $_SESSION['end_date'];
$time_duration = $_SESSION['time_duration'];
//
$day_head = new DateTime($start_date);
$day_tail = new DateTime($end_date);
//include selected date
$day_tail->add(new DateInterval('P1D'));
$interval_day = new DateInterval('P1D'); // 1 day interval
$day_range = new DatePeriod($day_head, $interval_day, $day_tail);

echo "<div class = 'grid grid-cols-1 md:grid-cols-4'>";
foreach ($day_range as $col => $date) {
  $hour_head = new DateTime('8:00 AM');
  $hour_tail = new DateTime('5:00 PM');
  $interval_hour = new DateInterval($time_duration); // time duration

  echo "<div class = 'flex flex-col text-center mt-3'>" . "<p class = 'mx-2 text-sm bg-orange'>" . $date->format('Y-m-d') . "</p>" . "<p class = 'font-bold text-2xl '>" . $getDay[$date->format('w')] . "</p>";
  $hour_range = new DatePeriod($hour_head, $interval_hour, $hour_tail);

  echo "<div class = 'grid grid-cols-2 mt-3 '>";
  foreach ($hour_range as  $row => $time) {
    $day = $date->format('Y-m-d');
    $hour = $time->format('g:i A');
    //UTC to PST 8 hours difference
    if ($time_duration == "PT15M" && $row % 4 == 0) {
      echo " <p class='text-sm'>" . $time->format('g:i A') . "</p>" . "<div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='rectangle w-[100px] h-[20px] bg-gray border-[.5px]' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
    } else if ($time_duration == "PT30M" && $row % 2 == 0) {
      echo " <p class='text-sm'>" . $time->format('g:i A') . "</p>" . "<div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='rectangle w-[100px] h-[20px] bg-gray border-[.5px]' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
    } else if ($time_duration == "PT60M") {
      echo " <p class='text-sm'>" . $time->format('g:i A') . "</p>" . "<div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='rectangle w-[100px] h-[20px] bg-gray border-[.5px]' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
    } else {
      echo "<p> </p><div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='rectangle w-[100px] h-[20px] bg-gray border-[.5px]' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
    }
  }
  echo "</div> ";

  echo "</div>";
}

echo "</div>";
?>

<!-- button -->
<div class="flex justify-center mt-[150px] mb-[50px]">
  <input class="button p-3 bg-orange rounded-2xl px-10 cursor-pointer" type="submit" value="Next"></input>
</div>


<?php include_once($footer_path); ?>
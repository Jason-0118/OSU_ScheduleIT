<?php
session_start();

ob_start();
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";
include_once($header_path);
?>

<?php
$topic = $_SESSION['topic'];
$location = $_SESSION['location'];
$method = $_SESSION['method'];
$start_date = $_SESSION['start_date'];
$end_date = $_SESSION['end_date'];
$time_duration = $_SESSION['time_duration'];

// get time slot array via ajax
if (isset($_POST['selected_time_array'])) {
  $_SESSION['selected_time_array'] = $_POST['selected_time_array'];
}

// get text content via ajax
if (isset($_POST['textContent'])) {
  $_SESSION['description'] = $_POST['textContent'];
}


// get checkbox value via ajax
if (isset($_POST['pem_array'])) {
  $_SESSION['pem_array'] = $_POST['pem_array'];
}

// echo "previous page data => $topic - $location - $method - $start_date - $end_date - $time_duration";
// foreach ($_SESSION['selected_time_array'] as $timeslot) {
//   echo "<p>" . gmdate("Y-m-d H:i:s", $timeslot) . "</p>";
// }
// echo $_SESSION['description']. "<br>";
foreach ($_SESSION['pem_array'] as $pem) {
  echo "<p>" . $pem . "</p>";
}
//
// add to database


?>


<!-- display html -->
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


echo "<div class='overflow-x-scroll p-2 flex flex-col items-center md:flex flex-col md:items-center'> ";
//print date head
echo "<div class='flex w-max'> 
            <div class='h-[20px] w-[80px]'>  </div>";
foreach ($day_range as $date) {
  echo "<div class='flex flex-col items-center w-[50px] h-[60px]'>" .
    "<div class='text-sm'>" . $date->format('M') . " " .  ltrim(date("d", strtotime($date->format('Y-m-d'))), '0') . "</div>" .
    "<div class='font-bold text-md'>" . $getDay[$date->format('w')] . "</div>" .
    "</div>";
}
echo "</div>";

$hour_head = new DateTime('8:00 AM');
$hour_tail = new DateTime('5:00 PM');
$interval_hour = new DateInterval($time_duration); // time duration
$hour_range = new DatePeriod($hour_head, $interval_hour, $hour_tail);

echo "<div class='w-max '>";
foreach ($hour_range as $row => $time) {
  echo "<div class='flex'>";
  //print time col
  if ($time_duration == "PT15M" && $row % 4 == 0) {
    echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white '>" . $time->format('g:i A') . "</div>";
  } else if ($time_duration == "PT30M" && $row % 2 == 0) {
    echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white '>" . $time->format('g:i A') . "</div>";
  } else if ($time_duration == "PT60M") {
    echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white '>" . $time->format('g:i A') . "</div>";
  } else {
    echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white '> </div>";
  }

  //print time block
  echo "<div class='flex'>";
  foreach ($day_range as $col => $date) {
    $day = $date->format('Y-m-d');
    $hour = $time->format('g:i A');
    //UTC to PST 8 hours difference
    echo "<div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='rectangle w-[50px] h-[20px] bg-gray border-[.5px]' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
  }
  echo "</div>";
  echo "</div>";
}
echo "</div>";

echo "</div>";
?>


<form method="POST" action="create.php">
  <!-- description -->
  <div class="flex flex-col items-center space-y-3 mt-3 p-3">
    <h2 class="text-2xl font-bold text-orange">Description</h2>
    <div id="editor" class="h-[150px] w-full"> </div>
  </div>

  <!-- file upload -->
  <div class="p-3">
    <div class="p-3 border-[0.5px] border-gray space-y-3">
      <h2>File Download For Attendees</h2>
      <p class="text-gray text-sm">You may only upload a single file. Uploading a second file will overwrite the first. If you need to upload multiple files combine them into a single zip file. Allowed file types: txt, zip, pdf, docx, xlsx, pptx</p>
      <input type="file" name="file">
    </div>
  </div>

  <!-- checkbox -->
  <div class="flex flex-col p-3">
    <div class="md:grid md:grid-cols-4">
      <div><input type="checkbox" id="enable_upload" name="enable_upload"> Enable Attendees to Upload Files</input></div>
      <div id="outter_require_upload" class="hidden text-orange ml-5"><input type="checkbox" id="require_upload" name="require_upload"> Require Attendees to Upload Files</input></div>
    </div>
    <div class="md:grid md:grid-cols-4">
      <div><input type="checkbox" id="enable_comment" name="enable_comment"> Enable Attendees to Comment</input></div>
      <div id="outter_require_comment" class="hidden text-orange ml-5"><input type="checkbox" id="require_comment" name="require_comment"> Require Attendees to Comment</input></div>
    </div>
  </div>

  <!-- button -->
  <div class="flex justify-center mt-[50px] mb-[50px]">
    <input class="button p-3 bg-orange rounded-2xl px-10 cursor-pointer" type="submit" name="submit" value="Submit"></input>
  </div>
</form>





<?php include_once($footer_path); ?>
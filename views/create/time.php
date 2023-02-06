<?php
ob_start();
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";
include_once($header_path);
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


echo "<div class='overflow-x-scroll p-2 md:flex flex-col md:items-center'> ";
  //print date head
  echo "<div class='flex w-max'> 
            <div class='h-[20px] w-[80px]'>  </div>";
    foreach ($day_range as $date) {
      echo "<div class='flex flex-col items-center w-[50px] h-[60px]'>" .
              "<div class='text-sm'>" . $date->format('M') . " ".  ltrim(date("d", strtotime($date->format('Y-m-d'))), '0') . "</div>" . 
              "<div>" . $getDay[$date->format('w')] . "</div>" .
          "</div>";
    }
  echo "</div>";


  $hour_head = new DateTime('8:00 AM');
  $hour_tail = new DateTime('5:00 PM');
  $interval_hour = new DateInterval($time_duration); // time duration
  $hour_range = new DatePeriod($hour_head, $interval_hour, $hour_tail);

  echo "<div class='w-max '>";
    foreach($hour_range as $row => $time){
      echo "<div class='flex'>";
        //print time col
        if ($time_duration == "PT15M" && $row % 4 == 0) {
          echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white '>" . $time->format('g:i A') . "</div>";
        } else if ($time_duration == "PT30M" && $row % 2 == 0) {
          echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white '>" . $time->format('g:i A') . "</div>";
        } else if ($time_duration == "PT60M") {
          echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white '>" . $time->format('g:i A') . "</div>";
        }
        else{
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

<!-- button -->
<div class="flex justify-center mt-[150px] mb-[50px]">
  <input class="button p-3 bg-orange rounded-2xl px-10 cursor-pointer" type="submit" value="Next"></input>
</div>


<?php include_once($footer_path); ?>
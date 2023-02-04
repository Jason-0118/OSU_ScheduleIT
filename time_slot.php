<?php ob_start();
include 'inc/header.php';  ?>


<!-- process bar -->
<div class="w-full bg-gray-200 rounded-full h-1.5 mb-4 dark:bg-gray-700">
  <div class="bg-darkGray h-1 md:h-1.5 rounded-full dark:bg-gray-500" style="width: 66%"></div>
</div>


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

echo "<div class = 'grid grid-cols-1 md:grid-cols-3'>";
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

<script>
  //timeslot
  const rectangles = document.querySelectorAll('.rectangle');
  rectangles.forEach(function(rectangle) {
    rectangle.addEventListener('click', function() {
      if (rectangle.classList.contains('bg-gray')) {
        rectangle.classList.remove('bg-gray');
        rectangle.classList.add('bg-selected_orange');
      } else {
        rectangle.classList.remove('bg-selected_orange')
        rectangle.classList.add('bg-gray')
      }
    })
  });

  //btn
  //find all the selected time and insert into array
  const btn = document.querySelector('.button');
  btn.addEventListener('click', function() {

    selected_time_array = []
    const selected_time = document.querySelectorAll('.bg-selected_orange')
    selected_time.forEach(function(timeslot) {
      value = timeslot.getAttribute('date-time')
      selected_time_array.push(value)
    })
    console.log(selected_time_array)

    $.ajax({
      url:"next.php",
      type: "post",
      data: {selected_time_array: selected_time_array},
      success:function(response){
        console.log("Sent successfully");
        window.location.href = "next.php";
      }
    });
  })
</script>
<?php include 'inc/footer.php'; ?>
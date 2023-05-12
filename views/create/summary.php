<?php
session_start();
ob_start();
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";
include_once($header_path);


//from header('Location: /OSU_ScheduleIT/views/create/summary.php?id=' . $idEvent);
$hashEvent = $_GET['id'];
$invite_link = "http://" . $_SERVER['HTTP_HOST'] . "/OSU_ScheduleIT/views/create/invite.php?id=" . $hashEvent;

//event table
$sql_event = "SELECT topic, location, allowUpload, description, hashUsers FROM event where hashEvent = '$hashEvent' ";
$result = mysqli_query($conn, $sql_event);
$row = mysqli_fetch_assoc($result);

$topic = $row['topic'];
$location = $row['location'];
$allowUpload = $row['allowUpload'];
$description = $row['description'];
$hashUsers = $row['hashUsers'];

if ($allowUpload == 1 || $allowUpload == 2) {
  $allowUpload = "Yes";
} else {
  $allowUpload = "No";
}

//options table
$sql_options = "SELECT date FROM options WHERE idEvent = (SELECT idEvent from event WHERE hashEvent = '$hashEvent')";
$result = mysqli_query($conn, $sql_options);
$selected_date_array = array();
$timeslot = array();
while ($row = mysqli_fetch_assoc($result)) {
  $selected_date_array[] = $row['date'];
  $timeslot[] = strtotime($row['date']);
}
//get start date and end date for graph
$start_date = min(array_map('strtotime', $selected_date_array));
$start_date = date('Y-m-d', $start_date);

$end_date = max(array_map('strtotime', $selected_date_array));
$end_date = date('Y-m-d', $end_date);

$sql_options = "SELECT duration FROM options WHERE idEvent = (SELECT idEvent from event WHERE hashEvent = '$hashEvent') LIMIT 1";
$result = mysqli_query($conn, $sql_options);
$row = mysqli_fetch_assoc($result);
$duration = $row['duration'];


//users table
$sql_users = "SELECT onid FROM users WHERE hashUsers = '$hashUsers' ";
$result = mysqli_query($conn, $sql_users);
$row = mysqli_fetch_assoc($result);

$onid = $row['onid'];

$dic_reservations = array();
//get reservation name list
$sql_reservations_name_list = "select DISTINCT users.firstName, users.lastName, users.hashUsers from users, reservations where users.hashUsers = reservations.hashUsers and reservations.idOptions in (
  select idOptions from options where idEvent = (
  select idEvent from event where hashEvent = '$hashEvent'))";
$result_name_list = mysqli_query($conn, $sql_reservations_name_list);
if (mysqli_num_rows($result_name_list) > 0) {
  foreach ($result_name_list as $name) {
    $full_name = $name['lastName'] . " " . $name['firstName'];
    $hash_user = $name['hashUsers'];
    // echo $hash_user . $full_name . " ";
    $sql_reservations_time_list = "select date from options where idOptions in (select idOptions from reservations where hashUsers = '$hash_user')";
    $result_time_list = mysqli_query($conn, $sql_reservations_time_list);
    while ($row = $result_time_list->fetch_assoc()) {
      $dic_reservations[$full_name][] =  $row['date'];
      // echo $row['date'] . "<br>";
    }
  }
}


if (isset($_POST['submit'])) {
}
?>



<!-- 1: summary -->
<div class="flex justify-center">
  <div class="flex flex-col items-start space-y-3 mt-5 w-[90%]">
    <div class="flex space-x-3 items-center">
      <h2 class="font-bold text-orange text-xl">Summary</h2>
      <button class="bg-orange text-white p-2 px-4 rounded-xl">Edit Info</button>
    </div>
    <div class="border-2 border-orange p-5 space-y-5 md:w-full">
      <div class="grid grid-cols-2">
        <p><span class="font-bold">Title:</span> <?php echo $topic ?></p>
        <p><span class="font-bold">Enable Attendees to Upload Files:</span> <?php echo $allowUpload ?></p>
      </div>
      <div>
        <p><span class="font-bold">Link/Location:</span> <?php echo $location ?></p>
        <div class="grid grid-cols-1 mt-3">
          <div class="font-bold">Selected Date: </div>
          <!-- print small timeslot graph -->
          <?php
          $getDay = array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');

          //
          $day_head = new DateTime($start_date);
          $day_tail = new DateTime($end_date);
          //include selected date
          $day_tail->add(new DateInterval('P1D'));
          $interval_day = new DateInterval('P1D'); // 1 day interval
          $day_range = new DatePeriod($day_head, $interval_day, $day_tail);


          echo "<div class='overflow-x-scroll p-2 flex flex-col'> ";
          //print date header
          echo "<div class='flex w-max'> 
              <div class='h-[20px] w-[80px]'>  </div>";
          foreach ($day_range as $date) {
            echo "<div class='flex flex-col items-center w-[50px] h-[60px]'>" .
              "<div class='text-xs'>" . $date->format('M') . " " .  ltrim(date("d", strtotime($date->format('Y-m-d'))), '0') . "</div>" .
              "<div class='font-bold text-md'>" . $getDay[$date->format('w')] . "</div>" .
              "</div>";
          }
          echo "</div>";

          $hour_head = new DateTime('8:00 AM');
          $hour_tail = new DateTime('5:00 PM');
          $interval_hour = new DateInterval($duration); // time duration
          $hour_range = new DatePeriod($hour_head, $interval_hour, $hour_tail);

          echo "<div class='w-max '>";
          foreach ($hour_range as $row => $time) {
            echo "<div class='flex'>";
            //print time col
            if ($duration == "PT15M" && $row % 4 == 0) {
              echo "<div class='text-xs h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else if ($duration == "PT30M" && $row % 2 == 0) {
              echo "<div class='text-xs h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else if ($duration == "PT60M") {
              echo "<div class='text-xs h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else {
              echo "<div class='text-xs h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '> </div>";
            }

            //print time block
            echo "<div class='flex'>";
            foreach ($day_range as $col => $date) {
              $day = $date->format('Y-m-d');
              $hour = $time->format('g:i A');
              //UTC to PST 8 hours difference
              if (in_array(strtotime("$day $hour"), $timeslot)) echo "<div id='YouTime" . strtotime("$day $hour") . "'  class='w-[50px] h-[20px] bg-orange border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour") . "> </div>";
              else echo "<div id='YouTime" . strtotime("$day $hour") . "'  class='w-[50px] h-[20px] bg-gray border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour") . "> </div>";
            }
            echo "</div>";
            echo "</div>";
          }
          echo "</div>";

          echo "</div>";
          ?>
        </div>
      </div>
      <div>
        <h2 class="font-bold">Description</h2>
        <p><?php echo $description ?></p>
      </div>
    </div>
  </div>
</div>


<!-- 2: link -->
<div class="flex justify-center mt-5">
  <div class="flex w-[90%]">
    <p id="invited_link" class="flex-grow truncate border-2 border-orange p-1"><?php echo $invite_link ?></p>
    <button id="copy_button" class="bg-orange text-white px-1 rounded-sm text-xs">Copy Link</button>
  </div>
</div>

<!-- 3: Attendees -->

<div class="flex justify-center mt-10 ">
  <div class="flex-col flex-start  w-[90%]">
    <h2 class="font-bold text-orange text-xl">Attendees</h2>
    <!-- table -->
    <table class="table-auto w-full">
      <thead>
        <tr>
          <th class="px-1 py-2 ">Name</th>
          <th class="px-1 py-2 ">Date</th>
          <th class="px-1 py-2 ">Time</th>
          <!-- <th class="px-1 py-2 ">File</th> -->
          <th class="px-1 py-2 ">Remove</th>
        </tr>
      </thead>
      <tbody class="text-center">
        <!-- php loop here -->
        <?php
        foreach ($dic_reservations as $key => $value) {
          $timeSlot_array = $value;
          $onid = $key;
          echo "<tr>";
          echo "<td class='border-y px-1 py-2'>" . $onid . "</td>";
          //for loop for date
          echo "<td class='border-y px-1 py-2'>";
          foreach ($timeSlot_array as $timeslot) {
            $timestamp = strtotime($timeslot);
            echo date("Y-m-d", $timestamp) . "<br>";
          }
          echo "</td>";

          //for loop for time
          echo "<td class='border-y px-1 py-2'>";
          foreach ($timeSlot_array as $timeslot) {
            $timestamp = strtotime($timeslot);
            echo date("H:i:s A", $timestamp) . "<br>";
          }
          echo "</td>";

          //for loop for ❌
          echo "<td class='border-y px-1 py-2 cursor-pointer'>";
          foreach ($timeSlot_array as $timeslot) {
            echo "❌" . "<br>";
          }
          echo "</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<!-- 4: register -->
<div class="flex justify-center mt-10 ">
  <div class="w-[90%]">
    <h2 class="font-bold text-orange text-xl">Invited Not Yet Registered</h2>
    <!-- table -->
    <table class="w-full">
      <thead>
        <tr>
          <th class="px-1 py-2">ONID</th>
          <th class="px-1 py-2">Date Sent</th>
        </tr>
      </thead>
      <tbody class="text-center">
        <tr>
          <td class="border-y px-1 py-2">Zhanxin2</td>
          <td class="border-y px-1 py-2">11/16/22 5:04pm</td>
        </tr>
        <tr>
          <td class="border-y px-1 py-2">John</td>
          <td class="border-y px-1 py-2">11/16/22 5:04pm</td>
        </tr>

      </tbody>
    </table>
  </div>
</div>


<!-- invite btn -->
<form method="POST">
  <div class="flex justify-center my-10">
    <div class="w-[90%]">
      <input class="p-1 border-[1px] border-orange" type="text" id="onid" name="onid" placeholder="Enter ONID, e.g: zhanxin2,test,...">
      <input class="bg-orange text-white p-2 px-4 rounded-xl cursor-pointer" type="submit" name="submit" value="Invite Attendee"></input>
    </div>
  </div>
</form>


<?php include_once($footer_path); ?>


<!-- summary.php -->
<script>
  document.getElementById("copy_button").addEventListener("click", function() {
    var content = document.getElementById("invited_link").innerHTML;
    var temp = document.createElement("input");
    temp.setAttribute("value", content);
    // Append the input element to the document
    document.body.appendChild(temp);

    // Select the content in the input element
    temp.select();

    // Copy the selected content to the clipboard
    document.execCommand("copy");

    // Remove the temporary input element
    document.body.removeChild(temp);

    alert("copied link: " + content);

  })
</script>
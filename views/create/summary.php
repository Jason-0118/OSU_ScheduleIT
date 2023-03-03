<?php
session_start();
ob_start();
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";
include_once($header_path);

//from header('Location: /OSU_ScheduleIT/views/create/summary.php?id=' . $idEvent);
$idEvent = $_SESSION['idEvent'];

$sql = "SELECT e.topic, e.location, d.pem, e.hashIdEvent, e.onid, d.timeSlot, d.description, d.duration, d.startDate, d.endDate FROM event as e, eventDetail as d WHERE d.idEvent = e.idEvent AND d.idEvent = '$idEvent' ";
//1: enable_upload, 2:enable_comment, 3:require_upload, 4:require_comment
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$topic = $row['topic'];
$location = $row['location'];
$pem = json_decode($row['pem']);
$timeslot = json_decode($row['timeSlot']);
$description = $row['description'];
$duration = $row['duration'];
$hashIdEvent = $row['hashIdEvent'];
$start_date = $row['startDate'];
$end_date = $row['endDate'];
$onid = $row['onid'];


$enable_upload = "No";
if ($pem[0] == 1) {
  $enable_upload = "Yes";
}
$invite_link = "http://" . $_SERVER['HTTP_HOST'] . "/OSU_ScheduleIT/views/create/invite.php?id=" . $hashIdEvent;

//get all attendees from mysql
// <!-- sql: SELECT * FROM `attendees` WHERE idEventDetail = (select idEventDetail from eventDetail where idEvent = 94); -->
$sql_get_attendees = "SELECT * FROM `attendees` WHERE idEventDetail = (select idEventDetail from eventDetail where idEvent = '$idEvent')";
$result_attendees = mysqli_query($conn, $sql_get_attendees);

// send email via onid



if (isset($_POST['submit'])) {
  // if(!empty($_POST['onid']))
  // {
  //   $onid_string = filter_input(INPUT_POST, 'onid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  //   $onid_array = explode(",", $onid_string);
  //   var_dump($onid_array); 
  //   foreach($onid_array as $name) {


  //   }
  // }
  // $to = "jason.ps0118@gmail.com";
  // $subject = "Invitation email: " ;
  // $message = "message!!";

  // $headers = array(
  //   "MIME-Version" => "1.0",
  //   "Content-Type" => "text/html;charset=UTF-8",
  //   "From" => "zhanxin2@oregonstate.edu",
  //   "Reply-To" => "zhanxin2@oregonstate.edu",
  // );

  // $send = mail($to, $subject, $message, $headers);
  // echo ($send ? "Email is sent" : "error");
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
        <p><span class="font-bold">Enable Attendees to Upload Files:</span> <?php echo $enable_upload ?></p>
      </div>
      <div>
        <p><span class="font-bold">Link/Location:</span> <?php echo $location ?></p>
        <div class="grid grid-cols-1 mt-3">
          <div class="font-bold">Selected Date (<?php if ($duration == "PT15M")
                                                  echo "15mins";
                                                elseif ($duration == "PT30M") echo "30mins";
                                                else echo "60mins" ?>):
          </div>
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
              <div class='h-[20px] w-[60px]'>  </div>";
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
              if (in_array(strtotime("$day $hour - 8 hour"), $timeslot)) echo "<div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='w-[50px] h-[20px] bg-orange border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
              else echo "<div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='w-[50px] h-[20px] bg-gray border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
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
        if (mysqli_num_rows($result_attendees) > 0) {
          foreach ($result_attendees as $row) {
            $timeSlot_array = json_decode($row['timeSlot']);
            $onid = $row['onid'];
            echo "<tr>";
            echo "<td class='border-y px-1 py-2'>" . $onid . "</td>";
            //for loop for date
            echo "<td class='border-y px-1 py-2'>";
            foreach ($timeSlot_array as $timeslot) {
              echo date("Y-m-d", strtotime('+8 hours', $timeslot)) . "<br>";
            }
            echo "</td>";

            //for loop for time
            echo "<td class='border-y px-1 py-2'>";
            foreach ($timeSlot_array as $timeslot) {
              echo date("H:i:s A", strtotime('+8 hours', $timeslot)) . "<br>";
            }
            echo "</td>";

            echo "<td class='border-y px-1 py-2 cursor-pointer'> ❌ </td>";
            echo "</tr>";
          }
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
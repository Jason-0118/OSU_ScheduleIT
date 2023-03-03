<?php

use function PHPSTORM_META\type;

session_start();
ob_start();
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";
include_once($header_path);

//id from url
$hashIdEvent = $_GET['id'];

$sql = "SELECT e.onid, e.topic, e.location, d.pem, d.timeSlot, d.description, d.duration, d.startDate, d.endDate FROM event as e, eventDetail as d WHERE d.idEvent = e.idEvent AND e.hashIdEvent = '$hashIdEvent' ";
//1: enable_upload, 2:enable_comment, 3:require_upload, 4:require_comment
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$ONID = $row['onid'];
$topic = $row['topic'];
$location = $row['location'];
$pem = json_decode($row['pem']);
$timeslot = json_decode($row['timeSlot']);
$description = $row['description'];
$start_date = $row['startDate'];
$end_date = $row['endDate'];
$time_duration = $row['duration'];

$enable_upload = "No";
if ($pem[0] == 1) {
    $enable_upload = "Yes";
}


//------------get total attendees
$sql_get_attendees_num =
    "select count(*) from attendees where idEventDetail = (
    select idEventDetail from eventDetail where eventDetail.idEvent = 
    (
        select idEvent from event where hashIdEvent = '$hashIdEvent'
    )
)";
$result_attendees_num = mysqli_query($conn, $sql_get_attendees_num);
$attendees_num_row = mysqli_fetch_assoc($result_attendees_num);
$total_attendees = $attendees_num_row['count(*)'] + 1;

//----------------------combine all timeslot from a attendees table----------------------
//get timeSlot from creator
$sql_get_creator_timeslot = "select timeSlot from eventDetail where idEvent = 
    (
        select idEvent from event where hashIdEvent = '$hashIdEvent'
    )";
$result_cretor_timslot = mysqli_query($conn, $sql_get_creator_timeslot);
$timeslot_array = json_decode(mysqli_fetch_assoc($result_cretor_timslot)['timeSlot']);
//get attendees timeslots
$sql_get_attendees_timeSlot =
    "select timeSlot from attendees where idEventDetail = (
    select idEventDetail from eventDetail where eventDetail.idEvent = 
    (
        select idEvent from event where hashIdEvent = '$hashIdEvent'
    )
)";
$result_attendees_timeslot = mysqli_query($conn, $sql_get_attendees_timeSlot);
if (mysqli_num_rows($result_attendees_timeslot) > 0) {
    foreach ($result_attendees_timeslot as $timeslot) {
        $each_attendee_timeslot_array = json_decode($timeslot['timeSlot']);
        $timeslot_array = array_merge($timeslot_array, $each_attendee_timeslot_array);
    }
}

$count_timeslot_array = array_count_values($timeslot_array);


?>

<!-- submit timeslot for attendees -->
<?php
if (isset($_POST['timeSlot'])) {
    $_SESSION['timeSlot'] = $_POST['timeSlot'];
}

if (isset($_POST['submit'])) {
    $ONID = "test";

    $timeslot = json_encode($_SESSION['timeSlot']);

    $get_idEventDetail = "SELECT d.idEventDetail FROM eventDetail as d, event as e WHERE e.hashIdEvent = '$hashIdEvent' AND e.idEvent = d.idEvent ";
    $result = mysqli_query($conn, $get_idEventDetail);
    $row = mysqli_fetch_assoc($result);
    $idEventDetail = $row['idEventDetail'];


    $sql = "INSERT INTO attendees(idEventDetail, onid, timeSlot) VALUES('$idEventDetail', '$ONID', '$timeslot')";
    if (mysqli_query($conn, $sql) && !empty($timeslot)) {
        header('Location: /OSU_ScheduleIT/views/meeting/meeting.php');
    }
}

?>


<!-- 1: summary -->
<div class="flex justify-center">
    <div class="flex flex-col items-start space-y-3 mt-5 w-[90%]">
        <div class="flex space-x-3 items-center">
            <h2 class="font-bold text-orange text-xl">Summary</h2>
        </div>
        <div class="border-2 border-orange p-5 space-y-5 md:w-full">
            <div class="grid grid-cols-2">
                <p><span class="font-bold">Creator:</span> <?php echo $ONID ?></p>
                <p><span class="font-bold">Enable Attendees to Upload Files:</span> <?php echo $enable_upload ?></p>
            </div>
            <div class="grid grid-cols-2">
                <p><span class="font-bold">Title:</span> <?php echo $topic ?></p>
                <p><span class="font-bold">Link/Location:</span> <?php echo $location ?></p>
            </div>
            <div>
                <h2 class="font-bold">Description</h2>
                <p><?php echo $description ?></p>
            </div>
        </div>
    </div>
</div>

<form method="POST">
    <div class="flex-col md:flex md:flex-row md:justify-center">
        <!-- -------------------------------------1st--------------------------------------------- -->
        <?php
        $getDay = array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');

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
            <div class='h-[20px] w-[60px]'>  </div>";
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
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else if ($time_duration == "PT30M" && $row % 2 == 0) {
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else if ($time_duration == "PT60M") {
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else {
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '> </div>";
            }

            //print time block
            echo "<div class='flex'>";
            foreach ($day_range as $col => $date) {
                $day = $date->format('Y-m-d');
                $hour = $time->format('g:i A');
                //UTC to PST 8 hours difference
                if (in_array(strtotime("$day $hour - 8 hour"), $timeslot_array)) {
                    echo "<div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='rectangle w-[50px] h-[20px] bg-gray border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
                } else {
                    echo "<div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='w-[50px] h-[20px] bg-gray border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
                }
            }
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";

        echo "</div>";
        ?>

        <!-- -------------------------------------2nd--------------------------------------------- -->

        <?php
        $getDay = array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');

        //
        $day_head = new DateTime($start_date);
        $day_tail = new DateTime($end_date);
        //include selected date
        $day_tail->add(new DateInterval('P1D'));
        $interval_day = new DateInterval('P1D'); // 1 day interval
        $day_range = new DatePeriod($day_head, $interval_day, $day_tail);


        echo "<div class='overflow-x-scroll p-2 flex flex-col items-center md:flex flex-col md:items-center md:ml-[50px] '> ";
        //print date head
        echo "<div class='flex w-max'> 
            <div class='h-[20px] w-[60px]'>  </div>";
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
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else if ($time_duration == "PT30M" && $row % 2 == 0) {
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else if ($time_duration == "PT60M") {
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else {
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '> </div>";
            }

            //print time block
            echo "<div class='flex'>";
            foreach ($day_range as $col => $date) {
                $day = $date->format('Y-m-d');
                $hour = $time->format('g:i A');
                //UTC to PST 8 hours difference
                if (in_array(strtotime("$day $hour - 8 hour"), $timeslot_array)) {

                    $opacity = floor(110 * $count_timeslot_array[strtotime("$day $hour - 8 hour")] / $total_attendees) / 100;
                    // echo $count_timeslot_array[strtotime("$day $hour - 8 hour")] . "-";
                    // echo $total_attendees. "-";
                    // echo $opacity;
                    echo "<div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='rectangle2 w-[50px] h-[20px] bg-orange/[$opacity] border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
                } else echo "<div id='YouTime" . strtotime("$day $hour - 8 hour") . "'  class='w-[50px] h-[20px] bg-gray border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour - 8 hour") . "> </div>";
            }
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";

        echo "</div>";
        ?>
    </div>



    <!-- invite btn -->
    <div class="flex justify-between px-4 md:justify-start md:px-20">
        <input class="attendee_button bg-orange text-white p-2 px-4 rounded-xl my-5 w-[100px] cursor-pointer" type="submit" name="submit" value="Save"></input>
    </div>
</form>



<?php include_once($footer_path); ?>


<!-- invite php -->
<script>
    const rectangles = document.querySelectorAll('.rectangle');
    rectangles.forEach(function(rectangle) {
        rectangle.addEventListener('click', (event) => {
            //change color
            if (rectangle.classList.contains('bg-gray')) {
                rectangle.classList.remove('bg-gray');
                rectangle.classList.add('bg-selected_orange');
            } else {
                rectangle.classList.remove('bg-selected_orange')
                rectangle.classList.add('bg-gray')
            }
            const datetime = event.target.getAttribute("date-time");

            const rectangles2 = document.querySelectorAll('.rectangle2');
            Array.from(rectangles2).forEach(r2 => {
                const r2_date_time = r2.getAttribute("date-time");
                
                if(r2_date_time == datetime && rectangle.classList.contains('bg-selected_orange')){
                    r2.classList.add('border-[2px]');
                    r2.classList.add('border-white');
                }
                else{
                    r2.classList.remove('border-[2px]');
                    r2.classList.remove('border-white');
                }
   
            });



        })
    });


    //attendee_button
    const attendee_button = document.querySelector('.attendee_button');
    attendee_button.addEventListener('click', function() {
        //find all the selected time and insert into array
        timeslot = []
        const selected_time = document.querySelectorAll('.bg-selected_orange')
        selected_time.forEach(function(t) {
            value = t.getAttribute('date-time')
            timeslot.push(value)
        })
        //
        console.log("--->", timeslot)

        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                timeSlot: timeslot,
            },
            success: function(response) {
                console.log("Sent successfully");
            }
        });
    })
</script>
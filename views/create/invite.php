<?php
session_start();
ob_start();
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";
include_once($header_path);

//id from url
$hashEvent = $_GET['id'];

//event table
$sql_event = "SELECT topic, location, allowUpload, description, hashUsers FROM event WHERE hashEvent = '$hashEvent' ";
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

//get reservation slots using options table
$max = 10; //hard code
$sql_options = "SELECT date, totalSlots FROM options WHERE idEvent = (SELECT idEvent FROM event WHERE hashEvent = '$hashEvent')";
$result = mysqli_query($conn, $sql_options);
$timeslot_array = array();
if (mysqli_num_rows($result) > 0) {
    foreach ($result as $row) {
        $timeslot_array[strtotime($row['date'])] = $row['totalSlots'];
    }
}
// var_dump($timeslot_array);
// echo $timeslot_array[1678204800];


?>

<!-- submit timeslot for attendees -->
<?php
if (isset($_POST['timeSlot'])) {
    $_SESSION['timeSlot'] = $_POST['timeSlot'];
}
$firstName = "John";
$lastName = "Doe";
$onid = "test_onid";
$hashUsers = substr(md5($onid), 0, 8);

//check if users already exist
$sql_check_user = "SELECT onid FROM users WHERE onid = '$onid' ";
$result = mysqli_query($conn, $sql_check_user);
$row = mysqli_fetch_assoc($result);
if(!empty($row['onid'])) $user_onid = $row['onid'];
//insert to users table if user not exist
if (!empty($hashUsers) && !empty($onid) && !empty($lastName) && !empty($firstName) && empty($user_onid)) {
    $sql_users = "INSERT INTO users(hashUsers, onid, lastName, firstName) VALUES ('$hashUsers', '$onid', '$lastName', '$firstName')";
    mysqli_query($conn, $sql_users);
}

if (isset($_POST['submit'])) {
    $timeslot = $_SESSION['timeSlot'];
    // echo date('Y-m-d H:i:s', $timeslot[0]);
    //find idOPtions in options table by using hashEvent and date - in a loop
    foreach ($timeslot as $date) {
        $time = date('Y-m-d H:i:s', $date);
        $sql_options = "SELECT idOptions from options WHERE date = '$time' AND idEvent = (SELECT idEvent from event WHERE hashEvent = '$hashEvent')";
        $result = mysqli_query($conn, $sql_options);
        $row = mysqli_fetch_assoc($result);
        $idOptions = $row['idOptions'];
        //then insert data to reservation 
        $sql_reservations = "INSERT INTO reservations(idOptions, hashUsers) VALUES ('$idOptions', '$hashUsers')" ;
        $sql_update_options = "UPDATE options SET totalSlots = totalSlots + 1 WHERE idOptions = '$idOptions'";
        mysqli_query($conn, $sql_reservations);
        mysqli_query($conn, $sql_update_options);
    }
    header('Location: /OSU_ScheduleIT/views/meeting/meeting.php');

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
                <p><span class="font-bold">Creator:</span> <?php echo $onid ?></p>
                <p><span class="font-bold">Enable Attendees to Upload Files:</span> <?php echo $allowUpload ?></p>
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
        $interval_hour = new DateInterval($duration); // time duration
        $hour_range = new DatePeriod($hour_head, $interval_hour, $hour_tail);

        echo "<div class='w-max '>";
        foreach ($hour_range as $row => $time) {
            echo "<div class='flex'>";
            //print time col
            if ($duration == "PT15M" && $row % 4 == 0) {
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else if ($duration == "PT30M" && $row % 2 == 0) {
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else if ($duration == "PT60M") {
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
                if (in_array(strtotime("$day $hour"), $timeslot)) {
                    echo "<div id='YouTime" . strtotime("$day $hour") . "'  class='rectangle w-[50px] h-[20px] bg-gray border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour") . "> </div>";
                } else {
                    echo "<div id='YouTime" . strtotime("$day $hour") . "'  class='w-[50px] h-[20px] bg-gray border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour") . "> </div>";
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
        $interval_hour = new DateInterval($duration); // time duration
        $hour_range = new DatePeriod($hour_head, $interval_hour, $hour_tail);

        echo "<div class='w-max '>";
        foreach ($hour_range as $row => $time) {
            echo "<div class='flex'>";
            //print time col
            if ($duration == "PT15M" && $row % 4 == 0) {
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else if ($duration == "PT30M" && $row % 2 == 0) {
                echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
            } else if ($duration == "PT60M") {
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
                if (in_array(strtotime("$day $hour"), $timeslot)) {
                    $opacity = floor(110 * $timeslot_array[strtotime("$day $hour")] / $max) / 100;
                    echo "<div id='YouTime" . strtotime("$day $hour") . "'  class='rectangle2 w-[50px] h-[20px] bg-orange/[$opacity] border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour") . "> </div>";
                } else echo "<div id='YouTime" . strtotime("$day $hour") . "'  class='w-[50px] h-[20px] bg-gray border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour") . "> </div>";
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

                if (r2_date_time == datetime && rectangle.classList.contains('bg-selected_orange')) {
                    r2.classList.add('border-[2px]');
                    r2.classList.add('border-white');
                } else {
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
<?php
/** 
 * @file meeting.php
 * @brief A web page that displays all meeting events.
*/

// start session
session_start();
ob_start();

// set paths for website header and footer sections of webpage
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";
include_once($header_path);

//account info for test
$onid = "zhangxin2";
$att_onid = "test_onid";

//get all idEvent for creator
$sql_idEvent = "SELECT idEvent FROM event WHERE hashUsers = (SELECT hashUsers FROM users WHERE onid = '$onid' )";
$idEvent_result = mysqli_query($conn, $sql_idEvent);
$idEvent_rows = mysqli_fetch_all($idEvent_result);

//get idOptions from reservation info for as an attendees
$sql_reservations = "SELECT idOptions FROM reservations WHERE hashUsers = (SELECT hashUsers FROM users WHERE onid = '$att_onid' )";
$reservations_result = mysqli_query($conn, $sql_reservations);
$reservations_rows = mysqli_fetch_all($reservations_result);
?>


<!-- Switch button -->
<div class="flex justify-center mt-10 ">
    <div class="w-[90%]">
        <button id="creator_trigger" class='bg-orange text-white p-1 border-2 text-sm'>Creator</button>
        <button id="attendee_trigger" class='bg-white text-orange p-1 border-2 text-sm'>Attendee</button>
    </div>
</div>

<!-- form -->
<div id="attendee" class="flex justify-center mt-10 hidden">
    <div class="w-[90%]">
        <h2 class="font-bold text-orange text-xl">Attendee</h2>
        <!-- table -->
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-1 py-2">Topic</th>
                    <th class="px-1 py-2">Location/Link</th>
                    <th class="px-1 py-2">Description</th>
                    <th class="px-1 py-2">Date</th>
                </tr>
            </thead>
            <tbody class="text-center">

                <?php
                //variables
                $i = 0;
                $past_reservation = null;
                $current_time = time();
                $topic;
                $location;
                $description;
                $date;
                // loop through to display data from database
                foreach ($reservations_rows as $row) {
                    foreach ($row as $idOptions) {
                        $sql_attendee = "SELECT o.date, e.topic, e.location, e.description FROM options as o, event as e WHERE o.idOptions = '$idOptions' AND o.idEvent = e.idEvent";
                        $attendee_result = mysqli_query($conn, $sql_attendee);
                        $attendee_rows = mysqli_fetch_assoc($attendee_result);

                        // filter upcoming events
                        if (strtotime($attendee_rows['date']) > $current_time) {
                            echo "<tr>";
                            echo " <td class='border-y px-1 py-2'> ";
                            echo $attendee_rows['topic'];
                            echo "</td>";

                            echo " <td class='border-y px-1 py-2'> ";
                            echo $attendee_rows['location'];
                            echo "</td>";

                            echo " <td class='border-y px-1 py-2'> ";
                            echo $attendee_rows['description'];
                            echo "</td>";

                            echo " <td class='border-y px-1 py-2'> ";
                            echo $attendee_rows['date'];
                            echo "</td>";
                        } else {
                            $past_reservation[$i]['topic'] = $attendee_rows['topic'];
                            $past_reservation[$i]['location'] = $attendee_rows['location'];
                            $past_reservation[$i]['description'] = $attendee_rows['description'];
                            $past_reservation[$i]['date'] = $attendee_rows['date'];
                            ++$i;
                        }
                    }
                }
                // displat all the past event
                if($past_reservation != null){
                    foreach ($past_reservation as $row) {
                        echo "<tr>";
                        echo " <td class='border-y px-1 py-2 '> ";
                        echo $attendee_rows['topic'];
                        echo "</td>";
    
                        echo " <td class='border-y px-1 py-2'> ";
                        echo $attendee_rows['location'];
                        echo "</td>";
    
                        echo " <td class='border-y px-1 py-2'> ";
                        echo $attendee_rows['description'];
                        echo "</td>";
    
                        echo " <td class='border-y px-1 py-2 line-through decoration-pink-500'> ";
                        echo $attendee_rows['date'];
                        echo "</td>";
                    }
                }
                ?>

            </tbody>
        </table>
    </div>
</div>

<!-- form -->
<div id="creator" class="flex justify-center mt-10 ">
    <div class="w-[90%]">
        <h2 class="font-bold text-orange text-xl">Creator</h2>
        <!-- table -->
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-1 py-2">Topic</th>
                    <th class="px-1 py-2">Location/Link</th>
                    <th class="px-1 py-2">Description</th>
                    <th class="px-1 py-2">Detail</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                //variables
                $topic;
                $location;
                $description;
                $detail;
                // loop through to display data from database
                foreach ($idEvent_rows as $row) {
                    foreach ($row as $idEvent) {
                        $sql_event = "SELECT topic, location, description, hashEvent FROM event WHERE idEvent = '$idEvent' ";
                        $event_result = mysqli_query($conn, $sql_event);
                        $event_rows = mysqli_fetch_assoc($event_result);

                        echo "<tr>";
                        echo " <td class='border-y px-1 py-2'> ";
                        echo $event_rows['topic'];
                        echo "</td>";

                        echo " <td class='border-y px-1 py-2'> ";
                        echo $event_rows['location'];
                        echo "</td>";

                        echo " <td class='border-y px-1 py-2'> ";
                        echo $event_rows['description'];
                        echo "</td>";

                        echo "<td class='px-1 py-2'> <a href='/OSU_ScheduleIT/views/create/summary.php?id=" . $event_rows['hashEvent'] . "'><button class='bg-orange text-white p-2 px-4 rounded-xl'>Check</button></th>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once($footer_path); ?>

<script>
    // control the button to switch displaying creator or attendee
    var creator_form = document.getElementById("creator");
    var attendee_form = document.getElementById("attendee");
    var creator_trriger = document.getElementById("creator_trigger");
    var attendee_trriger = document.getElementById("attendee_trigger");

    creator_trriger.addEventListener("click", function() {
        if (creator_trriger.classList.contains('bg-white')) {

            creator_trriger.classList.remove('bg-white');
            creator_trriger.classList.remove('text-orange');
            creator_trriger.classList.add('bg-orange');
            creator_trriger.classList.add('text-white');

            attendee_trriger.classList.remove('bg-orange');
            attendee_trriger.classList.remove('text-white');
            attendee_trriger.classList.add('bg-white');
            attendee_trriger.classList.add('text-orange');

            creator_form.classList.remove('hidden')
            attendee_form.classList.add('hidden')

        }
    })

    attendee_trriger.addEventListener("click", function() {
        if (attendee_trriger.classList.contains('bg-white')) {

            attendee_trriger.classList.remove('bg-white');
            attendee_trriger.classList.remove('text-orange');
            attendee_trriger.classList.add('bg-orange');
            attendee_trriger.classList.add('text-white');

            creator_trriger.classList.remove('bg-orange');
            creator_trriger.classList.remove('text-white');
            creator_trriger.classList.add('bg-white');
            creator_trriger.classList.add('text-orange');

            creator_form.classList.add('hidden')
            attendee_form.classList.remove('hidden')
        }
    })
</script>
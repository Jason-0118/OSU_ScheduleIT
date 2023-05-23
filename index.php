<?php
/** 
 * @file index.php
 * @brief Home page
*/
session_unset();
ob_start();
session_start();
include_once 'header.php';
?>


<?php
// Get the JSON string from the request body
$jsonString = file_get_contents('php://input');

$topicErr = $locationErr = '';
//store start_date, end_date, time_duration
$start_date = $end_date = $convert_start_date = $convert_end_date =  '';
$startDateErr = $endDateErr = '';

//form submit
if (isset($_POST['submit'])) {
    // validate topic
    if (empty($_POST['topic'])) {
        $topicErr = "Title is required!";
    } else {
        $_SESSION['topic'] = filter_input(INPUT_POST, 'topic', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // validate location
    if (empty($_POST['location'])) {
        $locationErr = "Location is required!";
    } else {
        $_SESSION['location'] = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    //assign value to method
    $_SESSION['method'] = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //validate start_date
    if (empty($_POST['start_date'])) {
        $startDateErr = "Start Date is required!";
    } else {
        $_SESSION['start_date'] = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $start_date = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    //validate end_date
    if (empty($_POST['end_date'])) {
        $endDateErr = "End Date is required!";
    } else {
        $_SESSION['end_date'] = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $end_date = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    //time duration
    $_SESSION['time_duration'] = filter_input(INPUT_POST, 'time_duration', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $format = "Y-m-d";
    $convert_start_date = DateTime::createFromFormat($format, $start_date);
    $convert_end_date = DateTime::createFromFormat($format, $end_date);

    if (empty($topicErr) && empty($locationErr) && (empty($startDateErr) && empty($endDateErr) && ($convert_start_date <= $convert_end_date))) {
        header('Location: /OSU_ScheduleIT/views/create/create.php');
        if (mysqli_query($conn, $sql)) {
            // success
            header('Location: time.php');
        } else {
            // error
            echo 'Error: ' . mysqli_error($conn);
        }
    }
}
?>


<div class="relative">
    <!-- bg -->
    <div class=" min-h-screen bg-cover bg-no-repeat bg-fixed bg-center opacity-60" style="background-image: url('https://visitosu.oregonstate.edu/sites/visitosu.oregonstate.edu/files/0319_osufoundation_1153_0.jpg')"></div>

    <!-- info -->
    <form method="POST" action="index.php" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 space-y-10 w-[65%]">
        <!-- topic -->
        <div class="flex flex-col items-center space-y-3">
            <P class="text-[18px] font-bold text-orange md:text-[24px]">Topic <?php if (!empty($topicErr)) : ?>
                    <span class="text-red-600 text-sm underline p-2">Topic is required!</span>
                <?php endif; ?>
            </P>
            <div class="flex justify-center w-full">
                <input type="text" id="topic" name="topic" placeholder="Enter the topic..." class="peer p-2 grow">
                <select class="form-select bg-orange font-bold p-2" id="method" name="method">
                    <option value="in_person">In Person</option>
                    <option value="virtual">Virtual</option>
                </select>
            </div>

        </div>
        <!-- location -->
        <div class="flex flex-col items-center space-y-3">
            <P class="text-[18px] font-bold text-orange md:text-[24px]">Location <?php if (!empty($locationErr)) : ?>
                    <span class="text-red-600 text-sm underline p-2">Location is required!</span>
                <?php endif; ?>
            </P>
            <input class="p-2 w-full" type="text" id="location" name="location" placeholder="Enter your location or meeting link">

        </div>

        <!-- select date and time duration -->
        <div class="flex flex-col justify-around space-y-5 md:flex-row md:items-center md:h-[200px] md:w-full">

            <!-- duration -->
            <div class="flex flex-col items-center space-y-3">
                <p class="text-[18px] font-bold text-orange md:text-[24px]">Timeslot Duration</p>
                <select class="bg-orange font-bold p-2" id="time_duration" name="time_duration">
                    <option value="PT15M">15 mins</option>
                    <option value="PT30M">30 mins</option>
                    <option value="PT60M">60 mins</option>
                </select>
            </div>

            <!-- date range -->
            <div class="flex justify-center items-center space-x-5">
                <input type="date" id="start_date" name="start_date">
                <span class="font-bold">to</span>
                <input type="date" id="end_date" name="end_date">
                <?php if (!empty($startDateErr) || !empty($endDateErr)) : ?>
                    <p class="text-red-600 text-sm underline p-2">Date is required!</p>
                <?php endif; ?>

                <?php if ($convert_start_date > $convert_end_date) : ?>
                    <p class="text-red-600 text-sm underline p-2">End date is before start date!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- button -->
        <div class="flex justify-center">
            <input class="p-3 bg-orange rounded-2xl px-10 cursor-pointer" type="submit" name="submit" value="Create"></input>
        </div>
    </form>
</div>






<?php include_once 'footer.php'; ?>


<!-- index.php -->
<script>
    function Menu(self) {
        const list = document.querySelector("ul");
        self.name === 'menu-outline' ? (self.name = "close-outline", list.classList.add("top-[80px]"), list.classList.add("opacity-100"), list.classList.add("z-[100]")) :
            (self.name = "menu-outline", list.classList.remove("top-[80px]"), list.classList.remove("opacity-100"), list.classList.remove("z-[100]"))
    }

    function Dropdown() {
        const list = document.getElementById("dropdown");
        list.classList.toggle("hidden");
    }
</script>
<?php ob_start();
include 'inc/header.php'; ?>

<?php
//store start_date, end_date, time_duration
$start_date = $end_date = $convert_start_date = $convert_end_date =  '';
$startDateErr = $endDateErr = '';

if (isset($_POST['submit'])) {
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

    //redirect to next page
    if (empty($startDateErr) && empty($endDateErr) && ($convert_start_date <= $convert_end_date)) {
        header('Location: time_slot.php');
    }
}
?>

<!-- process bar -->
<div class="w-full bg-gray-200 rounded-full h-1.5 mb-4 dark:bg-gray-700">
    <div class="bg-darkGray h-1 md:h-1.5 rounded-full dark:bg-gray-500" style="width: 33%"></div>
</div>

<?php if (!empty($startDateErr) || !empty($endDateErr)) : ?>
    <p class="text-red-600 text-xl bg-black/70 p-2 flex justify-center">Date is required!</p>
<?php endif; ?>

<?php if ($convert_start_date > $convert_end_date) : ?>
    <p class="text-red-600 text-xl bg-black/70 p-2 flex justify-center">Start date is before end date!</p>
<?php endif; ?>
<form method="POST" action="time.php">
    <div class="flex flex-col justify-around space-y-24 mt-[100px] md:flex-row md:h-[300px] md:w-full">
        <!-- date range -->
        <div class="flex justify-center items-center space-x-5">
            <input type="date" id="start_date" name="start_date">
            <span class="font-bold">to</span>
            <input type="date" id="end_date" name="end_date">
        </div>

        <!-- duration -->
        <div class="flex flex-col items-center  space-y-3">
            <p class="text-[24px] font-bold text-orange">Timeslot Duration</p>
            <select class="bg-orange font-bold p-2" id="time_duration" name="time_duration">
                <option value="PT15M">15 mins</option>
                <option value="PT30M">30 mins</option>
                <option value="PT60M">60 mins</option>
            </select>
        </div>

    </div>


    <!-- button -->
    <div class="flex justify-center mt-[150px] mb-[50px]">
        <input class="p-3 bg-orange rounded-2xl px-10 cursor-pointer" type="submit" name="submit" value="Next"></input>
    </div>

</form>


<?php include 'inc/footer.php'; ?>
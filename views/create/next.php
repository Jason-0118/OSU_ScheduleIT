<?php ob_start();
include 'inc/header.php';  ?>

<!-- process bar -->
<div class="w-full bg-gray-200 rounded-full h-1.5 mb-4 dark:bg-gray-700">
    <div class="bg-darkGray h-1 md:h-1.5 rounded-full dark:bg-gray-500" style="width: 100%"></div>
</div>


<?php
if (isset($_POST['selected_time_array'])) {
    $selected_time_array = $_POST['selected_time_array'];
    $_SESSION['selected_time_array'] = $selected_time_array;
  }
  foreach($_SESSION['selected_time_array'] as $timeslot){
    echo "<p>" . gmdate("Y-m-d H:i:s", $timeslot) . "</p>"; 
  }

?>

<?php include 'inc/footer.php'; ?>
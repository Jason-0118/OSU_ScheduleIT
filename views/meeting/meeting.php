<?php
session_start();

ob_start();
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";
include_once($header_path);


$onid = "zhangxin2";
//get all idEvent
$sql_idEvent = "SELECT idEvent FROM event WHERE hashUsers = (SELECT hashUsers FROM users WHERE onid = '$onid' )";
$idEvent_result = mysqli_query($conn, $sql_idEvent);
$idEvent_rows = mysqli_fetch_assoc($idEvent_result);
$idEvent_amount = count($idEvent_rows);
var_dump ($idEvent_rows);

if($idEvent_amount === 2){
    echo($idEvent_rows[0][0]);
}
//get data from sql
$sql_options = "SELECT * FROM options WHERE idEvent =  (SELECT idEvent FROM event WHERE hashUsers = (SELECT hashUsers FROM users WHERE onid = '$onid' )) ";
// $result = mysqli_query($conn, $sql_options);
// $rows = mysqli_fetch_all($result);



?>




<?php include_once($footer_path); ?>
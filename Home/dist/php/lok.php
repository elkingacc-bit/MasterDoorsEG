<?php 
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
$key = '__^%&Q@$&*!@#$%^&*^__';
$sqlGetName = "SELECT * FROM `systeminfo`";
$queryGetName = mysqli_query($link,$sqlGetName)or die("Error System Info Data");
$resGetName = mysqli_fetch_array($queryGetName);
function getMachineId() {
    $fingerprint = [php_uname(), disk_total_space('.'), filectime('/'), phpversion()];
    return hash('sha256', json_encode($fingerprint));
}
$HID =  getMachineId();

$data = ($resGetName[2].$HID);


?>
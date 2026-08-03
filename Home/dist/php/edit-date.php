<?php
// Connexion à la base de données
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
 
$UserCode = $_SESSION['code'];
if (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])){

	$id = $_POST['Event'][0];
	$start = date("Y-m-d h:i:s",strtotime($_POST['Event'][1]));
	$end = $_POST['Event'][2];
	$customer = $_POST['Event'][3];

	$sqlEditEvent = "UPDATE `events` SET `customerName` = '$customer', `start` = '$start', `end` = '$end',
	`userCode` = $UserCode WHERE `id` = $id ";

  $prepareQuery = mysqli_query($link,$sqlEditEvent);

 echo 1;
	
}

	
// header('Location: '.$_SERVER['HTTP_REFERER']);
?>

<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
require_once('sanitize.php');
$UserCode = $_SESSION['code'];
if (isset($_POST['title'])) {
 
	$title = sanitizeInput($_POST['title']);
	$description = sanitizeInput($_POST['description']);
	$start = $_POST['start'];
	$start= strtotime($start);
	$start=date('Y-m-d H:i:s', $start); 
	$end = $_POST['end'];
	$end= strtotime($end);
	$end=date('Y-m-d H:i:s', $end); 
	$color = sanitizeInput($_POST['color']);
	$customer = sanitizeInput($_POST['customer']);
//echo "test ->" . $start;	

	$sqlAddEvent = "INSERT INTO events(`title`,`customerName`, `userCode`, `description`, `start`, `end`,
	 `color`) values ('$title', '$customer', $UserCode,'$description', '$start', '$end', '$color')";
	$prepareQuery =mysqli_query($link,$sqlAddEvent);

echo 1;
}


//header('Location: '.$_SERVER['HTTP_REFERER']);
?>

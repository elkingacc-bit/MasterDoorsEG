<?php

@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
require_once('sanitize.php');
$UserCode = $_SESSION['code'];

if (isset($_POST['delete']) && isset($_POST['id'])){
	
	$id = $_POST['id'];

	$sql = "DELETE FROM `events` WHERE `id` = $id";

	$prepareQuery = mysqli_query($link,$sql);
	echo 1;
	
} else if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['color']) && isset($_POST['id'])){
	
	$id = $_POST['id'];
	$title = $_POST['title'];
	$description = $_POST['description'];
	$color = $_POST['color'];
	$customer = $_POST['customer'];
	
	$sql = "UPDATE `events` SET  `title` = '$title',`customerName` = '$customer', 
	`description` = '$description', `color` = '$color' WHERE `id` = $id ";

	$prepareQuery = mysqli_query($link,$sql);
	echo 1;
}


?>

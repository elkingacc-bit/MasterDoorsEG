<?php
require_once('../utils/auth.php');
require_once('../utils/sanitize.php');

if (isset($_POST['title'])) {

	$title = sanitizeInput($_POST['title']);
	$description = sanitizeInput($_POST['description']);
	$start = $_POST['start'];
	$end = $_POST['end'];
	$color = sanitizeInput($_POST['color']);

	$sql2 = "INSERT INTO events(title, description, start, end, color) values ('$title', '$description', '$start', '$end', '$color')";
	
	$prepareQuery =mysqli_query($auth,$sql2);

}

header('Location: '.$_SERVER['HTTP_REFERER']);
?>

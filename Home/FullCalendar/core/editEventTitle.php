<?php

require_once('../utils/auth.php');
if (isset($_POST['delete']) && isset($_POST['id'])){
	
	$id = $_POST['id'];

	$sql = "DELETE FROM `events` WHERE `id` = $id";

	$prepareQuery = mysqli_query($auth,$sql);
	
} else if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['color']) && isset($_POST['id'])){
	
	$id = $_POST['id'];
	$title = $_POST['title'];
	$description = $_POST['description'];
	$color = $_POST['color'];
	
	$sql = "UPDATE `events` SET  `title` = '$title', `description` = '$description', `color` = '$color' WHERE 
	`id` = $id ";

	$prepareQuery = mysqli_query($auth,$sql);
}

header('Location: ../index.php');	
?>

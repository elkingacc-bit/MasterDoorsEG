<?php
// Connexion à la base de données
require_once('../utils/auth.php');

if (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])){

	$id = $_POST['Event'][0];
	$start = $_POST['Event'][1];
	$end = $_POST['Event'][2];

	$sql = "UPDATE `events` SET  `start` = '$start', `end` = '$end' WHERE `id` = $id ";

  $prepareQuery = mysqli_query($auth,$sql);

 echo 'OK';
	
}

	
// header('Location: '.$_SERVER['HTTP_REFERER']);
?>

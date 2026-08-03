<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
$descripCode = $_POST['locationVal'];
	$getItemLocaltion="SELECT `location` FROM `warehouse`  WHERE `description` = $descripCode ";
	$queryItemLocaltion=mysqli_query($link,$getItemLocaltion)or die("ERROR :01-IL_IL_S");
	$resItemLocaltion=mysqli_fetch_assoc($queryItemLocaltion);
		
		if($resItemLocaltion['location'] == NULL || $resItemLocaltion['location'] == "")
		{
			echo "N/A";
		}
		else
		{
			echo "$resItemLocaltion[location]";
		}
?>

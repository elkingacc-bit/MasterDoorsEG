<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

if(!empty($_SESSION['username']))
{

$asKitName = $_POST['textEdited'];
$asKitId = $_POST['AsKitIRowId'];

$sqlCheckName = "SELECT `kitName` FROM `assemblykits` WHERE `kitName` = '$asKitName' AND `id` != $asKitId ";
$queryCheckName = mysqli_query($link,$sqlCheckName)or die("ERROR :01-CNS_ANS_S"); 

	if(mysqli_num_rows($queryCheckName) > 0)
	{
		echo 0;
	}
	else
	{
		$sqlGetGName = "SELECT `kitName` FROM `assemblykits` WHERE  `id` = $asKitId ";
		$queryGetGName = mysqli_query($link,$sqlGetGName)or die("ERROR :01-CNS_ANS_S"); 
		$resGetGName= mysqli_fetch_assoc($queryGetGName);
		$oldName = $resGetGName['kitName'];
		
		$sqlEditGrouping = "UPDATE `assemblykits` SET `kitName` = '$asKitName' WHERE `id` = $asKitId ";
		mysqli_query($link,$sqlEditGrouping)or die("ERROR :02-CNS_ANS_S"); 
		
			
		$action="Edited Assembly Kit Name from $oldName to $asKitName";
		$logRef=12;
		include_once("aduLog.php");
			echo 1;
			exit();
	}
}
else
{
	echo 9;
	exit();
}
?>
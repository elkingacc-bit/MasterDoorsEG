<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$jobRID = $_POST['jobRIdR'];
	
	
	$sqlGetAllNewJob="SELECT  `projectName` FROM `job` WHERE `jobId` = $jobRID";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	$porject = $resGetAllNewJob['projectName'];
	
	$sqlReturnJob = "UPDATE `job` SET `jobref` = 1, `offerStatus` = 'Returned' WHERE `jobId` = $jobRID";
	mysqli_query($link,$sqlReturnJob)or die("ERROR :01-AU_AU_S");
	
	$action="Retuen offer to edit for project : $porject";
	$logRef=5;	
	include_once("aduLog.php");
	
	echo 1;
	exit();
?>	
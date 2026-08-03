<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['chgeJRob'];
$ItemRowId = $_POST['chgeItemRowId'];
$ItemRef = $_POST['chgeItemRefVal'];
$ItemName = $_POST['chgeItemName'];
 
$sqlGetJobData = "SELECT  `projectName` FROM `job` WHERE `jobId` = $jobRowId";
$queryGetJobData = mysqli_query($link,$sqlGetJobData)or die("ERROR :01-ANJ_GCN_S");
$resultGetJobData = mysqli_fetch_assoc($queryGetJobData);

$sqlGetOldRef = "SELECT  `itemRef` FROM `itemoffer` WHERE `id` = $ItemRowId";
$queryGetOldRef = mysqli_query($link,$sqlGetOldRef)or die("ERROR :01-ANJ_GCN_S");
$resultGetOldRef = mysqli_fetch_assoc($queryGetOldRef);

$oldRef = $resultGetOldRef['itemRef'];
$Project = $resultGetJobData['projectName'];


$sqlCahngeItemRef = "UPDATE `itemoffer` SET `itemRef` = '$ItemRef' WHERE `id` = $ItemRowId";
mysqli_query($link,$sqlCahngeItemRef)or die("ERROR :02-ANJ_GCN_S");

$sqlCahngeItemHWRef = "UPDATE `offerproperties` SET `offerItemRef` = '$ItemRef' WHERE `ioidref` = $ItemRowId";
mysqli_query($link,$sqlCahngeItemHWRef)or die("ERROR :02-ANJ_GCN_S");


$action="Change HW Group Ref For ($oldRef) To ($ItemRef) For Item $ItemName in Project $Project ";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
	exit();

?>
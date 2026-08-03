<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['RJROIF'];
$rowIDHW = $_POST['TRIDHW'];
$itemName = $_POST['itemNameHWRem'];

	$sqlGetHWCode = "SELECT `descripcode`, `totalprice` FROM `offerproperties` WHERE `offproId` = $rowIDHW";
	$queryGetHWCode = mysqli_query($link,$sqlGetHWCode)or die("ERROR :01-ANJ_GCN_S");
	$resGetHWCode = mysqli_fetch_assoc($queryGetHWCode);
	$hwTotalPrice =$resGetHWCode['totalprice']; 
	
	$sqlGetHWName = "SELECT `descriptionname` FROM `stockitems` WHERE `description` = 
	$resGetHWCode[descripcode]";
	$queryGetHWName = mysqli_query($link,$sqlGetHWName)or die("ERROR :01-ANJ_GCN_S");
	$resGetHWName = mysqli_fetch_assoc($queryGetHWName);
	
	$hwName = $resGetHWName['descriptionname'];
	
	$sqlGetJobNum = "SELECT `localref`, `offerValue` FROM `job` WHERE `jobId` = $jobRowId";
	$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
	$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);

$offerVal = $resGetJobNum['offerValue'];
$jobLocalRef = $resGetJobNum['localref'];

$offerVal = ($offerVal - $hwTotalPrice);

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	
	$sqlRemoveHW = "DELETE FROM `offerproperties` WHERE `offproId` = $rowIDHW ";
	mysqli_query($link,$sqlRemoveHW)or die("ERROR :03-ANJ_GCN_S");
	
	$action="Remove HW: $hwName from Item : $itemName for Job Number : $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;

?>
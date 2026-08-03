<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['RJROIFDoor'];
$rowIDItem = $_POST['TRIDDoor'];

	$sqlGetItemRef = "SELECT `doortype`, `totalprice` FROM `autodoorsoffer` WHERE `id` = $rowIDItem ";
	$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");
	$resGetItemRef = mysqli_fetch_assoc($queryGetItemRef);
	
	$itemTotalPrice = $resGetItemRef['totalprice'];
	
	$sqlGetJobNum = "SELECT `localref`, `offerValue` FROM `job` WHERE `jobId` = $jobRowId";
	$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
	$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);

   
$offerVal = $resGetJobNum['offerValue'];
$jobLocalRef = $resGetJobNum['localref'];


$offerVal = ($offerVal - $itemTotalPrice );

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	$DoorType = $resGetItemRef['doortype'];
	
	$sqlRemoveItem = "DELETE FROM `autodoorsoffer` WHERE `id` = $rowIDItem
	LIMIT 1";
	mysqli_query($link,$sqlRemoveItem)or die("ERROR :02-ANJ_GCN_S");
	

	
	$action="Remove Door Toe : $DoorType and All hardware From Job Number : $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;

?>
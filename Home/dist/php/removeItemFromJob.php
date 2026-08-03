<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
// echo "test ->";
$jobRowId = $_POST['RJROIFItem'];
$rowIDItem = $_POST['TRIDItem'];

	$sqlGetItemRef = "SELECT `itemRef`, `itemname`, `totalprice` FROM `itemoffer` WHERE `id` = $rowIDItem";
	$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");
	$resGetItemRef = mysqli_fetch_assoc($queryGetItemRef);
	
 	$itemTotalPrice = $resGetItemRef['totalprice'];
	
	$sqlGetHWVal = "SELECT SUM(`totalprice`) AS TotalHW FROM `offerproperties` WHERE `ioidref` = $rowIDItem";
	$queryGetHWVal = mysqli_query($link,$sqlGetHWVal)or die("ERROR :04-ANJ_GCN_S");
	$resGetHWVal = mysqli_fetch_assoc($queryGetHWVal);
	
	$totalHWVal = $resGetHWVal['TotalHW'];
	$itemTotalPrice = ($itemTotalPrice + $totalHWVal);
	
	$sqlGetJobNum = "SELECT `localref`, `offerValue` FROM `job` WHERE `jobId` = $jobRowId";
	$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
	$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);

$offerVal = $resGetJobNum['offerValue'];
$jobLocalRef = $resGetJobNum['localref'];

$offerVal = ($offerVal - $itemTotalPrice);

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	$itemRef = $resGetItemRef['itemRef'];
	$itemName = $resGetItemRef['itemname'];
	
	$sqlRemoveItem = "DELETE FROM `itemoffer` WHERE `id` = $rowIDItem";
	mysqli_query($link,$sqlRemoveItem)or die("ERROR :02-ANJ_GCN_S");
	
	$sqlRemoveItemHW = "DELETE FROM `offerproperties` WHERE `ioidref` = $rowIDItem";
	mysqli_query($link,$sqlRemoveItemHW)or die("ERROR :03-ANJ_GCN_S");
	
	$action="Remove Item : $itemName and all assigned HW From Job Number : $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;

?>
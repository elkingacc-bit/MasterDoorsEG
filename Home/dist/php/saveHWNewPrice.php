<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$hwRowId = $_POST['hwRowIdEdit'];
$jobRowId = $_POST['hwJobRowId'];
$hwCost = $_POST['itemCostEdit'];
$hwOtherCost = $_POST['overCostEdit'];
$hwPrice = $_POST['hwPriceEdit'];
$hwOverhead = $_POST['overheadEdit'];
$itemName = $_POST['hwLinkedItem'];

	$sqlGetHWCode = "SELECT `descripcode`, `totalprice`, `descripquantity`, `ioidref` FROM `offerproperties` 
	WHERE `offproId` = $hwRowId";
	$queryGetHWCode = mysqli_query($link,$sqlGetHWCode)or die("ERROR :01-ANJ_GCN_S");
	$resGetHWCode = mysqli_fetch_assoc($queryGetHWCode);
	$hwOldTotalPrice = $resGetHWCode['totalprice']; 
	$hwQty = $resGetHWCode['descripquantity'];
	$ItemRID = $resGetHWCode['ioidref'];
	
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

$offerVal = ($offerVal - $hwOldTotalPrice);

$newTotalPrice = ($hwPrice * $hwQty);

$newOfferVal = ($newTotalPrice + $offerVal);

	$sqlUpdateHW = "UPDATE  `offerproperties` SET `unitPrice` = '$hwPrice', `unitCost` = '$hwCost', 
	`othercost` = '$hwOtherCost', `overhead` = '$hwOverhead', `totalprice` = '$newTotalPrice' 
	WHERE `offproId` = $hwRowId ";
	mysqli_query($link,$sqlUpdateHW)or die("ERROR :03-ANJ_GCN_S");

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$newOfferVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	
	$action="Edit Price for HW: $hwName from Item : $itemName for Job Number : $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;

?>
<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['RJROIF'];
$rowIDHW = $_POST['TRIDHW'];
$itemName = $_POST['itemNameHWRem'];
$itemDoorRowId = $_POST['itemRowIdEdit'];
$qty = $_POST['NewQty'];

//echo "Test -> ".$qty;

	$sqlGetHWCode = "SELECT `descripcode`, `unitPrice`,`totalprice` FROM `offerproperties` 
	WHERE `offproId` = $rowIDHW";
	$queryGetHWCode = mysqli_query($link,$sqlGetHWCode)or die("ERROR :01-ANJ_GCN_S");
	$resGetHWCode = mysqli_fetch_assoc($queryGetHWCode);
	$hwOldTotalPrice =$resGetHWCode['totalprice']; 
	$hwUnitPrice =$resGetHWCode['unitPrice']; 
	
	$sqlGetHWName = "SELECT `descriptionname` FROM `stockitems` WHERE `description` = 
	$resGetHWCode[descripcode]";
	$queryGetHWName = mysqli_query($link,$sqlGetHWName)or die("ERROR :01-ANJ_GCN_S");
	$resGetHWName = mysqli_fetch_assoc($queryGetHWName);
	
	$hwName = $resGetHWName['descriptionname'];
	
	$sqlGetDoorQTY = "SELECT `itemqty` FROM `itemoffer` WHERE `id` = $itemDoorRowId";
	$queryGetDoorQTY = mysqli_query($link,$sqlGetDoorQTY)or die("ERROR :01-ANJ_GCN_S");
	$resGetDoorQTY = mysqli_fetch_assoc($queryGetDoorQTY);
	 
	$doorQTY = $resGetDoorQTY['itemqty'];
	
	$qty = ($qty * $doorQTY);
	
	$newTotalPrice = round($qty * $hwUnitPrice);
	
	$sqlGetJobNum = "SELECT `localref`, `offerValue`, `projectName` FROM `job` WHERE `jobId` = $jobRowId";
	$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
	$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);

$offerVal = $resGetJobNum['offerValue'];
$project = $resGetJobNum['projectName'];
$jobLocalRef = $resGetJobNum['localref'];

$offerVal = ($offerVal - $hwOldTotalPrice);

$offerVal = ($offerVal + $newTotalPrice);
 
 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	
	$sqlUpdateHW = "UPDATE  `offerproperties` SET `descripquantity` = $qty, `totalprice` = '$newTotalPrice'
	 WHERE `offproId` = $rowIDHW ";
	mysqli_query($link,$sqlUpdateHW)or die("ERROR :03-ANJ_GCN_S");
	
	$action="Edit HW: $hwName from Item : $itemName for Job Number : $jobLocalRef Project: $project change 
	QTY to ($qty)";
	
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;

?>
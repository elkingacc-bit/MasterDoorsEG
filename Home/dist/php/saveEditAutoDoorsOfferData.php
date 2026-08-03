<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['shRowId2'];
$doorRowId = $_POST['doorRID'];
$CustCode = $_POST['shCustCode2'];
$CustName = $_POST['shCustname2'];
$itemType = $_POST['shType2'];
$DoorSpecs = nl2br($_POST['shDSpecs2']);
$DoorSpecs = trim($DoorSpecs);
$MotorSpecs = nl2br($_POST['shMSpecs2']);
$MotorSpecs = trim($MotorSpecs);
$doorPrice = $_POST['shPrice2'];
$QTY = $_POST['shQTY2'];
$TotalPrice = $_POST['shTPrice2'];

$sqlCheckData = "SELECT `id` FROM `autodoorsoffer` WHERE `doortype` = '$itemType' AND `jobid` = $jobRowId
AND `id` != $doorRowId";
$queryCheckData = mysqli_query($link,$sqlCheckData)or die("ERROR :01-ANJ_GCN_S");

//if(mysqli_num_rows($queryCheckData) > 0)
//{
//	echo 0;
//}
//else
//{	

$sqlGetJobNum = "SELECT `localref`, `offerValue` FROM `job` WHERE `jobId` = $jobRowId";
$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);

$offerVal = $resGetJobNum['offerValue'];
$jobLocalRef = $resGetJobNum['localref'];

$sqlGetItemVal = "SELECT `totalprice` FROM `autodoorsoffer` WHERE `id` = $doorRowId";
	$queryGetItemVal = mysqli_query($link,$sqlGetItemVal)or die("ERROR :02-ANJ_GCN_S");
	$resGetItemVal = mysqli_fetch_assoc($queryGetItemVal);
	$itemTotalPriceOld = $resGetItemVal['totalprice'];

$offerVal = ($offerVal - $itemTotalPriceOld);

$offerVal = ($offerVal + $TotalPrice);

$sqlAddItemData = "UPDATE  `autodoorsoffer` SET `doortype` = '$itemType', `doorspecs` = '$DoorSpecs', 
	`motorspecs` = '$MotorSpecs', `doorprice` = '$doorPrice', `doorqty` =  $QTY, `totalprice` = '$TotalPrice'
	WHERE `id` = $doorRowId";
	mysqli_query($link,$sqlAddItemData)or die("ERROR :03-ANJ_GCN_S");

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	$action="Edit Offer Door: $itemType | Customer - $CustName Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
//}


?>
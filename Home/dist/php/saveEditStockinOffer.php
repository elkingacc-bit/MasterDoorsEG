<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
//echo "test--> ";
$jobRowId = $_POST['HWJobId'];
$tableRowId = $_POST['TRowId'];
$CustCode = $_POST['HWCustCode'];
$CustName = $_POST['HWCustName'];
$descripName = $_POST['HWname'];
$PartNum = $_POST['HWPartNo'];
$descripCode = $_POST['HWCode'];
$descripQTY = $_POST['HWQTY'];
$descripPrice = $_POST['HWPrice'];
$dTotalPrice = $_POST['HWItemTPrice'];

$sqlCheckData = "SELECT `id` FROM `stockoffers` WHERE `descripcode` = $descripCode AND `jobref` = $jobRowId
AND `id` != $tableRowId";
$queryCheckData = mysqli_query($link,$sqlCheckData)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryCheckData) > 0)
{
	echo 0;
}
else
{	

$sqlGetJobNum = "SELECT `localref`, `offerValue` FROM `job` WHERE `jobId` = $jobRowId";
$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);

$offerVal = $resGetJobNum['offerValue'];
$jobLocalRef = $resGetJobNum['localref'];

$sqlGetItemVal = "SELECT `totalprice` FROM `stockoffers` WHERE `id` = $tableRowId";
	$queryGetItemVal = mysqli_query($link,$sqlGetItemVal)or die("ERROR :02-ANJ_GCN_S");
	$resGetItemVal = mysqli_fetch_assoc($queryGetItemVal);
	$itemTotalPriceOld = $resGetItemVal['totalprice'];

$offerVal = ($offerVal - $itemTotalPriceOld);

$offerVal = ($offerVal + $dTotalPrice);

$sqlAddItemData = "UPDATE  `stockoffers` SET `descripcode` = $descripCode, `descripqty` = $descripQTY, 
	`descripprice` = '$descripPrice', `totalprice` = '$dTotalPrice'	WHERE `id` = $tableRowId";
	mysqli_query($link,$sqlAddItemData)or die("ERROR :03-ANJ_GCN_S");

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	$action="Edit Stock Offer Item: $descripName | Customer - $CustName Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
}


?>
<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
//echo "test - > ";
$jobRowId = $_POST['HWJobId'];
$custCode = $_POST['HWCustCode'];
$custName = $_POST['HWCustName'];
$partNum = $_POST['HWPartNo'];
$descCode = $_POST['HWCode'];
$descName = $_POST['HWname'];
$QTY = $_POST['HWQTY'];
$hwPrice = $_POST['HWPrice'];
$totalPrice = $_POST['HWItemTPrice'];


$sqlCheckData = "SELECT `id` FROM `stockoffers` WHERE `descripcode` = $descCode 
AND `jobref` = $jobRowId ";
$queryCheckData = mysqli_query($link,$sqlCheckData)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryCheckData) > 0)
{
	echo 0;
}
else
{
	
	
	$sqlAddItemData = "INSERT INTO `stockoffers`(`descripcode`, `descripqty`, `descripprice`, `totalprice`,
	`jobref`, `ref`) VALUES ($descCode, $QTY, '$hwPrice', '$totalPrice', $jobRowId,  0)";
	mysqli_query($link,$sqlAddItemData)or die("ERROR :02-ANJ_GCN_S");

$sqlGetJobNum = "SELECT `localref`, `offerValue` FROM `job` WHERE `jobId` = $jobRowId";
$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);

$offerVal = $resGetJobNum['offerValue'];
$jobLocalRef = $resGetJobNum['localref'];

$offerVal = ($offerVal + $totalPrice);

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	$action="Add New Item($partNum | $descName) From Stock in Offer For Customer: $custName | 
	Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
}


?>
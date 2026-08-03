<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$TypeRowId = $_POST['TypeRID'];
$jobRowId = $_POST['shRowId'];
$CustCode = $_POST['shCustCode'];
$CustName = $_POST['shCustname'];
$itemType = $_POST['shType'];
//$height = $_POST['THeight'];
//$wedth = $_POST['TWedth'];;
//$depth = $_POST['TDepth'];
$doorPrice = $_POST['shPrice'];
$QTY = $_POST['shQTY'];
$TotalPrice = $_POST['shTPrice'];
$sqlCheckData = "SELECT `id` FROM `maintoffers` WHERE `type` = '$itemType' AND `jobid` = $jobRowId
AND `id` != $TypeRowId";
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

$sqlGetItemVal = "SELECT `totalprice` FROM `maintoffers` WHERE `id` = $TypeRowId";
	$queryGetItemVal = mysqli_query($link,$sqlGetItemVal)or die("ERROR :02-ANJ_GCN_S");
	$resGetItemVal = mysqli_fetch_assoc($queryGetItemVal);
	$itemTotalPriceOld = $resGetItemVal['totalprice'];

$offerVal = ($offerVal - $itemTotalPriceOld);

$offerVal = ($offerVal + $TotalPrice);

$sqlAddItemData = "UPDATE  `maintoffers` SET `type` = '$itemType', `price` = '$doorPrice', 
`typeqty` =  $QTY, `TotalPrice` = '$TotalPrice'	WHERE `id` = $TypeRowId";
	mysqli_query($link,$sqlAddItemData)or die("ERROR :03-ANJ_GCN_S");

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	$action="Edit Offer Type: $itemType | Customer - $CustName Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
}

?>
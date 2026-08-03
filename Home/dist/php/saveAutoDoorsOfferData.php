<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['shRowId'];
$CustCode = $_POST['shCustCode'];
$CustName = $_POST['shCustname'];
$itemType = $_POST['shType'];
$DoorSpecs = nl2br($_POST['shDSpecs']);
$DoorSpecs = trim($DoorSpecs);
$MotorSpecs = nl2br($_POST['shMSpecs']);
$MotorSpecs = trim($MotorSpecs);
$doorPrice = $_POST['shPrice'];
$QTY = $_POST['shQTY'];
$TotalPrice = $_POST['shTPrice'];

$sqlCheckData = "SELECT `id` FROM `autodoorsoffer` WHERE `doortype` = '$itemType' AND `jobid` = $jobRowId";
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

$offerVal = ($offerVal + $TotalPrice);

$sqlAddItemData = "INSERT INTO  `autodoorsoffer`(`doortype`, `doorspecs`, `motorspecs`, `doorprice`,
	 `doorqty`, `totalprice`, `jobid`) VALUES ('$itemType' ,'$DoorSpecs', '$MotorSpecs', '$doorPrice', 
	 $QTY, '$TotalPrice', $jobRowId)";
	mysqli_query($link,$sqlAddItemData)or die("ERROR :03-ANJ_GCN_S");

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	$action="Add New Automatic Door $itemType For Customer - $CustName Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
//}


?>
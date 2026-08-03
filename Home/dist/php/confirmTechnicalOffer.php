<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['jRIDTConf'];
	
	$sqlGetJobNum = "SELECT `localref` FROM `job` WHERE `jobId` = $jobRowId";
	$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :01-ANJ_GCN_S");
	$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);
 $jobLocalRef = $resGetJobNum['localref'];

 $sqlUpdateOfferStatus = "UPDATE `job` SET `jobref` = 1 , `offerStatus` = 'Ready' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :02-ANJ_GCN_S");
 
 $sqlUpdateOfferProp = "UPDATE `offerproperties` SET `jobproref` = 1 WHERE `jobidref` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferProp)or die("ERROR :03-ANJ_GCN_S");
 
 $sqlUpdateOfferItems = "UPDATE `itemoffer` SET `ref` = 1 WHERE `jobref` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferItems)or die("ERROR :04-ANJ_GCN_S");
	
	$action="Confrim Technical Offer Data for Job Number : $jobLocalRef";

$logRef=5;	
include_once("aduLog.php");
	
	echo 1;

?>
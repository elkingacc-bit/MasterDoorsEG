<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 //echo "test --> ";
 $JobRowId = $_POST['AttJobRowId'];
 $attName = $_POST['AttOfferName'];
 
 	$sqlGetAllNewJob="SELECT `customer`, `localref` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$custName = $resGetCustomer['customername'];
	$jobLocalRef = $resGetAllNewJob['localref'];
 
 	$sqlCheckData="SELECT `id` FROM `offerpolicy` WHERE `jobRowId` = $JobRowId";
	$queryCheckData=mysqli_query($link,$sqlCheckData)or die("ERROR :03-AU_AU_S");
	if(mysqli_num_rows($queryCheckData) > 0)
	{
		$sqlAttName = "UPDATE `offerpolicy` SET `attdName` = '$attName' WHERE `jobRowId` = $JobRowId";
	}
	else
	{
		$sqlAttName = "INSERT INTO `offerpolicy` (`attdName`, `custcode`, `jobRowId`) VALUES 
		('$attName', $resGetAllNewJob[customer],$JobRowId)";
	}
	mysqli_query($link,$sqlAttName)or die("ERROR :04-AU_AU_S");
	
	
	$action="Add Attintion Name: $attName for Customer: $custName Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
	exit();
	
 }
?>
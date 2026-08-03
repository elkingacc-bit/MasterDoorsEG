<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 //echo "test --> ";
 $JobRowId = $_POST['payJobRowId'];
 $downpayment = $_POST['PayDown'];
 $downpayment = round(($downpayment / 100), 2);
 $deliverpayment = $_POST['payRecive'];
 $deliverpayment = round(($deliverpayment / 100), 2);
 $finishpayment = $_POST['payFinish'];
 $finishpayment = round(($finishpayment / 100), 2);
 $VATStatus = $_POST['TAXSat'];
 
 $PayNote = nl2br($_POST['paymentNotes']);
 $PayNote = trim($PayNote);
 
 	$sqlGetAllNewJob="SELECT `customer`, `localref` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$custName = $resGetCustomer['customername'];
	$jobLocalRef = $resGetAllNewJob['localref'];
	
 	
	$sqlAttName = "UPDATE `offerpolicy` SET `downpayment` = '$downpayment', `deliverypayment`= 
	'$deliverpayment', `finishpayment` = '$finishpayment', `paynote` = '$PayNote' 
	WHERE `jobRowId` = $JobRowId";
	mysqli_query($link,$sqlAttName)or die("ERROR :04-AU_AU_S");
	
	$sqlUpdateJob = "UPDATE `job` SET `vatstatus` = $VATStatus WHERE `jobId` = $JobRowId";
	mysqli_query($link,$sqlUpdateJob)or die("ERROR :05-AU_AU_S");
	
	
	$action="Add Payment trems For Customer: $custName Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
	
 }
?>
<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 //echo "test --> ";
 $JobRowId = $_POST['ValidJobRowId'];
 $vDate = $_POST['validationdate'];
 $vNote =  nl2br($_POST['validationNotes']);
 $vNote = trim($vNote);
 
 	$sqlGetAllNewJob="SELECT `customer`, `localref` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$custName = $resGetCustomer['customername'];
	$jobLocalRef = $resGetAllNewJob['localref'];
 
 	
	$sqlAttName = "UPDATE `offerpolicy` SET `validate` = '$vNote', `validitydate`= '$vDate' 
	WHERE `jobRowId` = $JobRowId";
	mysqli_query($link,$sqlAttName)or die("ERROR :04-AU_AU_S");
	
	
	$action="Add Validation Date : $vDate for Customer: $custName Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
	
 }
?>
<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$orderRowId = $_POST['SuppORID'];
$orderNewNote = $_POST['NewUpdatedNote'];

$sqlGetAllNewSuppOrder="SELECT  `SuppCode`, `OrderNumber`, `orderNotes`, `custPOId` FROM `supplierorder` 
	 WHERE `SOId` = $orderRowId ";
	$queryGetAllSuppOrder=mysqli_query($link,$sqlGetAllNewSuppOrder)or die("ERROR :01-AU_AU_S");
	$resGetAllSuppOrder= mysqli_fetch_assoc($queryGetAllSuppOrder);
	
	$oldNote = $resGetAllSuppOrder['orderNotes'];
	
	$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
	$resGetAllSuppOrder[SuppCode]";
	$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :03-AU_AU_S");
	$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);
	
	$sqlGetCustPO="SELECT `custCode`, `PoNum`, `orderType`, `jobidref` FROM `customerpo` 
	WHERE `poId` = $resGetAllSuppOrder[custPOId]";
	$queryGetCustPO=mysqli_query($link,$sqlGetCustPO)or die("ERROR :04-AU_AU_S");
	$resGetCustPO= mysqli_fetch_assoc($queryGetCustPO);
	
	$sqlGetProject="SELECT `projectName` FROM `job` WHERE `jobId` = $resGetCustPO[jobidref]";
	$queryGetProject=mysqli_query($link,$sqlGetProject)or die("ERROR :05_1-AU_AU_S");
	$resGetProject= mysqli_fetch_assoc($queryGetProject);
	
	$project = $resGetProject['projectName'];
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetCustPO[custCode]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :05-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	$oldNote = strip_tags($oldNote);
	$AllNotes = $oldNote." "."<br/>".$orderNewNote;
		//nl2br()
	$sqlUpdateSONote = "UPDATE `supplierorder` SET `orderNotes` = '$AllNotes' WHERE `SOId` = $orderRowId";
	mysqli_query($link,$sqlUpdateSONote)or die("ERROR :06-AU_AU_S");
	
	$action="Update Order Note From ($oldNote) to ($orderNewNote) for Order No:$resGetAllSuppOrder[orderNotes]
	Project: $project";
	$logRef=9;	
	include_once("aduLog.php");
	
	echo 1;
	exit();
?>	
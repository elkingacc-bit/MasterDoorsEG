<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 
 $SuppOrderRID = $_POST['SuppORID'];
 $SuppName = $_POST['Suppname'];
 $SuppCode = $_POST['SupplierCode'];
 $Satrt = $_POST['StartDate'];
 $End = $_POST['EndDate'];
  
$sqlGetOrderData="SELECT `SuppCode`,`date`, `deliveryDate`, `OrderNumber` FROM `supplierorder` 
WHERE `SOId` = $SuppOrderRID";
$queryGetOrderData=mysqli_query($link,$sqlGetOrderData)or die("ERROR :01-AU_AU_S");
$resGetOrderData= mysqli_fetch_assoc($queryGetOrderData);

$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $resGetOrderData[SuppCode]";
$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :02-AU_AU_S");
$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);


$oldSupp = $resGetSupplier['suppliername'];
$oldStart = $resGetOrderData['date'];
$oldEnd = $resGetOrderData['deliveryDate'];


	$sqlUpdateOrder = "UPDATE `supplierorder` SET `SuppCode` = $SuppCode, `date` = '$Satrt', `deliveryDate`
	= '$End' WHERE `SOId` = $SuppOrderRID";
	mysqli_query($link,$sqlUpdateOrder)or die("ERROR :05-AU_AU_S");
	
	
$action="Edit Order Basic Data For Supplier Order No: $resGetOrderData[OrderNumber] From ($oldSupp | $oldStart
 | $oldEnd) To ($SuppName | $Satrt | $End)";
$logRef=9;	
include_once("aduLog.php");


	echo 1;
	exit();	 
 ?>
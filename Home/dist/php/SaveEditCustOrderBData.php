<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
  
 $custOrderRID = $_POST['CustORID'];
 $JobRID = $_POST['JobRIDOrder'];
 $CustName = $_POST['Custname'];
 $CustCode = $_POST['CustCode'];
 $Sales = $_POST['Salesname'];
 $SalesCode = $_POST['SalesCode'];
 $Project = $_POST['newProject'];
 $Tax = $_POST['Taxs'];
 
 $sqlGetOrderData="SELECT `custCode`,`orderNotes`, `jobidref`, `poVal`, `POVat` FROM `customerpo` 
 WHERE `poId` = $custOrderRID";
$queryGetOrderData=mysqli_query($link,$sqlGetOrderData)or die("ERROR :01-AU_AU_S");
$resGetOrderData= mysqli_fetch_assoc($queryGetOrderData);

$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetOrderData[custCode]";
$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);

$sqlGetJobData="SELECT `projectName`,`salesman`,`vatstatus` FROM `job` WHERE `jobId` = $JobRID";
$queryGetJobData=mysqli_query($link,$sqlGetJobData)or die("ERROR :03-AU_AU_S");
$resGetJobData= mysqli_fetch_assoc($queryGetJobData);

$sqlGetSales="SELECT `username` FROM `users` WHERE `codeid` = $resGetJobData[salesman]";
$queryGetSales=mysqli_query($link,$sqlGetSales)or die("ERROR :04-AU_AU_S");
$resGetSales= mysqli_fetch_assoc($queryGetSales);


$oldCust = $resGetCustomer['customername'];
$oldPrjt = $resGetJobData['projectName'];
$oldTax = $resGetJobData['vatstatus'];

if($oldTax == 0)
{
	$OldVat="Not VAT";
	
}
else
{
	$OldVat="VAT";
}

if($Tax == 0)
{
	$vat="Not VAT";
	$sqlUpatePO = "UPDATE `customerpo` SET `POVat` = '0' WHERE `poId` = $custOrderRID";
	mysqli_query($link,$sqlUpatePO)or die("ERROR :06-AU_AU_S");
}
else
{
	$vat="VAT";
	$vatAmount = round(($resGetOrderData['poVal'] * .14));
	
	$sqlUpatePO = "UPDATE `customerpo` SET `POVat` = '$vatAmount' WHERE `poId` = $custOrderRID";
	mysqli_query($link,$sqlUpatePO)or die("ERROR :06-AU_AU_S");
}

$oldSales = $resGetSales['username'];


	$sqlUpdateOrder = "UPDATE `customerpo` SET `custCode` = $CustCode WHERE `poId` = $custOrderRID";
	mysqli_query($link,$sqlUpdateOrder)or die("ERROR :05-AU_AU_S");
	
	
	$sqlUpdateJob = "UPDATE `job` SET `projectName` = '$Project', `salesman` = $SalesCode, vatstatus
	= $Tax WHERE `jobId` = $JobRID";
	mysqli_query($link,$sqlUpdateJob)or die("ERROR :06-AU_AU_S");
	
$action="Edit Order Basic Data Form ($oldPrjt | $oldCust | $oldSales | $OldVat) To ($Project | $CustName | $Sales | $vat)";
$logRef=5;	
include_once("aduLog.php");


	echo 1;
	exit();	 
 ?>
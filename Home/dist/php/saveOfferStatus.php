<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
if(!empty($_SESSION['username']))
{
 //echo "test --> ";
 $JobRowId = $_POST['JobRowId'];
 $Refeance = $_POST['sendRef'];
 $OfferInput = $_POST['status'];
 $NewRef = $_POST['NewRef'];
 
 if($Refeance == 1)
 {
	 $offerStauts = "Won";
	 $tableRef = 3;
	 if($NewRef == 1)
	 {
		$CurntPORID = $_POST['curntPORIdNo'];
		 
		$sqlGetJobData="SELECT `customer`, `localref`, `offerValue`, `description`,`jobtype`, `salesman`,
	`vatstatus`	FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetJobData=mysqli_query($link,$sqlGetJobData)or die("ERROR :01-AU_AU_S");
	$resGetJobData= mysqli_fetch_assoc($queryGetJobData);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetJobData[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetJobPolicy="SELECT `deliverydate`, `downpayment`, `deliverypayment`, `finishpayment`	
	FROM `offerpolicy` WHERE `jobRowId` = $JobRowId";
	$queryGetJobPolicy=mysqli_query($link,$sqlGetJobPolicy)or die("ERROR :02-AU_AU_S");
	$resGetJobPolicy= mysqli_fetch_assoc($queryGetJobPolicy);
		
	$custName = $resGetCustomer['customername'];
	
$custCode = $resGetJobData['customer'];	 
$OfferNumber = $resGetJobData['localref'];
$OfferVal = $resGetJobData['offerValue'];
$OfferNote = $resGetJobData['description'];	 
$OfferType = $resGetJobData['jobtype'];	
$salesCode = $resGetJobData['salesman'];
$VAT = $resGetJobData['vatstatus'];	 
$deliveryDate = $resGetJobPolicy['deliverydate'];
$downpayment = $resGetJobPolicy['downpayment'];
$deliverypayment = $resGetJobPolicy['deliverypayment'];
$finishpayment = $resGetJobPolicy['finishpayment'];
	
$orderNum = $OfferInput; 

if($VAT == 1)
{
	$VATVal = round(($OfferVal * .14), 2);
}
else
{
	$VATVal = 0;
}

$downPay = round(($OfferVal * $downpayment), 2);
$ReceivingPay = round(($OfferVal * $deliverypayment), 2);
$endPay = round(($OfferVal * $finishpayment), 2);
	 
	
	
	$sqlUpdatePO = "UPDATE `customerpo`SET `poVal` = '$OfferVal' , `POVat` = '$VATVal' ,`dwonpay` = 
	'$downPay' , `receivingpay` = '$ReceivingPay' , `finishpay` = '$endPay', `deleveryDate` = 
	'$deliveryDate',  `salesCode` = $salesCode WHERE `poId` = $CurntPORID";
	mysqli_query($link,$sqlUpdatePO)or die("ERROR :03-AU_AU_S");
	
	$sqlUpdateJobOffer = "UPDATE `job` SET `offerStatus` = '$offerStauts' , `jobref` = $tableRef, 
	`endDate` = NOW() WHERE `jobId` = $JobRowId";
	mysqli_query($link,$sqlUpdateJobOffer)or die("ERROR :04-AU_AU_S");
 	 
$action="Update PO Number $orderNum fom Customer: $custName for offer No: $OfferNumber 
($offerStauts Offer)";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;	
	exit(); 	 
		 
	 }
	 
	 else
	 {
	$sqlGetJobData="SELECT `customer`, `localref`, `offerValue`, `description`,`jobtype`, `salesman`,
	`vatstatus`	FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetJobData=mysqli_query($link,$sqlGetJobData)or die("ERROR :01-AU_AU_S");
	$resGetJobData= mysqli_fetch_assoc($queryGetJobData);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetJobData[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetJobPolicy="SELECT `deliverydate`, `downpayment`, `deliverypayment`, `finishpayment`	
	FROM `offerpolicy` WHERE `jobRowId` = $JobRowId";
	$queryGetJobPolicy=mysqli_query($link,$sqlGetJobPolicy)or die("ERROR :02-AU_AU_S");
	$resGetJobPolicy= mysqli_fetch_assoc($queryGetJobPolicy);
		
	$custName = $resGetCustomer['customername'];
	
$custCode = $resGetJobData['customer'];	 
$OfferNumber = $resGetJobData['localref'];
$OfferVal = $resGetJobData['offerValue'];
$OfferNote = $resGetJobData['description'];	 
$OfferType = $resGetJobData['jobtype'];	
$salesCode = $resGetJobData['salesman'];
$VAT = $resGetJobData['vatstatus'];	 
$deliveryDate = $resGetJobPolicy['deliverydate'];
$downpayment = $resGetJobPolicy['downpayment'];
$deliverypayment = $resGetJobPolicy['deliverypayment'];
$finishpayment = $resGetJobPolicy['finishpayment'];
	
$orderNum = $OfferInput; 

if($VAT == 1)
{
	$VATVal = round(($OfferVal * .14), 2);
}
else
{
	$VATVal = 0;
}

$downPay = round(($OfferVal * $downpayment), 2);
$ReceivingPay = round(($OfferVal * $deliverypayment), 2);
$endPay = round(($OfferVal * $finishpayment), 2);
	 
	
	
	$sqlAddNewPO = "INSERT INTO `customerpo`(`orderType`, `PoNum`, `poDate`, `custCode`, `poVal`, `POVat`, 
	`dwonpay`, `receivingpay`, `finishpay`, `deleveryDate`, `orderNotes`, `salesCode`, `jobidref`) VALUES
	('$OfferType', '$orderNum', NOW(), $custCode, '$OfferVal', '$VATVal', '$downPay', '$ReceivingPay', 
	'$endPay', '$deliveryDate', '$OfferNote', $salesCode, $JobRowId)";
	mysqli_query($link,$sqlAddNewPO)or die("ERROR :03-AU_AU_S");
	
	$sqlUpdateJobOffer = "UPDATE `job` SET `offerStatus` = '$offerStauts' , `jobref` = $tableRef, 
	`endDate` = NOW() WHERE `jobId` = $JobRowId";
	mysqli_query($link,$sqlUpdateJobOffer)or die("ERROR :04-AU_AU_S");
	 
$action="Add New PO from Customer: $custName for offer No: $OfferNumber ($offerStauts Offer)";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;	
	exit(); 
	 }
 }
 else if($Refeance == 2)
 {
	 $offerStauts = "Lost";
	 $tableRef = 4;
	 
	 if($OfferInput == 1)
	 {
		 $raeson = "High Price Offered";
	 }
	 else if($OfferInput == 2)
	 {
		 $raeson = "Customer Not Serious";
	 }
	 else if($OfferInput == 3)
	 {
		 $raeson = "Out Of Scoop";
	 }
	 
	 
	 $sqlGetAllNewJob="SELECT `customer`, `localref` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$custName = $resGetCustomer['customername'];
	$OfferNumber = $resGetAllNewJob['localref'];
	
	$sqlAttName = "UPDATE `job` SET `offerStatus` = '$offerStauts' , `jobref` = $tableRef, `endDate` = NOW() 
	,`reasonref` = $OfferInput WHERE `jobId` = $JobRowId";
	mysqli_query($link,$sqlAttName)or die("ERROR :04-AU_AU_S");
	
	
$action="Update offer status for Customer: $custName offer No: $OfferNumber by ($offerStauts) | Reason: $raeson";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
	exit();
	  
 }
 else if($Refeance == 3)
 {
	 $offerStauts = "Closed";
	 $tableRef = 5;
	 
	 if($OfferInput == 1)
	 {
		 $raeson = "High Price Offered";
	 }
	 else if($OfferInput == 2)
	 {
		 $raeson = "Customer Not Serious";
	 }
	 else if($OfferInput == 3)
	 {
		 $raeson = "Out Of Scoop";
	 }
	 
	 $sqlGetAllNewJob="SELECT `customer`, `localref` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$custName = $resGetCustomer['customername'];
	$OfferNumber = $resGetAllNewJob['localref'];
	
	$sqlAttName = "UPDATE `job` SET `offerStatus` = '$offerStauts' , `jobref` = $tableRef, `endDate` = NOW() 
	,`reasonref` = $OfferInput WHERE `jobId` = $JobRowId";
	mysqli_query($link,$sqlAttName)or die("ERROR :04-AU_AU_S");
	
	
$action="Update offer status for Customer: $custName Offer No: $OfferNumber by ($offerStauts) | Reason: $raeson";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
	exit();
 }	
 else if($Refeance == 4)
 {
	 
	$tableRef = 6;
	$offerStauts = 'Demo';
	$demoEnd= date("Y-m-d", strtotime(" +$OfferInput months")); 
	 
	$sqlGetAllNewJob="SELECT `customer`, `localref` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$custName = $resGetCustomer['customername'];
	$OfferNumber = $resGetAllNewJob['localref'];
	
	$sqlAttName = "UPDATE `job` SET `offerStatus` = '$offerStauts' , `jobref` = $tableRef, 
	`endDate` = '$demoEnd' WHERE `jobId` = $JobRowId";
	mysqli_query($link,$sqlAttName)or die("ERROR :04-AU_AU_S");
	
	
$action="Update offer status for Customer: $custName Offer No: $OfferNumber by ($offerStauts) | Demo End Date:
".date("d/m/Y",strtotime($demoEnd))."";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
	exit();
 }	
	
}
else 
{
	echo 9;
	exit();
}
?>
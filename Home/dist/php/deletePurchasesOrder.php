<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");

 $orderId=(int)$_POST['orderId'];

 if($orderId <= 0){
  echo "Invalid Request";
  exit;
 }

 $sqlOrder="SELECT `purchasesOrderNum`,`jobref`,`poId` FROM `purchasesorder` WHERE `purchasesorderid` = $orderId";
 $queryOrder=mysqli_query($link,$sqlOrder)or die("ERROR_SNSC : 01");
 if(mysqli_num_rows($queryOrder) == 0){
  echo "Order Not Found";
  exit;
 }
 $orderData=mysqli_fetch_assoc($queryOrder);
 $jopId=$orderData['jobref'];
 $poId=$orderData['poId'];

 // Don't allow deleting an offer that's already been converted to a supplier invoice
 $sqlInvoiceCheck="SELECT `suppliersInvoiceId` FROM `supplierInvoice` WHERE `supplierOrderNum` = '$orderData[purchasesOrderNum]'";
 $queryInvoiceCheck=mysqli_query($link,$sqlInvoiceCheck)or die("ERROR_SNSC : 02");
 if(mysqli_num_rows($queryInvoiceCheck) > 0){
  echo "This Offer Has Already Been Invoiced - It Can No Longer Be Deleted";
  exit;
 }

 // Same orderType -> item table resolution as purchasesOrderData.php / editPurchasesOrderData.php
 $sqlGetOrderType="SELECT `orderType` FROM `customerpo` WHERE `poId` = $poId";
 $queryGetOrderType=mysqli_query($link,$sqlGetOrderType)or die("ERROR_SNSC : 03");
 $orderTypeData=mysqli_fetch_assoc($queryGetOrderType);
 $orderType=$orderTypeData['orderType'];
 $itemsTable=null;
 if($orderType == "Automatic"){$itemsTable= "autodoorsoffer";}
 else if($orderType == "Doors" ){$itemsTable= "itemoffer";}
 else if($orderType == "Stock" ){$itemsTable= "stockoffers";}
 else if($orderType == "Maintenance" ){$itemsTable= "maintoffers";}

 // Reset the prices this offer stamped onto the job's items back to the schema default (0)
 // before removing the offer, so a future offer for the same job doesn't inherit a stale price
 if($itemsTable !== null){
  $jobColumn = ($itemsTable=='autodoorsoffer') ? 'jobid' : 'jobref';
  $sqlResetPrices="UPDATE `$itemsTable` SET `purchasesCost` = 0 WHERE `$jobColumn` = $jopId";
  mysqli_query($link,$sqlResetPrices);
 }

 $sqlDeleteOrder="DELETE FROM `purchasesorder` WHERE `purchasesorderid` = $orderId";
 if(mysqli_query($link,$sqlDeleteOrder)){
  $action="Delete Manufactur Offer $orderData[purchasesOrderNum]";
  $logRef=114;
  include_once("aduLog.php");
  echo "Offer Deleted";
 }
 else{
  echo "Failed To Delete Offer";
 }
?>

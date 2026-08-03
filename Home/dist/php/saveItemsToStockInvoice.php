<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $action="Add New Items To Invoice";
 $logRef=114;
 $lastInvoiceId =$_POST['lastId'];
 $itemRowId = $_POST['itemsName'];
 $itemQun = $_POST['itemsCount'];
 $itemUnitPrice = $_POST['unitPrice'];
 $itemTotalPrice = $_POST['itemTotalPrice'];
 $invDate=$_POST['dateInv'];
 // Add Invoice Items
 $sqlAddSupplierInvoiceData="INSERT INTO `supplierInvoiceData`(`supplierInvoiceNumber`,`ItemRowId`,`supplierInvoiceCount`,
 `supplierInvoiceUnitPrice`, `supplierInvoiceTotalItems`)VALUES('$lastInvoiceId','$itemRowId','$itemQun','$itemUnitPrice','$itemTotalPrice')";
 if(mysqli_query($link,$sqlAddSupplierInvoiceData)){
  $getAllItemCode="SELECT `itemsid` FROM `stockitems` WHERE `description` = $itemRowId";
  $queryAllItemCode=mysqli_query($link,$getAllItemCode)or die("ERROR :01-AIC_AIDL_S");
  $resAllItemCode=mysqli_fetch_assoc($queryAllItemCode);
  $itemRowId2=$resAllItemCode['itemsid'];
/*
  // Add Supplier Order 
  $sqlAddSupplierInvoice="INSERT INTO `supporderitems`(`ItemRowId`, `qty`, `price`, `status`, `SOIdRef`, `soType`, `OIRef`, `receivedQTY`, `receiveddate`) VALUES
  ('$itemRowId2','$itemQun','$itemUnitPrice',5,0,'Stock',$itemRowId2,$itemQun,'$invDate')";
  mysqli_query($link,$sqlAddSupplierInvoice);
*/

  include_once("aduLog.php");
 }
?>  
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $action="Add New Items To Invoice";
 $logRef=114;
 $lastInvoiceId =(int)$_POST['lastId'];
 $itemRowId = (int)$_POST['itemsName'];
 $itemQun = (float)$_POST['itemsCount'];
 $itemUnitPrice = (float)$_POST['unitPrice'];
 // Recompute the total server-side instead of trusting the client-supplied value
 $itemTotalPrice = $itemQun * $itemUnitPrice;
 $invDate=mysqli_real_escape_string($link, $_POST['dateInv']);
 // Add Invoice Items
 $sqlAddSupplierInvoiceData="INSERT INTO `supplierInvoiceData`(`supplierInvoiceNumber`,`ItemRowId`,`supplierInvoiceCount`,
 `supplierInvoiceUnitPrice`, `supplierInvoiceTotalItems`)VALUES('$lastInvoiceId','$itemRowId','$itemQun','$itemUnitPrice','$itemTotalPrice')";
 if(mysqli_query($link,$sqlAddSupplierInvoiceData)){
  $getAllItemCode="SELECT `itemsid` FROM `stockitems` WHERE `description` = $itemRowId";
  $queryAllItemCode=mysqli_query($link,$getAllItemCode)or die("ERROR :01-AIC_AIDL_S");
  $resAllItemCode=mysqli_fetch_assoc($queryAllItemCode);
  $itemRowId2=$resAllItemCode['itemsid'];

  include_once("../aduLog.php");
 }
?>  
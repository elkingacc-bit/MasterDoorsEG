<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $logRef=121;
 $rowid=(int)$_POST['rRowId'];
 // DEL From supplier Invoice Data 
 $sqlDellSupplierInvoiceData="DELETE FROM `supplierInvoiceData` WHERE `supplierInvoiceDataId` = $rowid";
mysqli_query($link,$sqlDellSupplierInvoiceData);


 /*
 //Item Data
 $sqlAccountantCodeData="SELECT `supplierInvoiceDataId`, `supplierInvoiceNumber`,`ItemRowId`,`supplierInvoiceCount`,`supplierInvoiceUnitPrice`, `supplierInvoiceTotalItems` 
 FROM `supplierInvoiceData` WHERE `supplierInvoiceDataId` = $rowid ";  
 $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
 $accData=mysqli_fetch_assoc($queryAccountantCodeData);
 $itemsName=$accData['ItemRowId'];
 $getAllItemCode="SELECT `descriptionname` FROM `stockitems` WHERE `description` = $itemsName";
 $queryAllItemCode=mysqli_query($link,$getAllItemCode)or die("ERROR :01-AIC_AIDL_S");
 $resAllItemCode=mysqli_fetch_assoc($queryAllItemCode);
 $items=$resAllItemCode['descriptionname'];
 $qun=$accData['supplierInvoiceCount'];
 $up=$accData['supplierInvoiceUnitPrice'];
 $action="Delete Fromm Supplier Invoice Items $items & Quantaty $qun & Unit Price $up And Not In Wherehouse";
 // DEL From supplier Invoice Data 
 $sqlDellSupplierInvoiceData="DELETE FROM `supplierInvoiceData` WHERE `supplierInvoiceDataId` = $rowid";
 if(mysqli_query($link,$sqlDellSupplierInvoiceData)){
  include_once("../aduLog.php");
  echo $accData['supplierInvoiceNumber'];	
 }
 */
?>
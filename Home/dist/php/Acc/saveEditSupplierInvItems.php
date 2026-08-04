<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $supInvRowId = $_POST['rRowId'];
 $oItems = $_POST['roItems'];
 $oQun = $_POST['roQun'];
 $oPrice = $_POST['roPrice']; 
 $oTotal = $_POST['roTotal'];
 $nItems = $_POST['rnItems'];
 $nQun = $_POST['rnQun'];
 $nPrice = $_POST['rnPrice']; 
 $nTotal = $_POST['rnTotal'];
 $action = "Edit In Suppliers Invoice Items";
 $logRef=120;
 if($nItems != $oItems){
  $action .= "& Items From $oItems To $nItems";
 }
 else if($oQun != $nQun){
  $action .= "& Quantaty From $oQun To $nQun";
 }
 else if($oPrice != $nPrice){
  $action .= "& Amount From $oPrice To $nPrice";
 }
 else if($nTotal != $oTotal){
  $action .= "& Total Changed From $oTotal To $nTotal";
 }
 // Update Invoice Items 
 $sqlUpdateSupplierInvoiceItems="UPDATE `supplierInvoiceData` SET `ItemRowId` = '$nItems',`supplierInvoiceCount` = '$nQun' ,
 `supplierInvoiceUnitPrice` = '$nPrice',`supplierInvoiceTotalItems` = '$nTotal' WHERE `supplierInvoiceDataId` = $supInvRowId";
 mysqli_query($link,$sqlUpdateSupplierInvoiceItems);
 include_once("../aduLog.php");
?>
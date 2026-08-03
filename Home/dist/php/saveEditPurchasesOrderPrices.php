<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");

 $orderId=(int)$_POST['orderId'];
 $rowCount=(int)$_POST['itemsRowCount'];

 // Table name must come from the same fixed set purchasesOrderData.php/saveSupplierOffer.php
 // generate it from - never trust a table name taken directly from user input
 $allowedItemsTables=['autodoorsoffer','itemoffer','stockoffers','maintoffers'];
 $itemsTable=in_array($_POST['offerTableDataName'], $allowedItemsTables, true) ? $_POST['offerTableDataName'] : null;

 if($itemsTable === null || $orderId <= 0 || $rowCount <= 0){
  echo "Invalid Request";
  exit;
 }

 // Confirm this order actually exists and isn't already invoiced before allowing an edit
 $sqlOrder="SELECT `purchasesOrderNum` FROM `purchasesorder` WHERE `purchasesorderid` = $orderId";
 $queryOrder=mysqli_query($link,$sqlOrder)or die("ERROR_SNSC : 01");
 if(mysqli_num_rows($queryOrder) == 0){
  echo "Order Not Found";
  exit;
 }
 $orderData=mysqli_fetch_assoc($queryOrder);
 $sqlInvoiceCheck="SELECT `suppliersInvoiceId` FROM `supplierInvoice` WHERE `supplierOrderNum` = '$orderData[purchasesOrderNum]'";
 $queryInvoiceCheck=mysqli_query($link,$sqlInvoiceCheck)or die("ERROR_SNSC : 02");
 if(mysqli_num_rows($queryInvoiceCheck) > 0){
  echo "This Offer Has Already Been Invoiced - Prices Can No Longer Be Edited";
  exit;
 }

 $newTotal = 0;
 $allSaved = true;
 for ($rowI=1; $rowI <= $rowCount ; $rowI++) {
  $itemRowId = (int)$_POST['itemsRowId'.$rowI];
  $itemUnitPrice = (float)$_POST['unitPrice'.$rowI];
  $stmt = mysqli_prepare($link, "UPDATE `$itemsTable` SET `purchasesCost` = ? WHERE `id` = ?");
  mysqli_stmt_bind_param($stmt, "di", $itemUnitPrice, $itemRowId);
  if(mysqli_stmt_execute($stmt)){
   $sqlQty="SELECT ".($itemsTable=='autodoorsoffer' ? '`doorqty`' : ($itemsTable=='itemoffer' ? '`itemqty`' : '`descripqty`'))." as qty FROM `$itemsTable` WHERE `id` = $itemRowId";
   $queryQty=mysqli_query($link,$sqlQty)or die("ERROR_SNSC : 03");
   $qtyData=mysqli_fetch_assoc($queryQty);
   $newTotal += ($itemUnitPrice * ($qtyData['qty'] ?? 0));
  }
  else{
   $allSaved = false;
  }
  mysqli_stmt_close($stmt);
 }

 if($allSaved){
  $stmtTotal = mysqli_prepare($link, "UPDATE `purchasesorder` SET `totalAmout` = ? WHERE `purchasesorderid` = ?");
  mysqli_stmt_bind_param($stmtTotal, "di", $newTotal, $orderId);
  mysqli_stmt_execute($stmtTotal);
  mysqli_stmt_close($stmtTotal);
  $action="Edit Prices On Manufactur Offer $orderData[purchasesOrderNum]";
  $logRef=114;
  include_once("aduLog.php");
  echo "Prices Updated";
 }
 else{
  echo "Some Item Prices Failed To Save";
 }
?>

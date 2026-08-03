<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $purCode='312100100000';
 $vat14Code='212100100000';
 $dateInvoice=$_POST['invoiceDate'];
 $transactionsYear=date('Y', strtotime($dateInvoice));
 $transactionsMonth=date('m', strtotime($dateInvoice));
 $invoiceNum=$_POST['invoiceNumber'];
 $supplier=$_POST['suplierCode'];
 $orderNum=$_POST['orderNumber'];
 $invType=$_POST['invoiceTypy'];
 $action="Add New Suppliers Invoice $invoiceNum";
 $logRef=114;  

  $financialRef="New Suppliers Invoice";

  
 if($invType == "VAT"){
  $vatValue = .14;
 }
 else if($invType == "Not"){
  $vatValue = 0;
 }
 $SOId = $_POST['SOId'];
 $invDisc = $_POST['invoiceDis'];
 $totalInvoice= 0 ;
 $rowCount = $_POST['dataCount'];
 for ($rowNum=1; $rowNum <= $rowCount ; $rowNum++) { 
  $itemName = $_POST['itemsName'.$rowNum];
  $itemRowId = $_POST['itemRow'.$rowNum];
  $itemQun = $_POST['itemsCount'.$rowNum];
  $itemUnitPrice = $_POST['unitPrice'.$rowNum];
  $itemTotalPrice = $_POST['itemTotalPrice'.$rowNum];
  $totalInvoice+=$itemTotalPrice;
 }
 $invoiceDiscount=(($totalInvoice * $invDisc)/100);
 $netTotalInvoiceAfterDiscount=($totalInvoice - $invoiceDiscount);
 $vatAmount=($netTotalInvoiceAfterDiscount * $vatValue);
 $totalInvoiceAmount=($netTotalInvoiceAfterDiscount+$vatAmount);
 // Cheak Supplier Invoice 
 $sqlSupplierInvoiceCount="SELECT `suppliersInvoiceId` FROM `supplierInvoice` WHERE `suppliersInvoiceNumber` = '$invoiceNum' AND `suppliersInvoiceDate`= '$dateInvoice' 
 AND `supplierCode` = '$supplier'";
 $querySupplierInvoiceCount=mysqli_query($link,$sqlSupplierInvoiceCount)or die("ERROR_SNSC : 01");
 $supplierInvoiceCount=mysqli_num_rows($querySupplierInvoiceCount);
 if($supplierInvoiceCount > 0){
  $result="This Transaction has Already Been Registered";  
 }
 else{
  //Get Financal Transaction Number Per Month
  $sqlFinancialTransaction="SELECT `transactionNumber` FROM `financialTransactions` 
  WHERE `transactionsYear` = $transactionsYear AND `transactionsMonth` = $transactionsMonth ORDER BY `transactionNumber` DESC LIMIT 1";
  $queryFinancialTransaction=mysqli_query($link,$sqlFinancialTransaction)or die("ERROR_SNSC : 01");
  $financialTransactionCount=mysqli_num_rows($queryFinancialTransaction);
  if($financialTransactionCount > 0){
   $financialTransactionNumber=mysqli_fetch_assoc($queryFinancialTransaction);  
   $lastNumber=$financialTransactionNumber['transactionNumber'];
   $nextNumber=($lastNumber + 1);
  }
  else{
   $nextNumber=01;
  }
  // Add Supplier Invoice 
  $sqlAddSupplierInvoice="INSERT INTO `supplierInvoice`(`suppliersInvoiceNumber`,`supplierOrderNum`,`suppliersInvoiceDate`,`supplierCode`,`suppliersInvoiceType`,
  `suppliersInvoiceSupTotal`,`suppliersInvoiceDiscount`,`suppliersInvoiceVat`,`suppliersInvoiceTotal`)VALUES
  ('$invoiceNum','$orderNum','$dateInvoice','$supplier','$invType','$totalInvoice','$invoiceDiscount','$vatAmount','$totalInvoiceAmount')";
  if(mysqli_query($link,$sqlAddSupplierInvoice)){


//Get Last Entry Id
$sqlCashTransactionId="SELECT `suppliersInvoiceId` FROM `supplierInvoice` WHERE `suppliersInvoiceNumber` = '$invoiceNum' AND `suppliersInvoiceDate`= '$dateInvoice' 
  AND `supplierCode` = '$supplier' ORDER BY `suppliersInvoiceId` DESC LIMIT 1";
$queryCashTransactionId=mysqli_query($link,$sqlCashTransactionId)or die("ERROR_SNSC : 01");
$cashTransactionId=mysqli_fetch_assoc($queryCashTransactionId);
$tableName="supplierInvoice";
$tableRow=$cashTransactionId['suppliersInvoiceId'];



   // Add Supplier Financial Transactions creditor
   $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
   `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
   VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','0','$totalInvoiceAmount','Invoice Supplier','$supplier','$financialRef','$tableName',$tableRow)";
   mysqli_query($link,$sqlTransactionDebtor);
   // Add VAT Financial Transactions debtor
   $sqlTransactionDebtor2="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
   `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
   VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','$vatAmount','0','$invoiceNum Vat','$vat14Code','$financialRef','$tableName',$tableRow)";
   mysqli_query($link,$sqlTransactionDebtor2);
   // Add Pur Financial Transactions debtor
   $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
   `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`) 
   VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','$netTotalInvoiceAfterDiscount','0','اثبات  مشتريات','$purCode','$financialRef','$tableName',$tableRow)";
   mysqli_query($link,$sqlTransactionCreditor);
   // Get Invoice Id
   $sqlSupplierInvoiceLast="SELECT `suppliersInvoiceId` FROM `supplierInvoice` WHERE `suppliersInvoiceNumber` = '$invoiceNum' AND `suppliersInvoiceDate`= '$dateInvoice' 
   AND `supplierCode` = '$supplier'";
   $querySupplierInvoiceLast=mysqli_query($link,$sqlSupplierInvoiceLast)or die("ERROR_SNSC : 01");
   $lastSupplierInvoice=mysqli_fetch_assoc($querySupplierInvoiceLast);
   $lastInvoiceId=$lastSupplierInvoice['suppliersInvoiceId'];
   for ($rowI=1; $rowI <= $rowCount ; $rowI++) { 
    $itemName = $_POST['itemsName'.$rowI];
    $itemRowId = $_POST['itemRow'.$rowI];
    $itemQun = $_POST['itemsCount'.$rowI];
    $itemUnitPrice = $_POST['unitPrice'.$rowI];
    $itemTotalPrice = $_POST['itemTotalPrice'.$rowI];
    // Add Invoice Items
    $sqlAddSupplierInvoiceData="INSERT INTO `supplierInvoiceData`(`supplierInvoiceNumber`,`ItemRowId`,`supplierInvoiceCount`,
    `supplierInvoiceUnitPrice`, `supplierInvoiceTotalItems`)VALUES('$lastInvoiceId','$itemRowId','$itemQun','$itemUnitPrice','$itemTotalPrice')";
    if(mysqli_query($link,$sqlAddSupplierInvoiceData)){
     // Change supporderitems Ref
     $sqlSupplierOrderItems="SELECT `OIId` FROM `supporderitems` WHERE `SOIdRef` = $SOId";
     $querySupplierOrderItemsData=mysqli_query($link,$sqlSupplierOrderItems)or die("ERROR_SNSC : 01");
     while($orderItemsData=mysqli_fetch_assoc($querySupplierOrderItemsData)){
      $orderItemId=$orderItemsData['OIId'];
      $sqlUpdateorderitems="UPDATE `supporderitems` SET `status`= 5 WHERE `OIId` = $orderItemId";
      mysqli_query($link,$sqlUpdateorderitems);
     }
    }
   }
   include_once("aduLog.php");
   echo "Data Saved";
  }
 }
?>
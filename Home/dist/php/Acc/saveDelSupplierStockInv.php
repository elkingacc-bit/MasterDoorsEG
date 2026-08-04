<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $rowid=$_POST['rRowId'];
 $logRef=120;
 $sqlDataEdit="SELECT `suppliersInvoiceNumber`,`suppliersInvoiceTotal` FROM `supplierInvoice` WHERE `suppliersInvoiceId` = $rowid";
 $queryDataEdit=mysqli_query($link,$sqlDataEdit);
 $resultDataEdit=mysqli_fetch_assoc($queryDataEdit);
 $delInvoiceNumber=$resultDataEdit['suppliersInvoiceNumber'];
 $delInvoiceAmount=$resultDataEdit['suppliersInvoiceTotal'];
 $action="Delete Invoice Number $delInvoiceNumber & Amount = $delInvoiceAmount FROM Stock Invoice Whithout Data";
 // DEL Supplier Invoice
 $sqlDellSupplierInvoice="DELETE FROM `supplierInvoice` WHERE `suppliersInvoiceId` = $rowid";
 mysqli_query($link,$sqlDellSupplierInvoice);
 // Get Financial Transactions Id
 $sqlFinancialTransactionsId="SELECT `financialTransactionsId` FROM `financialTransactions` WHERE `tableName` = 'supplierInvoice' AND `tableRowId` = $rowid";
 $queryFinancialTransactionsId=mysqli_query($link,$sqlFinancialTransactionsId);
 while($resultFinancialTransactionsId=mysqli_fetch_assoc($queryFinancialTransactionsId)){
  $ftid=$resultFinancialTransactionsId['financialTransactionsId'];
  // DEL FinancialTransactions
  $sqlDellFinancialTransaction="DELETE FROM `financialTransactions` WHERE `financialTransactionsId` = $ftid";
  mysqli_query($link,$sqlDellFinancialTransaction);
 }
  include_once("../aduLog.php");
?>
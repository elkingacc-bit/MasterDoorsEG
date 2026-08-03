<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $recipient=(int)$_SESSION['id'];
 $purCode='312100100000';
 $vat14Code='212100100000';
 $cashCode='116100100000';
 $dateInvoice=mysqli_real_escape_string($link, $_POST['finvDate']);
 $transactionsYear=date('Y', strtotime($dateInvoice));
 $transactionsMonth=date('m', strtotime($dateInvoice));
 $invoiceNum=mysqli_real_escape_string($link, $_POST['finvNum']);
 $supplier=(int)$_POST['finvSuplier'];
 $supTotal=(float)$_POST['finvSupTotal'];
 $vatAmount=(float)$_POST['finvVat'];
 $action="Add New Stock Suppliers Invoice $invoiceNum";
 $logRef=114;
 $financialRef="Withdrawal Stock Suppliers Invoice";
 if($vatAmount > 0){
  $invType = 'VAT';
 }
 else{
  $invType = 'Not VAT';
 }
 $invoiceDiscount=0;
 $totalInvoice=(float)$_POST['finvTotal'];
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
 `suppliersInvoiceSupTotal`,`suppliersInvoiceDiscount`,`suppliersInvoiceVat`,`suppliersInvoiceTotal`,`paiedStuts`)VALUES
 ('$invoiceNum','$invoiceNum','$dateInvoice','$supplier','$invType','$supTotal','$invoiceDiscount','$vatAmount','$totalInvoice',3)";
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
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','0','$totalInvoice','Invoice Supplier','$supplier','$financialRef','$tableName',$tableRow)";
  mysqli_query($link,$sqlTransactionDebtor);
  
  // Add VAT Financial Transactions debtor
  $sqlTransactionDebtor2="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','$vatAmount','0','$invoiceNum Vat','$vat14Code','$financialRef','$tableName',$tableRow)";
  mysqli_query($link,$sqlTransactionDebtor2);
  
  // Add Pur Financial Transactions debtor
  $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','$supTotal','0','اثبات  مشتريات','$purCode','$financialRef','$tableName',$tableRow)";
  mysqli_query($link,$sqlTransactionCreditor);

  // Get Invoice Id
  $sqlSupplierInvoiceLast="SELECT `suppliersInvoiceId` FROM `supplierInvoice` WHERE `suppliersInvoiceNumber` = '$invoiceNum' AND `suppliersInvoiceDate`= '$dateInvoice' 
  AND `supplierCode` = '$supplier'";
  $querySupplierInvoiceLast=mysqli_query($link,$sqlSupplierInvoiceLast)or die("ERROR_SNSC : 01");
  $lastSupplierInvoice=mysqli_fetch_assoc($querySupplierInvoiceLast);
  $lastInvoiceId=$lastSupplierInvoice['suppliersInvoiceId'];
  echo $lastInvoiceId;
 }
 include_once("aduLog.php");
}
?>
<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $invId=(int)$_POST['jopId'];
 $dateInvoice=mysqli_real_escape_string($link, $_POST['fDate']);
 $vat1Code='21210110000';
 $transactionsYear=date('Y', strtotime($dateInvoice));
 $transactionsMonth=date('m', strtotime($dateInvoice));

  $financialRef="Add WHT To Invoice";

  
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
 $sqlSalesTax="SELECT `salesInvoiceNumber`,`jopRef`,`salesInvoiceDate`,`customerCode`,`salesInvoiceSupTotal`,`invoiceCollectAmount` 
 FROM `salesInvoice` WHERE  `salesInvoiceId` = $invId";
 $querySalesTax=mysqli_query($link,$sqlSalesTax)or die("ERROR_SNSC : 02");
 $taxData=mysqli_fetch_assoc($querySalesTax);
 $invoiceNumber=$taxData['salesInvoiceNumber'];
 $action="Add WHT To Invoice $invoiceNumber";
 $logRef=113;
 $jopRef=$taxData['jopRef'];
 $customer=$taxData['customerCode'];
 $sqlOrderType="SELECT `jobtype` FROM `job` WHERE `jobId` = $jopRef ";
 $queryOrderType=mysqli_query($link,$sqlOrderType)or die("ERROR_SNSC : 02");
 $orderData=mysqli_fetch_assoc($queryOrderType);
 if($orderData['jobtype'] == 'Maintenance'){
  $validTax=($taxData['salesInvoiceSupTotal'] * 0.03);
 }
 else{
  $validTax=($taxData['salesInvoiceSupTotal'] * 0.01);    
 }
 $newCollectAmount=($taxData['invoiceCollectAmount']+$validTax);
 //Add New Invoice Coolect Amount
 $sqlJopInvoiceRef="UPDATE `salesInvoice` SET `invoiceCollectAmount` = '$newCollectAmount' WHERE `salesInvoiceId` = $invId";
 if(mysqli_query($link,$sqlJopInvoiceRef)){
  // Add VAT Financial Transactions creditor
  $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
  VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','0','$validTax','$invoiceNumber Invoice WHT','$customer','$financialRef','salesInvoice',$invId)";
  if(mysqli_query($link,$sqlTransactionDebtor)){
   // Add VAT Financial Transactions Debtor
   $sqlTransactionDebtor2="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
   `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES
   ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','$validTax','0','$invoiceNumber WHT','$vat1Code','$financialRef','salesInvoice',$invId)";
   if(mysqli_query($link,$sqlTransactionDebtor2)){
    include_once("aduLog.php");        
    echo "Save Done";      
   }
  }
 }
?>
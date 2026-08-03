<?php
 @session_start();  
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $recipient=$_SESSION['id'];
 $actionType=$_POST['fRef'];
 $dateInvoice=$_POST['fDate'];
 $transactionsYear=date('Y', strtotime($dateInvoice));
 $transactionsMonth=date('m', strtotime($dateInvoice));
 $amount=$_POST['fAmount'];
 $invId=$_POST['fInvNum'];
 $customer=$_POST['fCustomer'];
 
 $logRef=113;
 $cashType= substr($actionType,0,5);
 $cashCode=$actionType;
 $financialRef="Collect Invoice";
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
 $sqlSalesTax="SELECT `salesInvoiceNumber`,`invoiceCollectAmount`,`jopRef` FROM `salesInvoice` WHERE  `salesInvoiceId` = $invId";
 $querySalesTax=mysqli_query($link,$sqlSalesTax)or die("ERROR_SNSC : 02");
 $taxData=mysqli_fetch_assoc($querySalesTax);
 $invoiceNumber=$taxData['salesInvoiceNumber'];
 $newCollectAmount=($taxData['invoiceCollectAmount']+$amount);
 $jopRef=$taxData['jopRef'];
 // Project Name
 $sqlProjectName="SELECT `projectName` FROM `job` WHERE `jobId` = $jopRef";
 $quaryProjectName=mysqli_query($link,$sqlProjectName)or die("ERROR LOA_S:01");
 $projectName=mysqli_fetch_assoc($quaryProjectName);
 $project=$projectName['projectName'];

 $action=" Collect Invoice Number ( $invId ) Project ( $project )";
 // Collect Type
 if($cashType == 11620){
  // Bank
  $cheakNum=$_POST['numCheak']; 
  $dueDate=$_POST['cheakDate'];
  $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`poNum`,`empCode`,`chequNumber`,
  `valideDate`)VALUES ('$dateInvoice','$amount','0','Collect Invoice $invoiceNumber','$cashCode','$customer','$jopRef','$recipient',$cheakNum,'$dueDate')";
 }
 else{
  $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`poNum`,`empCode`)
  VALUES ('$dateInvoice','$amount','0','Collect Invoice $invoiceNumber Project ( $project )','$cashCode','$customer','$jopRef','$recipient')";
 } 
 // Add Collect Invoice To Cash
 $sqlWithdrawalGeneralCash;
 if(mysqli_query($link,$sqlWithdrawalGeneralCash)){
  //Get Last Entry Id
  $sqlCashTransactionId="SELECT `cash_transaction_id` FROM `cash_transaction` ORDER BY `cash_transaction_id` DESC LIMIT 1";
  $queryCashTransactionId=mysqli_query($link,$sqlCashTransactionId)or die("ERROR_SNSC : 01");
  $cashTransactionId=mysqli_fetch_assoc($queryCashTransactionId);
  $tableName="salesInvoice";
  $tableRow=$cashTransactionId['cash_transaction_id'];
  // Update Cash
  $sqlUpdateCashTransaction="UPDATE `cash_transaction` SET `tableName` = '$tableName',`tableRowId`='$invId',`ftId`='$nextNumber' WHERE `cash_transaction_id` = $tableRow";
  mysqli_query($link,$sqlUpdateCashTransaction);  
  // Add VAT Financial Transactions creditor
  $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,`description`
  ,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','0','$amount',
  'Collect Invoice $invoiceNumber Project ( $project )','$customer','$financialRef','$tableName',$tableRow)";
  if(mysqli_query($link,$sqlTransactionDebtor)){
   // Add VAT Financial Transactions Debtor
   $sqlTransactionDebtor2="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
   `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
   VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','$amount','0','Collect Invoice $invoiceNumber Project ($project)','$cashCode',
    '$financialRef','$tableName',$tableRow)";
   if(mysqli_query($link,$sqlTransactionDebtor2)){
    //Add New Invoice Coolect Amount
    $sqlJopInvoiceRef="UPDATE `salesInvoice` SET `invoiceCollectAmount` = '$newCollectAmount' WHERE `salesInvoiceId` = $invId";
    mysqli_query($link,$sqlJopInvoiceRef); 
    include_once("aduLog.php");
    echo "Data Seved";
   }
  }
 }
?>
<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $action="Add New Withdrawal Project Expenses";
 $logRef=111;
 $transactionsDate=$_POST['fDate'];  
 $transactionsYear=date('Y', strtotime($transactionsDate));
 $transactionsMonth=date('m', strtotime($transactionsDate));
 $cashCode='116100100000';
 $accCode=$_POST['fCode'];
 $recipient=$_POST['frecipient'];
 $amount=$_POST['famount'];
 $discrebtion=$_POST['fdiscrebtion'];
 $poNum=$_POST['poNumber'];
 $result = "";

  $financialRef="Project Expenses";


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
 //Cheak Entry
 $sqlCashTransactionCount="SELECT `cash_transaction_id` FROM `cash_transaction` WHERE `transactionDate` = '$transactionsDate' AND `withdrawal`= '$amount' 
 AND `description` = '$discrebtion' AND `account` = '$accCode' AND `empCode` = '$recipient'";
 $queryCashTransactionCount=mysqli_query($link,$sqlCashTransactionCount)or die("ERROR_SNSC : 01");
 $cashTransactionCount=mysqli_num_rows($queryCashTransactionCount);
 if($cashTransactionCount > 0){
  $result="This Transaction has Already Been Registered";  
 }
 else{
  $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`poNum`,`empCode`)
  VALUES ('$transactionsDate','0','$amount','$discrebtion','$cashCode','$accCode','$poNum','$recipient')";
  if(mysqli_query($link,$sqlWithdrawalGeneralCash)){
   //



    //Get Last Entry Id
$sqlCashTransactionId="SELECT `cash_transaction_id` FROM `cash_transaction` ORDER BY `cash_transaction_id` DESC LIMIT 1";
$queryCashTransactionId=mysqli_query($link,$sqlCashTransactionId)or die("ERROR_SNSC : 01");
$cashTransactionId=mysqli_fetch_assoc($queryCashTransactionId);
$tableName="Cash";
$tableRow=$cashTransactionId['cash_transaction_id'];
// Update Cash
$sqlUpdateCashTransaction="UPDATE `cash_transaction` SET `tableName` = '$tableName',`tableRowId`='$tableRow',`ftId`='$nextNumber' WHERE `cash_transaction_id` = $tableRow";
mysqli_query($link,$sqlUpdateCashTransaction);


   $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
   `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
   VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','$amount','0','$discrebtion','$accCode','$financialRef','$tableName',$tableRow)";
   //
   if(mysqli_query($link,$sqlTransactionDebtor)){
    $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
    `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES 
    ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','0','$amount','خزينه النقديه','$cashCode','$financialRef','$tableName',$tableRow)";
    mysqli_query($link,$sqlTransactionCreditor);
    $result = 1;
   }
   $result = 1;
  }
  $result = 1;
  include_once("../aduLog.php");
 }
 echo $result;
?>
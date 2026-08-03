<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");

 $logRef=111;
 $result = "";
 $transactionsDate=mysqli_real_escape_string($link, $_POST['fDate']);
 $transactionsYear=date('Y', strtotime($transactionsDate));
 $transactionsMonth=date('m', strtotime($transactionsDate));
 $discrebtion=mysqli_real_escape_string($link, $_POST['fDes']);
 $recipient=(int)$_SESSION['id'];
 $amount=(float)$_POST['fAmount'];
 $accCode=(int)$_POST['fCode'];
 $recipientType= substr($accCode,0,5);
 $cashCode=(int)$_POST['typeCash'];
 $cashType= substr($cashCode,0,5);

  


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
 if($cashType == 11620){
  // Bank
  $cheakNum=(int)$_POST['numCheak'];
  $dueDate=mysqli_real_escape_string($link, $_POST['cheakDate']);
  $casher="Bank";
  $financialRef="Received Cash In Bank";
  if($recipientType == 11610){
   $sqlWithdrawalGeneralCash2="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`)
   VALUES ('$transactionsDate', '0' , '$amount' , '$discrebtion' , $accCode ,$cashCode , $recipient)";
   mysqli_query($link,$sqlWithdrawalGeneralCash2);
  }
  $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`,`chequNumber`,
  `valideDate`)VALUES ('$transactionsDate', '$amount' , '0' , '$discrebtion' , $cashCode ,$accCode , $recipient,$cheakNum,'$dueDate')";
 }
 else{
  $casher="Cash";
  $financialRef="Received Cash In Casher";
  // Cash
  if($recipientType == 11620){
   $sqlWithdrawalGeneralCash2="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`)
   VALUES ('$transactionsDate', '0' , '$amount' , '$discrebtion' , $accCode ,$cashCode , $recipient)";
   mysqli_query($link,$sqlWithdrawalGeneralCash2);
  }
  $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`)
  VALUES ('$transactionsDate', '$amount' , '0' , '$discrebtion' , $cashCode ,$accCode , $recipient)";
 } 
 //Cheak Entry
 $sqlCashTransactionCount="SELECT `cash_transaction_id` FROM `cash_transaction` WHERE `transactionDate` = '$transactionsDate' AND `income`= '$amount' 
 AND `description` = '$discrebtion' AND `account` = '$accCode' AND `empCode` = '$recipient'";
 $queryCashTransactionCount=mysqli_query($link,$sqlCashTransactionCount)or die("ERROR_SNSC : 01");
 $cashTransactionCount=mysqli_num_rows($queryCashTransactionCount);
 if($cashTransactionCount > 0){
  $result="This Transaction has Already Been Registered";  
 }
 else{
  if(mysqli_query($link,$sqlWithdrawalGeneralCash)){ 
   
//Get Last Entry Id
$sqlCashTransactionId="SELECT `cash_transaction_id` FROM `cash_transaction` ORDER BY `cash_transaction_id` DESC LIMIT 1";
$queryCashTransactionId=mysqli_query($link,$sqlCashTransactionId)or die("ERROR_SNSC : 01");
$cashTransactionId=mysqli_fetch_assoc($queryCashTransactionId);
$tableName="Cash";
$tableRow=$cashTransactionId['cash_transaction_id'];
// Update Cash
$sqlUpdateCashTransaction="UPDATE `cash_transaction` SET `tableName` = '$tableName',`tableRowId`='$tableRow',`ftId`='$nextNumber' WHERE `cash_transaction_id` = $tableRow";
mysqli_query($link,$sqlUpdateCashTransaction);


   //
   $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
   `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES 
   ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','0','$amount','$discrebtion','$accCode','$financialRef','$tableName',$tableRow)";
   //
   if(mysqli_query($link,$sqlTransactionDebtor)){
    $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
    `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES
    ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','$amount','0','$casher Account','$cashCode','$financialRef','$tableName',$tableRow)";
    if(mysqli_query($link,$sqlTransactionCreditor)){
     $result = 1;
     $action="Received $amount In $casher";
     include_once("aduLog.php");
    }
    else{
     $result = "ERROR_SNSC : Failed to record creditor transaction";
    }
   }
   else{
    $result = "ERROR_SNSC : Failed to record debtor transaction";
   }
  }
  else{
   $result = "ERROR_SNSC : Failed to record cash transaction";
  }
 }
 echo $result;
<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $rowCount = $_POST['advType'];
 if($rowCount == 1){
  $advanceType="Employee";
 }
 else if($rowCount == 2){
  $advanceType="Staff";
 }
 $transactionsDate=$_POST['advancMonth'];
 $advancer=$_POST['user'];
 $advanceAmount=$_POST['amount'];
 $insallmentValue=$_POST['instAmount'];
 $transactionsYear=date('Y', strtotime($transactionsDate));
 $transactionsMonth=date('m', strtotime($transactionsDate));
 $logRef=111;
 $action="Add advance To".$advanceType;
 $result = 0;
 $advanceCode = 114101100000;
 $cashCode = 116100100000;
 $discrebtion = "withdrow advance To".$advanceType;
 
 $financialRef="advance";

 //Cheak Balance
 $sqlCasher="SELECT (sum(`income`) - sum(`withdrawal`) ) as cashbalance FROM `cash_transaction`  WHERE `statmentRef` = '$cashCode'";
 $queryCasher=mysqli_query($link,$sqlCasher)or die("ERROR_SNSC : 01");
 $casherBalance=mysqli_fetch_assoc($queryCasher); 
 $avilablBalance=$casherBalance['cashbalance'];
if($avilablBalance > $advanceAmount){






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
 $sqlCashTransactionCount="SELECT `cash_transaction_id` FROM `cash_transaction` WHERE `transactionDate` = '$transactionsDate' AND `withdrawal`= '$advanceAmount' 
 AND `description` = '$discrebtion' AND `account` = '$advanceCode' AND `empCode` = '$advancer'";
 $queryCashTransactionCount=mysqli_query($link,$sqlCashTransactionCount)or die("ERROR_SNSC : 01");
 $cashTransactionCount=mysqli_num_rows($queryCashTransactionCount);
 if($cashTransactionCount > 0){
  $result="This Transaction has Already Been Registered";  
 }
 else{
  //withdrow Advance
  $sqlWithdrawalAdvance="INSERT INTO `advance`(`advanceDate`, `empId`, `recived`, `cashback`,`installment`, `recevedRef`) 
  VALUES ('$transactionsDate','$advancer','$advanceAmount','0','$insallmentValue','$rowCount')";
  if(mysqli_query($link,$sqlWithdrawalAdvance)){
   //withdrow trusty
   $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`)
   VALUES ('$transactionsDate','0','$advanceAmount','$discrebtion',$cashCode,'$advanceCode','$advancer')";
   if(mysqli_query($link,$sqlWithdrawalGeneralCash)){





//Get Last Entry Id
$sqlCashTransactionId="SELECT `cash_transaction_id` FROM `cash_transaction` ORDER BY `cash_transaction_id` DESC LIMIT 1";
$queryCashTransactionId=mysqli_query($link,$sqlCashTransactionId)or die("ERROR_SNSC : 01");
$cashTransactionId=mysqli_fetch_assoc($queryCashTransactionId);
$tableRow=$cashTransactionId['cash_transaction_id'];
//Get Last Custody Entry Id
$sqlCustodyTransactionId="SELECT `advanceId` FROM `advance` ORDER BY `advanceId` DESC LIMIT 1";
$queryCustodyTransactionId=mysqli_query($link,$sqlCustodyTransactionId)or die("ERROR_SNSC : 01");
$custodyTransactionId=mysqli_fetch_assoc($queryCustodyTransactionId);
$custodyRow=$custodyTransactionId['advanceId'];

$tableName="advance";


// Update Cash
$sqlUpdateCashTransaction="UPDATE `cash_transaction` SET `tableName` = '$tableName',`tableRowId`='$custodyRow',`ftId`='$nextNumber' WHERE `cash_transaction_id` = $tableRow";
mysqli_query($link,$sqlUpdateCashTransaction);


	
    $sqlDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,`description`,
    `transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','$advanceAmount','0','$discrebtion','$advanceCode','$financialRef','$tableName',$tableRow)";
    if(mysqli_query($link,$sqlDebtor)){
     $sqlCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,`description`,
     `transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','0','$advanceAmount','$discrebtion','$cashCode','$financialRef','$tableName',$tableRow)";
     if(mysqli_query($link,$sqlCreditor)){
      $result = 1;
     }
    }
   }
  }
 }
 
 include_once("aduLog.php");
 }
 else{
    $result = 2;
 }

 echo $result;


?>
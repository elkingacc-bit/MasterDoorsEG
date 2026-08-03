<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");

 $logRef=111;
 $result = "";
 $transactionsDate=mysqli_real_escape_string($link, $_POST['fDate']);
 $transactionsYear=date('Y', strtotime($transactionsDate));
 $transactionsMonth=date('m', strtotime($transactionsDate));
 $accCode=(int)$_POST['fCode'];
 $recipientType= substr($accCode,0,5);
 $recipient=(int)$_POST['frecipient'];
 $amount=(float)$_POST['famount'];
 $discrebtion=mysqli_real_escape_string($link, $_POST['fdiscrebtion']);
 $cashCode=(int)$_POST['typeCash'];
 $cashType= substr($cashCode,0,5);


 //Cheak Balance
 $sqlCasher="SELECT (sum(`income`) - sum(`withdrawal`) ) as cashbalance FROM `cash_transaction`  WHERE `statmentRef` = '$cashCode'";
 $queryCasher=mysqli_query($link,$sqlCasher)or die("ERROR_SNSC : 01");
 $casherBalance=mysqli_fetch_assoc($queryCasher); 
 $avilablBalance=$casherBalance['cashbalance'];
 #
 if($avilablBalance > $amount)
 {
  //Get Financal Transaction Number Per Month
  $sqlFinancialTransaction="SELECT `transactionNumber` FROM `financialTransactions` 
  WHERE `transactionsYear` = $transactionsYear AND `transactionsMonth` = $transactionsMonth ORDER BY `transactionNumber` DESC LIMIT 1";
  $queryFinancialTransaction=mysqli_query($link,$sqlFinancialTransaction)or die("ERROR_SNSC : 01");
  $financialTransactionCount=mysqli_num_rows($queryFinancialTransaction);
  #
  if($financialTransactionCount > 0)
  {
   $financialTransactionNumber=mysqli_fetch_assoc($queryFinancialTransaction);  
   $lastNumber=$financialTransactionNumber['transactionNumber'];
   $nextNumber=($lastNumber + 1);
  }
  else
  {
   $nextNumber=01;
  }
  #
  if($cashType == 11620)
  {
   // Bank
   $cheakNum=(int)$_POST['numCheak'];
   $dueDate=mysqli_real_escape_string($link, $_POST['cheakDate']);
   $casher="Bank";
   $financialRef="Withdrawal Bank Entry";
   #
   if($recipientType == 11610)
   {
    //recived trusty
    $sqlWithdrawalGeneralCash2="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`)
    VALUES ('$transactionsDate',  '$amount' ,'0' , '$discrebtion' , $accCode ,$cashCode , $recipient)";
    mysqli_query($link,$sqlWithdrawalGeneralCash2);
   }
   // whithdrwo bank
   $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`,`chequNumber`,
   `valideDate`) VALUES ('$transactionsDate','0','$amount','$discrebtion',$cashCode,'$accCode','$recipient','$cheakNum','$dueDate')";
  }
  #
  else
  {
   $casher="Trasuty";
   $financialRef="Withdrawal Cash Entry";
   #
   if($recipientType == 11620)
   {
    // recived bank
    $sqlWithdrawalGeneralCash2="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`)
    VALUES ('$transactionsDate',  '$amount' ,'0' , '$discrebtion',$cashCode,$accCode , $recipient)";
    mysqli_query($link,$sqlWithdrawalGeneralCash2);
   }
   //withdrow trusty
   $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`)
   VALUES ('$transactionsDate','0','$amount','$discrebtion',$cashCode,'$accCode','$recipient')";
  }
  //Cheak Entry
  $sqlCashTransactionCount="SELECT `cash_transaction_id` FROM `cash_transaction` WHERE `transactionDate` = '$transactionsDate' AND `withdrawal`= '$amount' 
  AND `description` = '$discrebtion' AND `account` = '$accCode' AND `empCode` = '$recipient'";
  $queryCashTransactionCount=mysqli_query($link,$sqlCashTransactionCount)or die("ERROR_SNSC : 01");
  $cashTransactionCount=mysqli_num_rows($queryCashTransactionCount);
  #
  if($cashTransactionCount > 0)
  {
   $result="This Transaction has Already Been Registered";  
  }
  #
  else
  {

   #
   if(mysqli_query($link,$sqlWithdrawalGeneralCash))
   {
    


//Get Last Entry Id
$sqlCashTransactionId="SELECT `cash_transaction_id` FROM `cash_transaction` ORDER BY `cash_transaction_id` DESC LIMIT 1";
$queryCashTransactionId=mysqli_query($link,$sqlCashTransactionId)or die("ERROR_SNSC : 01");
$cashTransactionId=mysqli_fetch_assoc($queryCashTransactionId);
$tableName="Cash";
$tableRow=$cashTransactionId['cash_transaction_id'];
// Update Cash
$sqlUpdateCashTransaction="UPDATE `cash_transaction` SET `tableName` = '$tableName',`tableRowId`='$tableRow',`ftId`='$nextNumber' WHERE `cash_transaction_id` = $tableRow";
mysqli_query($link,$sqlUpdateCashTransaction);

    #
    $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
    `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
    VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','$amount','0','$discrebtion','$accCode','$financialRef','$tableName',$tableRow)";
    #
    if(mysqli_query($link,$sqlTransactionDebtor))
    {
     //
     $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
     `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','0','$amount','$casher','$cashCode',
     '$financialRef','$tableName',$tableRow)";
     if(mysqli_query($link,$sqlTransactionCreditor))
     {
      $result = 1;
      $action="Withdrawal $amount General Expenses From $casher";
      include_once("aduLog.php");
     }
     else
     {
      $result = "ERROR_SNSC : Failed to record creditor transaction";
     }
    }
    else
    {
     $result = "ERROR_SNSC : Failed to record debtor transaction";
    }
   }
   else
   {
    $result = "ERROR_SNSC : Failed to record cash transaction";
   }
  }
  echo $result;
 }
 else
 {
  echo 2;
 }
?>
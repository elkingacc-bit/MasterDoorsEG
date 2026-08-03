<?php
 @session_start();  
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $action="Add New Withdrawal Custody";
 $logRef=112;
 $financialRef="Custy Cashback";
 $result = "";
 $transactionsDate=$_POST['fDate'];
 $transactionsYear=date('Y', strtotime($transactionsDate));
 $transactionsMonth=date('m', strtotime($transactionsDate));
 $discrebtion=$_POST['fdiscrebtion'];
 $recipient=$_POST['frecipient'];
 $amount=$_POST['famount'];
 $acount=$_POST['fAccount'];
 $cashCode='116100100000'; 
 $sqlCustodyData="SELECT `poNum`, sum(`amount`) as cash,sum(`cashBack`) as comback FROM `custody`  WHERE `empCode` = '$recipient' AND `custodyRef` = 1";
 $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_SNSC : 02");
 $custodyGetData=mysqli_fetch_assoc($queryCustodyData);
 $projectNum=$_POST['poNumber'];
 $custody=($custodyGetData['cash'] - $custodyGetData['comback']);
 $reminingCustody=($custody-$amount);
 //cashback
 if($acount == "returnCash"){
  $sqlWithdrawalCustody="INSERT INTO `custody`(`custodyTransactionDate`,`poNum`,`discription`,`empCode`,`amount`,`cashBack`,`custodyRef`)
  VALUES ('$transactionsDate','$projectNum','$discrebtion','$recipient',0,'$amount',1)";
  if(mysqli_query($link,$sqlWithdrawalCustody)){
   if($reminingCustody == 0 ){
    $sqlTransactionCreditor2="UPDATE `custody` SET `custodyRef` = 2, `closedDate` = '$transactionsDate' WHERE `poNum` = '$projectNum' AND `empCode` = $recipient AND `custodyRef` =  1";
    mysqli_query($link,$sqlTransactionCreditor2);
      $result = 1;
   }    
    $result = 1;
  }
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
  //Cheak Entry
  $sqlCustodyCount="SELECT `custody_Id` FROM `custody` 
  WHERE `custodyTransactionDate` = '$transactionsDate' AND `poNum` = '$projectNum' AND `discription` = '$discrebtion' AND `empCode` = '$recipient' 
  AND `amount` = '$amount' AND `custodyRef` = 1";
  $queryCustodyTransactionCount=mysqli_query($link,$sqlCustodyCount)or die("ERROR_SNSC : 01");
  $custodyTransactionCount=mysqli_num_rows($queryCustodyTransactionCount);
  if($custodyTransactionCount > 0){
   $result="This Transaction has Already Been Registered";  
  }
  else{
   //Transactions Cash
   $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`poNum`,`empCode`)
   VALUES ('$transactionsDate','0','$amount','$discrebtion',$cashCode,'$acount','$projectNum','$recipient')";
   if(mysqli_query($link,$sqlWithdrawalGeneralCash)){
    //Transactions Custody
    $sqlWithdrawalCustody="INSERT INTO `custody`(`custodyTransactionDate`,`poNum`,`discription`,`empCode`,`amount`,`cashBack`,`custodyRef`)
    VALUES ('$transactionsDate','$projectNum','$discrebtion','$recipient',0,'$amount',1)";
    if(mysqli_query($link,$sqlWithdrawalCustody)){


//Get Last Entry Id
$sqlCashTransactionId="SELECT `cash_transaction_id` FROM `cash_transaction` ORDER BY `cash_transaction_id` DESC LIMIT 1";
$queryCashTransactionId=mysqli_query($link,$sqlCashTransactionId)or die("ERROR_SNSC : 01");
$cashTransactionId=mysqli_fetch_assoc($queryCashTransactionId);
$tableRow=$cashTransactionId['cash_transaction_id'];
//Get Last Custody Entry Id
$sqlCustodyTransactionId="SELECT `custody_Id` FROM `custody` ORDER BY `custody_Id` DESC LIMIT 1";
$queryCustodyTransactionId=mysqli_query($link,$sqlCustodyTransactionId)or die("ERROR_SNSC : 01");
$custodyTransactionId=mysqli_fetch_assoc($queryCustodyTransactionId);
$custodyRow=$custodyTransactionId['custody_Id'];

$tableName="custody";


// Update Cash
$sqlUpdateCashTransaction="UPDATE `cash_transaction` SET `tableName` = '$tableName',`tableRowId`='$custodyRow',`ftId`='$nextNumber' WHERE `cash_transaction_id` = $tableRow";
mysqli_query($link,$sqlUpdateCashTransaction);






     //Transactions Debtor
     $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
     `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','$amount','0','$discrebtion','$acount','$financialRef','$tableName',$tableRow)";
     if(mysqli_query($link,$sqlTransactionDebtor)){
      //Transactions Creditor
      $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
      `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','0','$amount','خزينه النقديه','$cashCode','$financialRef','$tableName',$tableRow)";
      mysqli_query($link,$sqlTransactionCreditor);
     }
    }
   }
   //Close Custdy
   if($reminingCustody == 0 ){
    $sqlTransactionCreditor2="UPDATE `custody` SET `custodyRef` = 2, `closedDate` = '$transactionsDate' WHERE `poNum` = '$projectNum' AND `empCode` = $recipient AND `custodyRef` =  1";
    mysqli_query($link,$sqlTransactionCreditor2);
   }
   $result = 1;
  }  
 }
 include_once("aduLog.php");
 echo $result;
?>
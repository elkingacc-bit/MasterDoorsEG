<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $transactionsDate=$_POST['fDate'];  
 $transactionsYear=date('Y', strtotime($transactionsDate));
 $transactionsMonth=date('m', strtotime($transactionsDate));
 $accCode=$_POST['fCode'];
 $recipient=$_POST['frecipient'];
 $amount=$_POST['famount'];
 $discrebtion=$_POST['fdiscrebtion'];
 $actionType=$_POST['fType'];
 $invoiceNum=$_POST['invNumber'];
 $logRef=114;
 $cashCode=$actionType;
 $paiedAmout=$_POST['paiedL'];
 $valiedAmout=$_POST['fValid'];

 $action="Withdrawal To Supplier Invoice $invoiceNum";
 $financialRef="Withdrawal To Supplier";
 $totalPaied=($paiedAmout+$amount);
 if($valiedAmout == $amount){
  $stuts = 6;
 }
 else{
  $stuts = 5;
 }
 $result = "";

 //Cheak Balance
 $sqlCasher="SELECT (sum(`income`) - sum(`withdrawal`) ) as cashbalance FROM `cash_transaction`  WHERE `statmentRef` = '$cashCode'";
 $queryCasher=mysqli_query($link,$sqlCasher)or die("ERROR_SNSC : 01");
 $casherBalance=mysqli_fetch_assoc($queryCasher); 
 $avilablBalance=$casherBalance['cashbalance'];
if($avilablBalance > $amount){



 //Cheak Entry
 $sqlCashTransactionCount="SELECT `cash_transaction_id` FROM `cash_transaction` WHERE `transactionDate` = '$transactionsDate' AND `withdrawal`= '$amount' 
 AND `description` = '$discrebtion' AND `account` = '$accCode' AND `empCode` = '$recipient'";
 $queryCashTransactionCount=mysqli_query($link,$sqlCashTransactionCount)or die("ERROR_SNSC : 01");
 $cashTransactionCount=mysqli_num_rows($queryCashTransactionCount);
 if($cashTransactionCount > 0){
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
  $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`poNum`,`empCode`)
  VALUES ('$transactionsDate','0','$amount','$discrebtion','$cashCode','$accCode','$invoiceNum','$recipient')";
  if(mysqli_query($link,$sqlWithdrawalGeneralCash)){
   



//Get Last Entry Id
$sqlCashTransactionId="SELECT `cash_transaction_id` FROM `cash_transaction` ORDER BY `cash_transaction_id` DESC LIMIT 1";
$queryCashTransactionId=mysqli_query($link,$sqlCashTransactionId)or die("ERROR_SNSC : 01");
$cashTransactionId=mysqli_fetch_assoc($queryCashTransactionId);
$tableName="supplierInvoice";
$tableRow=$cashTransactionId['cash_transaction_id'];
// Update Cash
$sqlUpdateCashTransaction="UPDATE `cash_transaction` SET `tableName` = '$tableName',`tableRowId`='$invoiceNum',`ftId`='$nextNumber' WHERE `cash_transaction_id` = $tableRow";
mysqli_query($link,$sqlUpdateCashTransaction);

   //
   $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
    `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
   VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','$amount','0','$discrebtion','$accCode','$financialRef','$tableName',$tableRow)";
   //
   if(mysqli_query($link,$sqlTransactionDebtor)){
    $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
    `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES 
    ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','0','$amount','خزينه النقديه','$cashCode','$financialRef','$tableName',$tableRow)";
    if(mysqli_query($link,$sqlTransactionCreditor)){
     $sqlUpdateSuppInv="UPDATE `supplierInvoice` SET `paidAmount`= '$totalPaied' WHERE `suppliersInvoiceId` = $invoiceNum";
     mysqli_query($link,$sqlUpdateSuppInv);        
     $sqlSupplierInvoiceDetiles="SELECT `ItemRowId` FROM `supplierInvoiceData` WHERE `supplierInvoiceNumber` = $invoiceNum";
     $querySupplierInvoiceDetilesData=mysqli_query($link,$sqlSupplierInvoiceDetiles)or die("ERROR_SNSC : 01");
     while($supplierInvoiceDetilesData=mysqli_fetch_assoc($querySupplierInvoiceDetilesData)){
      $orderItemId=$supplierInvoiceDetilesData['ItemRowId'];
      $sqlUpdateorderitems="UPDATE `supporderitems` SET `status`= $stuts WHERE `ItemRowId` = $orderItemId";
      mysqli_query($link,$sqlUpdateorderitems);
     }
     $result = 1;
    }
    $result = 1;
   }
   $result = 1;
  }
  $result = 1;
  include_once("../aduLog.php");
 }
 echo $result;

 }


else{
echo 2;
}

?>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $discrebtion='Withdrawal Sallary To Staff';
 $stafeId=(int)$_POST['stafId'];
 $transactionsDate=date("Y-m-d");
 $transactionsYear=date('Y', strtotime($transactionsDate));
 $transactionsMonth=date('m', strtotime($transactionsDate));
 $cashCode='116100100000';
 $accCode='312103100000';
 $logRef=114;
 $action="Withdrawal Sallary To Staff";
 $recipient=(int)$_SESSION['id'];
 $financialRef="Withdrawal Sallary";
 // Get Sales Invoice From Sales Invoice
 $sqlSalesInoice="SELECT `poRowId`,count(`attendDate`) as dayWork,`datePayment`,`paymentRef`, sum(`penalty`) as nag, sum(`Reward`) as plu,`ref` 
 FROM `outsidemanpower` WHERE `paymentRef` = 0 AND `staffRId` = $stafeId GROUP BY `poRowId` ";
 $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 01");
 While($salesInoiceData=mysqli_fetch_assoc($querySalesInoice)){
  $sqlStaff="SELECT `staffname`,`staffposition`,`dayval` FROM `allstaff` WHERE `id` = $stafeId";
  $queryStaff=mysqli_query($link,$sqlStaff)or die("ERROR_SNSC : 01");
  $staffData=mysqli_fetch_assoc($queryStaff);
  $workAmount=($salesInoiceData['dayWork'] * $staffData['dayval'] );
  $rewordAmount=($salesInoiceData['plu'] * $staffData['dayval'] );
  $pelantyAmount=($salesInoiceData['nag'] * $staffData['dayval'] );
  $amount=(($workAmount + $rewordAmount ) - $pelantyAmount);
  $sqlProject="SELECT `orderType`, `PoNum` FROM `customerpo` WHERE `poId` = $salesInoiceData[poRowId]";
  $queryProject=mysqli_query($link,$sqlProject)or die("ERROR_SNSC : 01");
  $projectData=mysqli_fetch_assoc($queryProject);
  // Po Number
  $poNum=$salesInoiceData['poRowId'];
  $sqlPoNum="SELECT `jobidref` FROM `customerpo` WHERE `poId` = $poNum";
  $queryPoNum=mysqli_query($link,$sqlPoNum)or die("ERROR_SNSC : 01");
  $poNumData=mysqli_fetch_assoc($queryPoNum);
  $invoiceNum=$poNumData['jobidref'];
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
  //Cheak Balance
  $sqlCasher="SELECT (sum(`income`) - sum(`withdrawal`) ) as cashbalance FROM `cash_transaction`  WHERE `statmentRef` = '$cashCode'";
  $queryCasher=mysqli_query($link,$sqlCasher)or die("ERROR_SNSC : 01");
  $casherBalance=mysqli_fetch_assoc($queryCasher); 
  $avilablBalance=$casherBalance['cashbalance'];
  if($avilablBalance > $amount){
   //cash
   $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`poNum`,`empCode`)
   VALUES ('$transactionsDate','0','$amount','$discrebtion',$cashCode,'$accCode','$invoiceNum','$recipient')";
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
    $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`
    ,`description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
    VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','$amount','0','$discrebtion','$accCode','$financialRef','$tableName',$tableRow)";
    if(mysqli_query($link,$sqlTransactionDebtor)){
     //
     $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
     `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','0','$amount',
     'خزينه النقديه','$cashCode','$financialRef','$tableName',$tableRow)";
     mysqli_query($link,$sqlTransactionCreditor);
     $result = 1;
    }
    $result = 1;
   } 
   $sqlUpdateorderitems="UPDATE `outsidemanpower` SET `paymentRef`= 1 WHERE `paymentRef` = 0 AND `staffRId` = $stafeId";
   mysqli_query($link,$sqlUpdateorderitems);
   $result = 1;
   include_once("../aduLog.php");
   
  }
  else{
     $result = 2;

  }
  
 }
   echo $result;
?>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $rowCount = (int)$_POST['count'];
 $transactionsDate=mysqli_real_escape_string($link, $_POST['c1']);
 $transactionsYear=date('Y', strtotime($transactionsDate));
 $transactionsMonth=date('m', strtotime($transactionsDate));
 $logRef=115;
 $action="Add Settlement Entry";
 $result = 0;
 $financialRef="Add Settlement Entry";
 //Get Financal Transaction Number Per Month
 $sqlFinancialTransaction="SELECT `transactionNumber` FROM `financialTransactions` 
 WHERE `transactionsYear` = $transactionsYear AND `transactionsMonth` = $transactionsMonth ORDER BY `transactionNumber` DESC LIMIT 1";
 $queryFinancialTransaction=mysqli_query($link,$sqlFinancialTransaction)or die("ERROR_SNSC : 01");
 $financialTransactionCount=mysqli_num_rows($queryFinancialTransaction);
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
 if($rowCount == 2)
 {
  $colDate1=mysqli_real_escape_string($link, $_POST['c1']);
  $colaccountName2=mysqli_real_escape_string($link, $_POST['c2']);
  $colDebtor3=mysqli_real_escape_string($link, $_POST['c3']);
  $colCreditor4=mysqli_real_escape_string($link, $_POST['c4']);
  $Description5=mysqli_real_escape_string($link, $_POST['c5']);
  $colDate6=mysqli_real_escape_string($link, $_POST['c6']);
  $colaccountName7=mysqli_real_escape_string($link, $_POST['c7']);
  $colDebtor8=mysqli_real_escape_string($link, $_POST['c8']);
  $colCreditor9=mysqli_real_escape_string($link, $_POST['c9']);
  $Description10=mysqli_real_escape_string($link, $_POST['c10']);
  $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
  VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$colDate1','$colDebtor3','$colCreditor4','$Description5','$colaccountName2','$financialRef','Settlement',0)";
  if(mysqli_query($link,$sqlTransactionDebtor))
  {
   $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
   `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES
   ('$transactionsYear','$transactionsMonth','$nextNumber','$colDate6','$colDebtor8','$colCreditor9','$Description10','$colaccountName7','$financialRef','Settlement',0)";
   if(mysqli_query($link,$sqlTransactionCreditor))
   {
    $result = 1;
   }
  }
 }
 else if($rowCount == 3)
 {
  $colDate1=mysqli_real_escape_string($link, $_POST['c1']);
  $colaccountName2=mysqli_real_escape_string($link, $_POST['c2']);
  $colDebtor3=mysqli_real_escape_string($link, $_POST['c3']);
  $colCreditor4=mysqli_real_escape_string($link, $_POST['c4']);
  $colDescription5=mysqli_real_escape_string($link, $_POST['c5']);
  $colDate6=mysqli_real_escape_string($link, $_POST['c6']);
  $colaccountName7=mysqli_real_escape_string($link, $_POST['c7']);
  $colDebtor8=mysqli_real_escape_string($link, $_POST['c8']);
  $colCreditor9=mysqli_real_escape_string($link, $_POST['c9']);
  $colDescription10=mysqli_real_escape_string($link, $_POST['c10']);
  $colDate11=mysqli_real_escape_string($link, $_POST['c11']);
  $colaccountName12=mysqli_real_escape_string($link, $_POST['c12']);
  $colDebtor13=mysqli_real_escape_string($link, $_POST['c13']);
  $colCreditor14=mysqli_real_escape_string($link, $_POST['c14']);
  $colDescription15=mysqli_real_escape_string($link, $_POST['c15']);
  $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
  VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$colDate1','$colDebtor3','$colCreditor4','$colDescription5','$colaccountName2','$financialRef','Settlement',0)";
  if(mysqli_query($link,$sqlTransactionDebtor))
  {
   $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
   `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES
   ('$transactionsYear','$transactionsMonth','$nextNumber','$colDate6','$colDebtor8','$colCreditor9','$colDescription10','$colaccountName7','$financialRef','Settlement',0)";
   if(mysqli_query($link,$sqlTransactionCreditor))
   {
    $sqlTransaction3="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,`description`,
    `transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES
    ('$transactionsYear','$transactionsMonth','$nextNumber','$colDate11','$colDebtor13','$colCreditor14','$colDescription15','$colaccountName12','$financialRef','Settlement',0)";
    if(mysqli_query($link,$sqlTransaction3))
    {
     $result = 1;
    }
   }
  }
 }
 else if($rowCount == 4)
 {
  $colDate1=mysqli_real_escape_string($link, $_POST['c1']);
  $colaccountName2=mysqli_real_escape_string($link, $_POST['c2']);
  $colDebtor3=mysqli_real_escape_string($link, $_POST['c3']);
  $colCreditor4=mysqli_real_escape_string($link, $_POST['c4']);
  $colDescription5=mysqli_real_escape_string($link, $_POST['c5']);
  $colDate6=mysqli_real_escape_string($link, $_POST['c6']);
  $colaccountName7=mysqli_real_escape_string($link, $_POST['c7']);
  $colDebtor8=mysqli_real_escape_string($link, $_POST['c8']);
  $colCreditor9=mysqli_real_escape_string($link, $_POST['c9']);
  $colDescription10=mysqli_real_escape_string($link, $_POST['c10']);
  $colDate11=mysqli_real_escape_string($link, $_POST['c11']);
  $colaccountName12=mysqli_real_escape_string($link, $_POST['c12']);
  $colDebtor13=mysqli_real_escape_string($link, $_POST['c13']);
  $colCreditor14=mysqli_real_escape_string($link, $_POST['c14']);
  $colDescription15=mysqli_real_escape_string($link, $_POST['c15']);
  $col1Date6=mysqli_real_escape_string($link, $_POST['c16']);
  $colaccountName17=mysqli_real_escape_string($link, $_POST['c17']);
  $colDebtor18=mysqli_real_escape_string($link, $_POST['c18']);
  $colCreditor19=mysqli_real_escape_string($link, $_POST['c19']);
  $colDescription20=mysqli_real_escape_string($link, $_POST['c20']);
  $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
  VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$colDate1','$colDebtor3','$colCreditor4','$colDescription5','$colaccountName2','$financialRef','Settlement',0)";
  if(mysqli_query($link,$sqlTransactionDebtor))
  {
   $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
   `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES
   ('$transactionsYear','$transactionsMonth','$nextNumber','$colDate6','$colDebtor8','$colCreditor9','$colDescription10','$colaccountName7','$financialRef','Settlement',0)";
   if(mysqli_query($link,$sqlTransactionCreditor))
   {
    $sqlTransaction3="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,`description`,
    `transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES
    ('$transactionsYear','$transactionsMonth','$nextNumber','$colDate11','$colDebtor13','$colCreditor14','$colDescription15','$colaccountName12','$financialRef','Settlement',0)";
    if(mysqli_query($link,$sqlTransaction3))
    {
     $sqlTransaction4="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,`description`,
     `transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES
     ('$transactionsYear','$transactionsMonth','$nextNumber','$col1Date6','$colDebtor18','$colCreditor19','$colDescription20','$colaccountName17','$financialRef','Settlement',0)";
     if(mysqli_query($link,$sqlTransaction4))
     {
      $result = 1;
     }
    }
   }
  }
 }
 if($result == 1)
 {
  include_once("../aduLog.php");  
 }
 echo $result;
?>
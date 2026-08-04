<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $result = "";
 $supplierId=(int)$_POST['pSupplier'];
 $transactionsDate=mysqli_real_escape_string($link, $_POST['pDate']);
 $transactionsYear=date('Y', strtotime($transactionsDate));
 $transactionsMonth=date('m', strtotime($transactionsDate));
 $trxAmount=(float)$_POST['pAmount'];
 $trxDisc=mysqli_real_escape_string($link, $_POST['pDiscrebtion']);
 $cashCode=(int)$_POST['typeCash'];
 $financialRef="Withdrawal To Supplier";
 $sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $supplierId";
 $queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :01-AU_AU_S".mysqli_error($link));
 $resGetSupplier = mysqli_fetch_assoc($queryGetSupplier);
 $action="Add New Withdrawal Payment To Supplier $resGetSupplier[suppliername]";
 $logRef=111;
 $cashType= substr($cashCode,0,5);
 //Cheak Entry
 $sqlCashTransactionCount="SELECT `cash_transaction_id` FROM `cash_transaction` WHERE `transactionDate` = '$transactionsDate' AND `withdrawal`= '$trxAmount' 
 AND `description` = '$trxDisc' AND `account` = '$supplierId' AND `statmentRef` = $cashCode";
 $queryCashTransactionCount=mysqli_query($link,$sqlCashTransactionCount)or die("ERROR_SNSC : 01");
 $cashTransactionCount=mysqli_num_rows($queryCashTransactionCount);
 if($cashTransactionCount > 0){
  $result="This Transaction has Already Been Registered";
 }
 else{
  //Cheak Balance
  $sqlCasher="SELECT (sum(`income`) - sum(`withdrawal`) ) as cashbalance FROM `cash_transaction`  WHERE `statmentRef` = '$cashCode'";
  $queryCasher=mysqli_query($link,$sqlCasher)or die("ERROR_SNSC : 01");
  $casherBalance=mysqli_fetch_assoc($queryCasher); 
  $avilablBalance=$casherBalance['cashbalance'];
  if($avilablBalance > $trxAmount){
   //Get Financal Transaction Number Per Month
   $sqlFinancialTransaction="SELECT `transactionNumber` FROM `financialTransactions` 
   WHERE `transactionsYear` = $transactionsYear AND `transactionsMonth` = $transactionsMonth ORDER BY `transactionNumber` DESC LIMIT 1";
   $queryFinancialTransaction=mysqli_query($link,$sqlFinancialTransaction)or die("ERROR_SNSC : 01");
   if( mysqli_num_rows($queryFinancialTransaction) > 0){
    $financialTransactionNumber=mysqli_fetch_assoc($queryFinancialTransaction);  
    $lastNumber=$financialTransactionNumber['transactionNumber'];
    $nextNumber=($lastNumber + 1);
   }
   else{$nextNumber=01;}
   # Bank
   if($cashType == 11620){
    $CheakNum=(int)$_POST['numCheak'];
    $dateCheak=mysqli_real_escape_string($link, $_POST['cheakDate']);
    $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`,`chequNumber`,`valideDate`)
    VALUES ('$transactionsDate','$trxAmount','$trxDisc','$cashCode','$supplierId','$_SESSION[id]',$CheakNum,'$dateCheak')"; 
   }
   // Cash
   else{
    $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`)
    VALUES ('$transactionsDate','$trxAmount','$trxDisc','$cashCode','$supplierId','$_SESSION[id]')"; 
   }
   if(mysqli_query($link,$sqlWithdrawalGeneralCash)){
    //Get Last Entry Id
    $sqlCashTransactionId="SELECT `cash_transaction_id` FROM `cash_transaction` ORDER BY `cash_transaction_id` DESC LIMIT 1";
    $queryCashTransactionId=mysqli_query($link,$sqlCashTransactionId)or die("ERROR_SNSC : 01");
    $cashTransactionId=mysqli_fetch_assoc($queryCashTransactionId);
    $tableName="cash_transaction";
    $tableRow=$cashTransactionId['cash_transaction_id'];
    // Update Cash
    $sqlUpdateCashTransaction="UPDATE `cash_transaction` SET `ftId`='$nextNumber' WHERE `cash_transaction_id` = $tableRow";
    if(mysqli_query($link,$sqlUpdateCashTransaction)){
     $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
     `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
     VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','$trxAmount','0','$trxDisc','$supplierId','$financialRef','$tableName',$tableRow)";
     //
     if(mysqli_query($link,$sqlTransactionDebtor)){
      $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
      `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`) VALUES 
      ('$transactionsYear','$transactionsMonth','$nextNumber','$transactionsDate','0','$trxAmount','$trxDisc','$cashCode','$financialRef','$tableName',$tableRow)";
      if(mysqli_query($link,$sqlTransactionCreditor)){
       $validAccount=$trxAmount;
       $payment=0;
       $sqlSupplierStockInv="SELECT `suppliersInvoiceId`,`suppliersInvoiceNumber`,`suppliersInvoiceTotal`,`paidAmount` FROM `supplierInvoice` 
       WHERE `suppliersInvoiceTotal` != `paidAmount` AND `supplierCode` = $supplierId ";
       $querySupplierStockInv=mysqli_query($link,$sqlSupplierStockInv)or die("ERROR_SNSC : 01");
       while($supplierStockInv=mysqli_fetch_assoc($querySupplierStockInv)){
        $avilable=($supplierStockInv['suppliersInvoiceTotal'] - $supplierStockInv['paidAmount']);
        $validAccount -= $avilable;
        if($validAccount > 0){
         $payment+=$avilable;
         $invId=$supplierStockInv['suppliersInvoiceId'];
         $totalPaidAmount=($supplierStockInv['paidAmount'] + $trxAmount);
         $sqlUpdateSuppInv="UPDATE `supplierInvoice` SET `paidAmount`= '$totalPaidAmount' WHERE `suppliersInvoiceId` = $invId";
         mysqli_query($link,$sqlUpdateSuppInv);
        }
        else{
         $sqlSupplierLastInv="SELECT `suppliersInvoiceId`,`suppliersInvoiceNumber`,`suppliersInvoiceDate`,`suppliersInvoiceTotal`,`paidAmount` FROM `supplierInvoice` 
         WHERE `suppliersInvoiceTotal` != `paidAmount` AND `supplierCode` = $supplierId ORDER BY `suppliersInvoiceDate` ASC LIMIT 1";
         $querySupplierLastInv=mysqli_query($link,$sqlSupplierLastInv)or die("ERROR_SNSC : 01");
         $resSupplierLastInv=mysqli_fetch_assoc($querySupplierLastInv);
         $lastId=$resSupplierLastInv['suppliersInvoiceId'];
         $lastCash=($trxAmount - $payment);
         $totalPaidAmount=($resSupplierLastInv['paidAmount'] + $lastCash);
       
        }
       }


$sqlUpdateLastSuppInv="UPDATE `supplierInvoice` SET `paidAmount`= '$totalPaidAmount' WHERE `suppliersInvoiceId` = $lastId";
mysqli_query($link,$sqlUpdateLastSuppInv);

       echo 1;
       include_once("../aduLog.php");
      }
     }
    }
   }
  }
  else{
   echo 2;
  }
 }
?>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $collectDate=mysqli_real_escape_string($link, $_POST['pDate']);
 $customerId=(int)$_POST['pSupplier'];
 $collectAmount=(float)$_POST['pAmount'];
 $collectDisc=mysqli_real_escape_string($link, $_POST['pDiscrebtion']);
 $cashCode=(int)$_POST['typeCash'];
 $cashType= substr($cashCode,0,5);
 # Financial Transaction
 $transactionsYear=date('Y', strtotime($collectDate));
 $transactionsMonth=date('m', strtotime($collectDate));
 $financialRef="Customer Collect";
 # Get Financal Transaction Number Per Month
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
 // Add VAT Financial Transactions creditor
 $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
 `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
 VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$collectDate','0','$collectAmount','$collectDisc','$customerId','$financialRef','CustomerCollect',$customerId)";
 if(mysqli_query($link,$sqlTransactionDebtor)){
  // Add VAT Financial Transactions Debtor
  $sqlTransactionDebtor2="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
  VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$collectDate','$collectAmount','0','$collectDisc','$cashCode','$financialRef','CustomerCollect',$customerId)";
  mysqli_query($link,$sqlTransactionDebtor2); 
 }
 //Get Jop Data
 $sqlSalesInoice="SELECT `jobId`,`projectName`,`offerValue`,`invoice` FROM `job` WHERE  `customer` = $customerId AND `offerStatus` = 'Won' ORDER BY `startDate` ASC";
 $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 02");
 if(mysqli_num_rows($querySalesInoice) > 0){
  while($salesInoiceData=mysqli_fetch_assoc($querySalesInoice)){
   $jopId=$salesInoiceData['jobId'];
   //Get Po Data
   $sqlCustomerPo="SELECT `poVal`, `POVat` FROM `customerpo` WHERE `jobidref` = $jopId AND `custCode` = $customerId";
   $quaryCustomerPo=mysqli_query($link,$sqlCustomerPo)or die("ERROR_SNSC : 02");
   if(mysqli_num_rows($quaryCustomerPo) > 0){
    $resultCustomerPo=mysqli_fetch_assoc($quaryCustomerPo);
    $totalPoPrice = ($resultCustomerPo['poVal'] + $resultCustomerPo['POVat']);
   }
   else{
    $totalPoPrice = 0;
   }
   // Get Dwonpayment Paied Data
   $sqlDwonpayment="SELECT `cash_transaction_id`,`income`,`ftId` FROM `cash_transaction` 
   WHERE `account` = $customerId AND `invNumber` = '$jopId' AND `description` LIKE 'Collect Dwonpaymen%'";
   $quaryDwonpayment=mysqli_query($link,$sqlDwonpayment)or die("ERROR_SNSC : 02");
   if(mysqli_num_rows($quaryDwonpayment) > 0){
    $dwonpaymentData=mysqli_fetch_assoc($quaryDwonpayment);
    $dwonpaymen = $dwonpaymentData['income'];       
   }
   else{
    $dwonpaymen = 0;
   }
   // Extract
   $sqlExtract="SELECT `salesInvoiceSupTotal` FROM `salesInvoiceDraft` WHERE `customerCode` = $customerId AND `jopRef` = '$jopId'";
   $quaryExtract=mysqli_query($link,$sqlExtract)or die("ERROR_SNSC : 02");
   if(mysqli_num_rows($quaryExtract) > 0){
    $resultExtract=mysqli_fetch_assoc($quaryExtract);
    $extract = $resultExtract['salesInvoiceSupTotal'];       
   }
   else{
    $extract = 0;
   }
   $sqlCollect="SELECT `salesInvoiceId`,`invoiceCollectAmount` FROM `salesInvoice` WHERE `customerCode` = $customerId AND `jopRef` = '$jopId'";
   $quaryCollect=mysqli_query($link,$sqlCollect)or die("ERROR_SNSC : 02");
   if(mysqli_num_rows($quaryCollect) > 0){
    $resultCollect=mysqli_fetch_assoc($quaryCollect);
    $invoiceId=$resultCollect['salesInvoiceId'];
    $collect = $resultCollect['invoiceCollectAmount'];
   }
   else{
    $collect = 0; 
   }
   // Have Amount
   if($collectAmount > 0){
    // Not Have Invoice
    if($salesInoiceData['invoice'] == 'No'){
     $tCollect=($dwonpaymen + $extract);
     $valid=($totalPoPrice - $tCollect);
     // Collect All Valied 
     if($collectAmount > $valid){
      //Add Extract From Customer Po
      $sqlAddSupplierInvoice="INSERT INTO `salesInvoiceDraft`(`jopRef`,`customerCode`,`salesInvoiceSupTotal`,`salesInvoiceDate`,`itemsQya`,`itemRowId`)
      VALUES('$jopId','$customerId','$valid','$collectDate','0','0')";
      mysqli_query($link,$sqlAddSupplierInvoice);
      $sqlTransactionDebtor="UPDATE `custorderdeliver` SET `ref`= 1  WHERE `jobRowId` = $jopId AND `ref` = '0'";
      mysqli_query($link,$sqlTransactionDebtor);
     }
     // Collect same Amount
     else{
      //Add Extract From Customer Po
      $sqlAddSupplierInvoice="INSERT INTO `salesInvoiceDraft`(`jopRef`,`customerCode`,`salesInvoiceSupTotal`,`salesInvoiceDate`,`itemsQya`,`itemRowId`)
      VALUES('$jopId','$customerId','$collectAmount','$collectDate','0','0')";
      mysqli_query($link,$sqlAddSupplierInvoice);
      $sqlTransactionDebtor="UPDATE `custorderdeliver` SET `ref`= 1  WHERE `jobRowId` = $jopId AND `ref` = '0'";
      mysqli_query($link,$sqlTransactionDebtor);
     }
    }
    // Have Invoice
    else if($salesInoiceData['invoice'] == 'Yes'){
     $tCollect=($collect);
     $valid=($totalPoPrice - $tCollect);
     if($collectAmount > $valid){
      $totalCollectAmount = ($valid + $tCollect);
     }
     else{
      $totalCollectAmount = ($collectAmount + $tCollect);
     }
     $sqlUpdateSuppInv="UPDATE `salesInvoice` SET `invoiceCollectAmount`= '$totalCollectAmount' WHERE `salesInvoiceId` = $invoiceId";
     mysqli_query($link,$sqlUpdateSuppInv);
    }
    // Add Cash Transaction
    if($collectAmount > $valid){
     $collectValide = $valid;
    }
    else{
     $collectValide = $collectAmount ; 
    }
    # Bank
    if($cashType == 11620){
     $cheakNum=(int)$_POST['numCheak'];
     $dueDate=mysqli_real_escape_string($link, $_POST['cheakDate']);
     // Add Collect To Bank
     $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`amountRef`,`statmentRef`,`account`,`poNum`,`empCode`,
     `chequNumber`, `valideDate`) 
     VALUES ('$collectDate','$collectValide','0','$collectDisc','$collectValide',$cashCode,'$customerId','$jopId','$_SESSION[id]','$cheakNum','$dueDate')";
     mysqli_query($link,$sqlWithdrawalGeneralCash);
    }
    # Cash
    else{
     // Add Collect To Cash
     $sqlWithdrawalGeneralCash="INSERT INTO `cash_transaction`(`transactionDate`,`income`,`withdrawal`,`description`,`amountRef`,`statmentRef`,`account`,`poNum`,`empCode`)
     VALUES ('$collectDate','$collectValide','0','$collectDisc','$collectValide',$cashCode,'$customerId','$jopId','$_SESSION[id]')";
     mysqli_query($link,$sqlWithdrawalGeneralCash);     
    }
   }
   $collectAmount -= $valid;
  }
  //include_once("../aduLog.php");
 }
 echo 1;
 //
?>
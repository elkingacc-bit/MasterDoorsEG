<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $cash_transaction_id=$_POST['rcashRowId'];
 $cashColumUpdate=$_POST['rcashColum'];
 $cashNewAmount=$_POST['rnAmount'];
 $cashOldAmount=$_POST['roAmount'];
 $cashNewDate=$_POST['rnDate'];
 $cashNewAccount=$_POST['rnAccount'];
 $cashNewCasher=$_POST['rcashAccount'];
 $cashNewDidcr=$_POST['rcashDisc'];
 $financialTransactionsId1=$_POST['rftRowId1'];
 $transactionsDate1=$_POST['rftDate1'];
 $debtor1=$_POST['rdepit1'];
 $creditor1=$_POST['rcredit1'];
 $transactionCode1=$_POST['rftAccount1'];
 $description1=$_POST['rftDisc1'];
 $financialTransactionsId2=$_POST['rftRowId2'];
 $transactionsDate2=$_POST['rftDate2'];
 $debtor2=$_POST['rdepit2'];
 $creditor2=$_POST['rcredit2'];
 $transactionCode2=$_POST['rftAccount2'];
 $description2=$_POST['rftDisc2'];
 $otherTableName=$_POST['rnextTableName'];
 $otherTableRowId=$_POST['rnextTableRowId'];
 $logRef=120;
 $action="Edit In $cashColumUpdate Cash Transaction From Amount $cashOldAmount  To Amount $cashNewAmount";
 #1
 // Update Cash
 $sqlUpdateCashTransaction="UPDATE `cash_transaction` SET `transactionDate` = '$cashNewDate', $cashColumUpdate = '$cashNewAmount',`description` = '$cashNewDidcr',
 `account` = '$cashNewAccount',`statmentRef`='$cashNewCasher' WHERE `cash_transaction_id` = $cash_transaction_id";
 mysqli_query($link,$sqlUpdateCashTransaction);
 #2
 // Update FinancialTransactions 1
 $sqlUpdateFinancialTransaction="UPDATE `financialTransactions` SET `transactionsDate` = '$transactionsDate1' ,`debtor` = '$debtor1' ,`creditor` = '$creditor1' ,
`description` = '$description1' ,`transactionCode` = '$transactionCode1' WHERE `financialTransactionsId` = $financialTransactionsId1";
 mysqli_query($link,$sqlUpdateFinancialTransaction);
 #3
 // financialTransactions 2
 $sqlUpdateFinancialTransaction2="UPDATE `financialTransactions` SET `transactionsDate` = '$transactionsDate2' ,`debtor` = '$debtor2' ,`creditor` = '$creditor2' ,
 `description` = '$description2' ,`transactionCode` = '$transactionCode2' WHERE `financialTransactionsId` = $financialTransactionsId2";
 mysqli_query($link,$sqlUpdateFinancialTransaction2);
 #4
 // Update Advance
 if($otherTableName == "advance"){
  if($cashColumUpdate == "income"){
   $sqlUpdateAdvanceTransaction="UPDATE `advance` SET `advanceDate` = '$cashNewDate', `cashback` ='$cashNewAmount' WHERE `advanceId` = $otherTableRowId";
   mysqli_query($link,$sqlUpdateAdvanceTransaction);
   $action .= "And Edit In Advance Installment From Amount $cashOldAmount  To Amount $cashNewAmount";
  }
  else if($cashColumUpdate == "withdrawal"){
   $sqlUpdateAdvanceTransaction="UPDATE `advance` SET  `advanceDate` = '$cashNewDate',`recived` ='$cashNewAmount' WHERE `advanceId` = $otherTableRowId";
   mysqli_query($link,$sqlUpdateAdvanceTransaction);
   $action .= "And Edit In Advance Recived From Amount $cashOldAmount  To Amount $cashNewAmount";
  }
 }
 // Update SupplierInvoice
 else if($otherTableName == "supplierInvoice"){
  $sqlNextTableData="SELECT `paidAmount` FROM `supplierInvoice` WHERE `suppliersInvoiceId` = '$otherTableRowId'";
  $queryNextTableData=mysqli_query($link,$sqlNextTableData)or die("ERROR LOA_S:01");
  $resultNextTableData=mysqli_fetch_assoc($queryNextTableData);
  $oldPaid=$resultNextTableData['paidAmount'];
  $beforPayment=($oldPaid - $cashOldAmount);
  $newPaid=($beforPayment + $cashNewAmount);
  $sqlUpdateSupplierInvoice="UPDATE `supplierInvoice` SET `paidAmount` = '$newPaid' WHERE `suppliersInvoiceId` = $otherTableRowId";
  mysqli_query($link,$sqlUpdateSupplierInvoice);
  $action .= "And Edit In Supplier Paid Amount From Amount $cashOldAmount To Amount $cashNewAmount";
 }
 // Update custody
 else if($otherTableName == "custody"){
  $sqlUpdateCustodyTransaction="UPDATE `custody` SET `custodyTransactionDate` = '$cashNewDate',`discription`= '$cashNewDidcr',`cashBack` = '$cashNewAmount' 
  WHERE `custody_Id` = $otherTableRowId";
  mysqli_query($link,$sqlUpdateCustodyTransaction);
  $action .= "And Edit In Custody Cashback Amount From Amount $cashOldAmount To Amount $cashNewAmount";
 }
 // Update SalesInvoiceDraft
 else if($otherTableName == "salesInvoiceDraft"){
  $sqlUpdateSalesInvoiceDraft="UPDATE `salesInvoiceDraft` SET  `salesInvoiceSupTotal` = '$cashNewAmount', `salesInvoiceDate`='$cashNewDate'
  WHERE `salesInvoiceDraftId` = $otherTableRowId";
  mysqli_query($link,$sqlUpdateSalesInvoiceDraft);
  $action .= "And Edit In Billing in Process Amount From Amount $cashOldAmount To Amount $cashNewAmount";
 }
 // Update SalesInvoice
 else if($otherTableName == "salesInvoice"){
  $sqlNextTableData="SELECT `invoiceCollectAmount` FROM `salesInvoice` WHERE `salesInvoiceId` = '$otherTableRowId'";
  $queryNextTableData=mysqli_query($link,$sqlNextTableData)or die("ERROR LOA_S:01");
  $resultNextTableData=mysqli_fetch_assoc($queryNextTableData);
  $oldPaid=$resultNextTableData['invoiceCollectAmount'];
  $beforPayment=($oldPaid - $cashOldAmount);
  $newPaid=($beforPayment + $cashNewAmount);
  $sqlUpdateSalesInvoiceTransaction="UPDATE `salesInvoice` SET `invoiceCollectAmount` = '$newPaid' WHERE `salesInvoiceId` = $otherTableRowId";
  mysqli_query($link,$sqlUpdateSalesInvoiceTransaction);
  $action .="And Edit In Customer Collect Amount From Amount $cashOldAmount To Amount $cashNewAmount";
 }
 include_once("../aduLog.php");
?>
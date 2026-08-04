<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $jopId=(int)$_POST['jopRef'];
 $invoiceNumber=mysqli_real_escape_string($link, $_POST['invNumber']);
 $dateInvoice=mysqli_real_escape_string($link, $_POST['invDate']);
 $action="Add New Invoice Number $invoiceNumber";
 $logRef=113;
 $incomCode='410100100000';
 $vat14Code='212100100000';
 $transactionsYear=date('Y', strtotime($dateInvoice));
 $transactionsMonth=date('m', strtotime($dateInvoice));
 $financialRef="Sales Invoice";
 // Get Customer Po Data
 $sqlSalesInoice="SELECT `orderType`,`PoNum`,`poDate`,`custCode`,`poVal`,`POVat` FROM `customerpo` WHERE `jobidref` = $jopId";
 $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 02");
 $salesInoiceData=mysqli_fetch_assoc($querySalesInoice);
 $supTotal=$salesInoiceData['poVal'];
 $taxAmount=$salesInoiceData['POVat'];
 $totalInv=( $supTotal + $taxAmount); 
 $customer=$salesInoiceData['custCode'];
 if($taxAmount == 0){
  $invType="Not VAT";	
 }
 else{
  $invType="VAT";
 }
 $lastColectAmount = 0;
 //Get extract collect
 $sqlExtract="SELECT sum(`salesInvoiceSupTotal`) as colect FROM `salesInvoiceDraft` WHERE `jopRef` = $jopId AND `ref` = 1 AND `customerCode` = $customer";
 $quaryExtract=mysqli_query($link,$sqlExtract)or die("ERROR_SNSC : 02");
 $extractCount=mysqli_num_rows($quaryExtract);
 if($extractCount > 0){
  $extractData=mysqli_fetch_assoc($quaryExtract);
  $lastColectAmount += $extractData['colect'];       
 }
 // Get Dwonpayment Paied Data
 $sqlDwonpayment="SELECT `cash_transaction_id`,`income`,`ftId` FROM `cash_transaction` 
 WHERE `account` = $customer AND `invNumber` = '$jopId' AND `description` LIKE 'Collect Dwonpaymen%'";
 $quaryDwonpayment=mysqli_query($link,$sqlDwonpayment)or die("ERROR_SNSC : 02");
 $dwonpaymentCount=mysqli_num_rows($quaryDwonpayment);
 if($dwonpaymentCount > 0){
  $dwonpaymentData=mysqli_fetch_assoc($quaryDwonpayment);
  $lastColectAmount += $dwonpaymentData['income'];       
  $cashRow=$dwonpaymentData['cash_transaction_id'];
  $ftEntryNumber=$dwonpaymentData['ftId'];
 }
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
 // Get Customer Data
 $sqlCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $salesInoiceData[custCode]";
 $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
 $customerData=mysqli_fetch_assoc($quaryCustomer);
 //Add Sales Invoice
 $sqlAddSupplierInvoice="INSERT INTO `salesInvoice`(`salesInvoiceNumber`,`jopRef`,`salesInvoiceDate`,`customerCode`,`salesInvoiceType`,`salesInvoiceSupTotal`,
 `salesInvoictVat`,`totalInvoice`,`invoiceCollectAmount`) VALUES
 ('$invoiceNumber','$jopId','$dateInvoice','$customer','$invType','$supTotal','$taxAmount','$totalInv','$lastColectAmount')";
 if(mysqli_query($link,$sqlAddSupplierInvoice)){
  //UPDATE extract Ref
  $sqlInvoiceDrafteRef="UPDATE `salesInvoiceDraft` SET `ref` = 2 WHERE `jopRef` = $jopId AND `ref` = 1 AND `customerCode` = $customer";
  mysqli_query($link,$sqlInvoiceDrafteRef);
  //Get Last Entry Id
  $sqlCashTransactionId="SELECT `salesInvoiceId` FROM `salesInvoice` 
  WHERE `salesInvoiceNumber` = '$invoiceNumber' AND `salesInvoiceDate`= '$dateInvoice' AND `customerCode` = '$customer' ORDER BY `salesInvoiceId` DESC LIMIT 1";
  $queryCashTransactionId=mysqli_query($link,$sqlCashTransactionId)or die("ERROR_SNSC : 01");
  $cashTransactionId=mysqli_fetch_assoc($queryCashTransactionId);
  $tableName="salesInvoice";
  $tableRow=$cashTransactionId['salesInvoiceId'];
  // Update Cash
  if($extractCount > 0){
   //get Old Data To Update Table Name In Cash
   $sqlExtractTableData="SELECT `salesInvoiceDraftId` FROM `salesInvoiceDraft` WHERE  `jopRef` = $jopId AND `ref` = 1 AND `customerCode` = $customer";
   $queryExtractTableData=mysqli_query($link,$sqlExtractTableData)or die("ERROR LOA_S:01");
   while($resultExtractTableData=mysqli_fetch_assoc($queryExtractTableData)){
    // Get Cash Row Id 
    $extractRowId=$resultExtractTableData['salesInvoiceDraftId']; 
    $sqlCashRow="SELECT `cash_transaction_id` FROM `cash_transaction` WHERE `tableName` = 'SalesInvoiceDraft' AND `tableRowId` = $extractRowId";
    $quaryCashRow=mysqli_query($link,$sqlCashRow)or die("ERROR_SNSC : 02");
    $resCashRow=mysqli_fetch_assoc($quaryCashRow);
    $cashExtractRow=$resCashRow['cash_transaction_id'];
    // Update Cash
    $sqlUpdateCashTransactionExtract="UPDATE `cash_transaction` SET `tableName` = '$tableName',`tableRowId`='$tableRow' WHERE `cash_transaction_id` = $cashExtractRow";
    mysqli_query($link,$sqlUpdateCashTransactionExtract);
   }
  }
  if($dwonpaymentCount > 0){
   $sqlUpdateCashTransaction="UPDATE `cash_transaction` SET `tableName` = '$tableName',`tableRowId`='$tableRow' WHERE `cash_transaction_id` = $cashRow";
   mysqli_query($link,$sqlUpdateCashTransaction);
   // Update Financial Transactions
  }
  // Add Customer Financial Transactions debtor
  $sqlTransactionDebtor="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
  VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','$totalInv','0','Sales Invoice Num $invoiceNumber','$customer','$financialRef',
  '$tableName',$tableRow)";
  mysqli_query($link,$sqlTransactionDebtor);
  // Add VAT Financial Transactions creditor
  $sqlTransactionDebtor2="INSERT INTO `financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
  VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','0','$taxAmount','$invoiceNumber Vat','$vat14Code','$financialRef','$tableName',
  $tableRow)";
  mysqli_query($link,$sqlTransactionDebtor2);
  // Add Pur Financial Transactions creditor
  $sqlTransactionCreditor="INSERT INTO`financialTransactions`(`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,`debtor`,`creditor`,
  `description`,`transactionCode`,`entryRef`, `tableName`,`tableRowId`)
  VALUES ('$transactionsYear','$transactionsMonth','$nextNumber','$dateInvoice','0','$supTotal','$invoiceNumber Revenue','$incomCode','$financialRef','$tableName',
  $tableRow)";
  mysqli_query($link,$sqlTransactionCreditor);
  // Get Invoice Id
  $sqlSalesInvoiceLast="SELECT `salesInvoiceId` FROM `salesInvoice` 
  WHERE `salesInvoiceNumber` = '$invoiceNumber' AND `salesInvoiceDate`= '$dateInvoice' AND `customerCode` = '$customer'";
  $querySalesInvoiceLast=mysqli_query($link,$sqlSalesInvoiceLast)or die("ERROR_SNSC : 01");
  $lastSalesInvoice=mysqli_fetch_assoc($querySalesInvoiceLast);
  $lastInvoiceId=$lastSalesInvoice['salesInvoiceId'];
 }
 //Get Sales Invoice Items
 $sqlSalesJop="SELECT `startDate`,`localref`,`customer`,`offerValue`,`jobtype`,`jobreceivables`,`endDate`,`invoice`, `salesman` FROM `job` WHERE `jobId` = $jopId";
 $querySalesJop=mysqli_query($link,$sqlSalesJop)or die("ERROR_SNSC : 02");
 While($jopData=mysqli_fetch_assoc($querySalesJop)){
  $orderType=$jopData['jobtype'];
  if($orderType == "Automatic"){
   $sqlOrderItems="SELECT `id`,`doortype`,`doorspecs`,`motorspecs`,`doorprice`,`doorqty`,`totalprice`,`jobid`,`ref` FROM `autodoorsoffer` WHERE `jobid` = $jopId";
   $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
   While($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
    $itemsRowId=$itemsData['id'];
    $items=$itemsData['doorspecs'];
    $itemsType=$itemsData['doortype'];
    $quantity=$itemsData['doorqty'];
    $unitPrice=$itemsData['doorprice'];
    $totalPrice=$itemsData['totalprice'];
    //insert Sales Invoice Items
    $sqlSalesInvoiceItems="INSERT INTO `salesInvoiceData`(`salesInvoiceId`,`jopRef`,`itemsRowId`,`unitCount`,`unitPrice`,`totalItemsPrice`)
    VALUES('$lastInvoiceId','$jopId','$itemsRowId','$quantity','$unitPrice','$totalPrice')";
    mysqli_query($link,$sqlSalesInvoiceItems);
   }
  }
  else if($orderType == "Doors" ){
   $sqlOrderItems="SELECT `id`,`itemname`,`itemtype`,`msquerprice`,`itemqty`,`totalprice` FROM `itemoffer` WHERE `jobref` = $jopId";
   $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
   While($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
    $itemsRowId=$itemsData['id'];
    $items=$itemsData['itemname'];
    $itemsType=$itemsData['itemtype'];
    $quantity=$itemsData['itemqty'];
    $unitPrice=$itemsData['msquerprice'];
    $totalPrice=$itemsData['totalprice'];
    //insert Sales Invoice Items
    $sqlSalesInvoiceItems="INSERT INTO `salesInvoiceData`(`salesInvoiceId`,`jopRef`,`itemsRowId`,`unitCount`,`unitPrice`,`totalItemsPrice`)
    VALUES('$lastInvoiceId','$jopId','$itemsRowId','$quantity','$unitPrice','$totalPrice')";
    mysqli_query($link,$sqlSalesInvoiceItems);
    //insert Sales Invoice Items Hardware
    $sqlOrderItemsHW="SELECT `offproId`,`descripcode`,`descripquantity`,`unitPrice`,`totalprice` FROM `offerproperties` WHERE  `ioidref` = $itemsRowId";
    $queryOrderItemsDataHW=mysqli_query($link,$sqlOrderItemsHW)or die("ERROR_SNSC : 01");
    WHILE($itemsHW=mysqli_fetch_assoc($queryOrderItemsDataHW)){
     $v1=$itemsHW['descripcode'];
     $v2=$itemsHW['descripquantity'];
     $v3=$itemsHW['unitPrice'];
     $v4=$itemsHW['totalprice'];
     $sqlSalesInvoiceItemsHw="INSERT INTO `salesInvoiceData`(`salesInvoiceId`,`jopRef`,`itemsRowId`,`unitCount`,`unitPrice`,`totalItemsPrice`)
     VALUES('$lastInvoiceId','$jopId','$v1','$v2','$v3','$v4')";
     mysqli_query($link,$sqlSalesInvoiceItemsHw);
    }
   }
  }    
  else if($orderType == "Stock" ){
   $sqlOrderItems="SELECT `id`, `descripcode`,`descripqty`,`descripprice`,`totalprice`,`jobref`,`ref`,`whref` FROM `stockoffers` WHERE `jobref` = $jopId";
   $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
   While($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
    $itemsRowId=$itemsData['id'];
    $items=$itemsData['itemname'];
    $quantity=$itemsData['descripqty'];
    $unitPrice=$itemsData['descripprice'];
    $totalPrice=$itemsData['totalprice'];
    //insert Sales Invoice Items
    $sqlSalesInvoiceItems="INSERT INTO `salesInvoiceData`(`salesInvoiceId`,`jopRef`,`itemsRowId`,`unitCount`,`unitPrice`,`totalItemsPrice`)
    VALUES('$lastInvoiceId','$jopId','$itemsRowId','$quantity','$unitPrice','$totalPrice')";
    mysqli_query($link,$sqlSalesInvoiceItems);
   }
  }
  // Maintenance Type  
  else if($orderType == "Maintenance" ){
   $sqlOrderItems="SELECT `id`,`type`, `price`, `typeqty`, `totalprice`, `jobid`, `ref`, `purchasesCost` FROM `maintoffers` WHERE `jobid` = $jopId";
   $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
   while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
    $itemsRowId=$itemsData['id'];
    $items=$itemsData['type'];
    $quantity=$itemsData['typeqty'];
    $unitPrice=$itemsData['price']; 
    $totalPrice=$itemsData['totalprice'];
    //insert Sales Invoice Items
    $sqlSalesInvoiceItems="INSERT INTO `salesInvoiceData`(`salesInvoiceId`,`jopRef`,`itemsRowId`,`unitCount`,`unitPrice`,`totalItemsPrice`) 
    VALUES('$lastInvoiceId','$jopId','$itemsRowId','$quantity','$unitPrice','$totalPrice')";
    mysqli_query($link,$sqlSalesInvoiceItems);
   }
  }
 }
 //Add Ref Invoice Jop
 $sqlJopInvoiceRef="UPDATE `job` SET `invoice` = 'Yes' WHERE `jobId` = $jopId";
 mysqli_query($link,$sqlJopInvoiceRef);
 include_once("../aduLog.php");
 echo $lastInvoiceId;
?>
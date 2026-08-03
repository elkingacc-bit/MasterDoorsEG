<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $rowid=$_POST['rRowId'];
 $oDate=$_POST['roDate'];
 $oNumber=$_POST['roNumber'];
 $oAmount=$_POST['roAmount'];
 $oVat=$_POST['roVat'];
 $oTotal=$_POST['roTotal'];
 $nDate=$_POST['rnDate'];
 $nNumber=$_POST['rnNumber'];
 $nAmount=$_POST['rnAmount'];
 $nVat=$_POST['rnVat'];
 $nTotal=$_POST['rnTotal'];
 $action = "Edit In SuppliersInvoice ";
 $logRef=120;
 if($nDate != $oDate){
  $action .= "& Date From $oDate To $nDate";
 }
 else if($nNumber != $oNumber){
  $action .= "& Inv. Num From $oNumber To $nNumber";
 }
 else if($nAmount != $oAmount){
  $action .= "& Amount From $oAmount To $nAmount";
 }
 else if($nVat != $oVat){
  $action .= "& VAT From $oVat To $nVat";
 }
 else if($nTotal != $oTotal){
  $action .= "& Total Inv. From $oTotal To $nTotal";
 }
 $sqlDataEdit="SELECT `suppliersInvoiceId`,`suppliersInvoiceNumber`,`suppliersInvoiceDate`, `supplierCode`, `suppliersInvoiceType`,`suppliersInvoiceSupTotal`,
 `suppliersInvoiceVat`,`suppliersInvoiceTotal` FROM `supplierInvoice` WHERE `suppliersInvoiceId` = $rowid";
 $queryDataEdit=mysqli_query($link,$sqlDataEdit);
 $resultDataEdit=mysqli_fetch_assoc($queryDataEdit);
 $supCode=$resultDataEdit['supplierCode'];
 // Update Invoice Data
 $sqlUpdateSupplierInvoiceData="UPDATE `supplierInvoice` SET  `suppliersInvoiceDate` = '$nDate', `suppliersInvoiceNumber` = '$nNumber' ,`supplierOrderNum` = '$nNumber',
 `suppliersInvoiceSupTotal` = '$nAmount' , `suppliersInvoiceVat` = '$nVat' , `suppliersInvoiceTotal` = '$nTotal' WHERE `suppliersInvoiceId` = $rowid";
 mysqli_query($link,$sqlUpdateSupplierInvoiceData);
 
 // Get Financial Transactions 
 $sqlFinancialData="SELECT `financialTransactionsId`,`transactionsDate`,`debtor`,`creditor`,`description`,`transactionCode`  FROM `financialTransactions`
 WHERE `tableName` = 'supplierInvoice' AND `tableRowId` = $rowid ";
 $queryFinancialData=mysqli_query($link,$sqlFinancialData);
 
  while($resFinancial=mysqli_fetch_assoc($queryFinancialData)){
  $account=$resFinancial['transactionCode'];
  $financialTransactionsId=$resFinancial['financialTransactionsId'];
  if($account == $supCode){
   // FinancialTransactions 1
   $sqlUpdateFinancialTransaction="UPDATE `financialTransactions` SET `transactionsDate` = '$nDate' ,`creditor` = '$nTotal' 
   WHERE `financialTransactionsId` = $financialTransactionsId";
   mysqli_query($link,$sqlUpdateFinancialTransaction);
  }
else if($account == 212100100000){
    // FinancialTransactions 1
   $sqlUpdateFinancialTransaction="UPDATE `financialTransactions` SET `transactionsDate` = '$nDate' ,`debtor` = '$nVat' 
   WHERE `financialTransactionsId` = $financialTransactionsId";
   mysqli_query($link,$sqlUpdateFinancialTransaction);
  }

else if($account == 312100100000){
    // FinancialTransactions 1
   $sqlUpdateFinancialTransaction="UPDATE `financialTransactions` SET `transactionsDate` = '$nDate' ,`debtor` = '$nAmount' 
   WHERE `financialTransactionsId` = $financialTransactionsId";
   mysqli_query($link,$sqlUpdateFinancialTransaction);
  }


 }






 // Get Invoice Items
 $sqlAccountantCodeData="SELECT `supplierInvoiceDataId`, `supplierInvoiceNumber`,`ItemRowId`,`supplierInvoiceCount`,`supplierInvoiceUnitPrice`,
`supplierInvoiceTotalItems`,`ref` FROM `supplierInvoiceData`
 WHERE `supplierInvoiceNumber` = $rowid ";  
 $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
 if(mysqli_num_rows($queryAccountantCodeData) > 0 ){ 
  while($accData=mysqli_fetch_assoc($queryAccountantCodeData)){
   $stockRef=$accData['ref'];
   $stockItem=$accData['ItemRowId'];
   $stockQun=$accData['supplierInvoiceCount'];
   $stockAmount=$accData['supplierInvoiceUnitPrice'];
   if($stockRef == 1){
    $sqlStockData="SELECT `warehouseId` FROM `warehouse` WHERE `description` = $stockItem AND `supplier`= $supCode AND `invoicenumber` = '$oNumber'";
    $queryStockData=mysqli_query($link,$sqlStockData);
    if(mysqli_num_rows($queryStockData) > 0 ){
     $resStockData=mysqli_fetch_assoc($queryStockData);
     $whRowid=$resStockData['warehouseId'];
     // Update Warehouse Data
     $sqlUpdateWarehouseeData="UPDATE `warehouse` SET `invoicenumber` = '$nNumber' WHERE `warehouseId` = $whRowid";
     mysqli_query($link,$sqlUpdateWarehouseeData);
     $action .= " & InvNum In Warehouse From $oTotal To $nTotal";
    }
   }
  }
 }
 include_once("aduLog.php");
?>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $tableRowId =$_POST['rowId'];
 $sqlDataEdit="SELECT `cash_transaction_id`,`transactionDate`,`income`,`withdrawal`,`description`,`account`,`empCode`,`tableName`,`tableRowId`,`ftId`
 FROM `cash_transaction` WHERE `cash_transaction_id` = '$tableRowId'";
 $queryDataEdit=mysqli_query($link,$sqlDataEdit); 
 $resultDataEdit=mysqli_fetch_assoc($queryDataEdit);
 $subTable=$resultDataEdit['tableName'];
 $subTableId=$resultDataEdit['tableRowId'];
 # Delete From Cash
 $sqlDeleteRowFromCash = "DELETE FROM  `cash_transaction` WHERE `cash_transaction_id` = '$tableRowId'";
 mysqli_query($link,$sqlDeleteRowFromCash)or die("ERROR :Delete Row From Cash"); 
 echo "<br> Record Deleted Successfully <br>";
 # Financial Transactions
 $sqlFinancialData="SELECT `financialTransactionsId`,`transactionsDate`, `debtor`, `creditor`,`description` FROM `financialTransactions` 
 WHERE `tableName` = '$subTable' AND `transactionsDate` = '$resultDataEdit[transactionDate]' AND `tableRowId` = $subTableId ";
 $queryFinancialData=mysqli_query($link,$sqlFinancialData);
 if(mysqli_num_rows($queryFinancialData) == 0){
  echo "<br> No Financial Transactions <br>";
 }
 else{
  while($resultFinancialData=mysqli_fetch_assoc($queryFinancialData)){
   # Delete From Financial Transactions
   $sqlDeleteRowFinancial = "DELETE FROM  `financialTransactions` WHERE `financialTransactionsId` = '$resultFinancialData[financialTransactionsId]'";
   mysqli_query($link,$sqlDeleteRowFinancial)or die("ERROR :Delete Row From Cash"); 
  }
  echo "<br> Record Deleted Successfully 2<br>";
 }
 if($subTable == 'advance'){
  $sqlAdvanceDataEdit="SELECT `advanceId`, `advanceDate`, `empId`, `recived`, `cashback`, `installment`, `recevedRef`, `advanceRef` FROM `advance` WHERE `advanceId` = $subTableId";
  $queryAdvanceDataEdit=mysqli_query($link,$sqlAdvanceDataEdit); 
  if($resultAdvanceDataEdit=mysqli_fetch_assoc($queryAdvanceDataEdit)){
   $sqlDeleteRowFromAdvanced = "DELETE FROM  `advance` WHERE `advanceId` = '$resultAdvanceDataEdit[advanceId]'";
   mysqli_query($link,$sqlDeleteRowFromAdvanced)or die("ERROR :Delete Row From Cash"); 
   echo "<br> Record Deleted Successfully 3<br>";
  }
 }
 else if($subTable == 'custody'){
  $sqlCustodyDataEdit="SELECT `custody_Id`, `custodyTransactionDate`, `poNum`, `discription`, `empCode`, `amount`, `cashBack` FROM `custody` WHERE `custody_Id` = $subTableId";
  $queryCustodyDataEdit=mysqli_query($link,$sqlCustodyDataEdit); 
  if($resultCustodyDataEdit=mysqli_fetch_assoc($queryCustodyDataEdit)){
   $sqlDeleteRowFromCustody = "DELETE FROM  `custody` WHERE `custody_Id` = '$resultCustodyDataEdit[custody_Id]'";
   mysqli_query($link,$sqlDeleteRowFromCustody)or die("ERROR :Delete Row From Cash"); 
   echo "<br> Record Deleted Successfully 3<br>";
  }
 }
?>
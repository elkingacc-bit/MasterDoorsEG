<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $tableRowId = (int)$_POST['rowId'];

 $stmtGet = mysqli_prepare($link, "SELECT `cash_transaction_id`,`transactionDate`,`income`,`withdrawal`,`description`,`account`,`empCode`,`tableName`,`tableRowId`,`ftId`
 FROM `cash_transaction` WHERE `cash_transaction_id` = ?");
 mysqli_stmt_bind_param($stmtGet, "i", $tableRowId);
 mysqli_stmt_execute($stmtGet);
 $resultDataEdit = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtGet));
 mysqli_stmt_close($stmtGet);

 if (!$resultDataEdit) {
  echo "<br> Record Not Found <br>";
  exit;
 }

 $subTable = $resultDataEdit['tableName'];
 $subTableId = $resultDataEdit['tableRowId'];
 $transactionDate = $resultDataEdit['transactionDate'];

 # Delete From Cash
 $stmtDelCash = mysqli_prepare($link, "DELETE FROM `cash_transaction` WHERE `cash_transaction_id` = ?");
 mysqli_stmt_bind_param($stmtDelCash, "i", $tableRowId);
 mysqli_stmt_execute($stmtDelCash) or die("ERROR :Delete Row From Cash");
 mysqli_stmt_close($stmtDelCash);
 echo "<br> Record Deleted Successfully <br>";

 # Financial Transactions
 $stmtFin = mysqli_prepare($link, "SELECT `financialTransactionsId`,`transactionsDate`, `debtor`, `creditor`,`description` FROM `financialTransactions`
 WHERE `tableName` = ? AND `transactionsDate` = ? AND `tableRowId` = ?");
 mysqli_stmt_bind_param($stmtFin, "ssi", $subTable, $transactionDate, $subTableId);
 mysqli_stmt_execute($stmtFin);
 $queryFinancialData = mysqli_stmt_get_result($stmtFin);
 if (mysqli_num_rows($queryFinancialData) == 0) {
  echo "<br> No Financial Transactions <br>";
 } else {
  while ($resultFinancialData = mysqli_fetch_assoc($queryFinancialData)) {
   # Delete From Financial Transactions
   $finId = (int)$resultFinancialData['financialTransactionsId'];
   $stmtDelFin = mysqli_prepare($link, "DELETE FROM `financialTransactions` WHERE `financialTransactionsId` = ?");
   mysqli_stmt_bind_param($stmtDelFin, "i", $finId);
   mysqli_stmt_execute($stmtDelFin) or die("ERROR :Delete Row From Cash");
   mysqli_stmt_close($stmtDelFin);
  }
  echo "<br> Record Deleted Successfully 2<br>";
 }
 mysqli_stmt_close($stmtFin);

 if ($subTable == 'advance') {
  $stmtAdv = mysqli_prepare($link, "SELECT `advanceId` FROM `advance` WHERE `advanceId` = ?");
  mysqli_stmt_bind_param($stmtAdv, "i", $subTableId);
  mysqli_stmt_execute($stmtAdv);
  if ($resultAdvanceDataEdit = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtAdv))) {
   $advanceId = (int)$resultAdvanceDataEdit['advanceId'];
   $stmtDelAdv = mysqli_prepare($link, "DELETE FROM `advance` WHERE `advanceId` = ?");
   mysqli_stmt_bind_param($stmtDelAdv, "i", $advanceId);
   mysqli_stmt_execute($stmtDelAdv) or die("ERROR :Delete Row From Cash");
   mysqli_stmt_close($stmtDelAdv);
   echo "<br> Record Deleted Successfully 3<br>";
  }
  mysqli_stmt_close($stmtAdv);
 } else if ($subTable == 'custody') {
  $stmtCus = mysqli_prepare($link, "SELECT `custody_Id` FROM `custody` WHERE `custody_Id` = ?");
  mysqli_stmt_bind_param($stmtCus, "i", $subTableId);
  mysqli_stmt_execute($stmtCus);
  if ($resultCustodyDataEdit = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtCus))) {
   $custodyId = (int)$resultCustodyDataEdit['custody_Id'];
   $stmtDelCus = mysqli_prepare($link, "DELETE FROM `custody` WHERE `custody_Id` = ?");
   mysqli_stmt_bind_param($stmtDelCus, "i", $custodyId);
   mysqli_stmt_execute($stmtDelCus) or die("ERROR :Delete Row From Cash");
   mysqli_stmt_close($stmtDelCus);
   echo "<br> Record Deleted Successfully 3<br>";
  }
  mysqli_stmt_close($stmtCus);
 }
?>
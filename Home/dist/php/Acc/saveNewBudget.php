<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");

 $accountCode = trim($_POST['accountCode'] ?? '');
 $year = (int)($_POST['year'] ?? 0);
 $month = (int)($_POST['month'] ?? 0);
 $amount = (float)($_POST['amount'] ?? 0);

 if ($accountCode === '' || $year < 2000 || $month < 1 || $month > 12 || $amount <= 0) {
  echo "Invalid Data";
  exit;
 }

 $stmt = mysqli_prepare($link, "INSERT INTO `budgetPlan` (`accountCode`,`budgetYear`,`budgetMonth`,`budgetAmount`,`createdBy`)
  VALUES (?,?,?,?,?)
  ON DUPLICATE KEY UPDATE `budgetAmount` = VALUES(`budgetAmount`)");
 $createdBy = $_SESSION['UserName'] ?? ($_SESSION['Dept'] ?? '');
 mysqli_stmt_bind_param($stmt, "siids", $accountCode, $year, $month, $amount, $createdBy);

 if (mysqli_stmt_execute($stmt)) {
  echo 1;
 } else {
  echo "ERROR_BUD:01 - " . mysqli_error($link);
 }
 mysqli_stmt_close($stmt);
?>

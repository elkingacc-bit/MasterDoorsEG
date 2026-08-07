<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");

 $assetName = trim($_POST['assetName'] ?? '');
 $assetCategory = trim($_POST['assetCategory'] ?? '');
 $accountCode = trim($_POST['accountCode'] ?? '');
 $purchaseDate = $_POST['purchaseDate'] ?? '';
 $cost = (float)($_POST['cost'] ?? 0);
 $usefulLife = (int)($_POST['usefulLife'] ?? 0);
 $salvage = (float)($_POST['salvage'] ?? 0);

 if ($assetName === '' || $purchaseDate === '' || $cost <= 0 || $usefulLife <= 0) {
  echo "Invalid Data";
  exit;
 }
 $accountCodeParam = $accountCode === '' ? null : $accountCode;
 $createdBy = $_SESSION['UserName'] ?? ($_SESSION['Dept'] ?? '');

 $stmt = mysqli_prepare($link, "INSERT INTO `fixedAssets`
  (`assetName`,`assetCategory`,`accountCode`,`purchaseDate`,`purchaseCost`,`usefulLifeYears`,`salvageValue`,`createdBy`)
  VALUES (?,?,?,?,?,?,?,?)");
 mysqli_stmt_bind_param($stmt, "ssssdids", $assetName, $assetCategory, $accountCodeParam, $purchaseDate, $cost, $usefulLife, $salvage, $createdBy);

 if (mysqli_stmt_execute($stmt)) {
  echo 1;
 } else {
  echo "ERROR_FA:01 - " . mysqli_error($link);
 }
 mysqli_stmt_close($stmt);
?>

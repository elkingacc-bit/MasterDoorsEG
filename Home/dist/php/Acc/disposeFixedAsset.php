<?php
 include_once("../authCheck.php");
 include_once("../connection.php");

 $fixedAssetId = (int)($_POST['fixedAssetId'] ?? 0);
 $disposalDate = $_POST['disposalDate'] ?? '';

 if ($fixedAssetId <= 0 || $disposalDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $disposalDate)) {
  echo "Invalid Data";
  exit;
 }

 $stmt = mysqli_prepare($link, "UPDATE `fixedAssets` SET `status` = 'Disposed', `disposalDate` = ? WHERE `fixedAssetId` = ?");
 mysqli_stmt_bind_param($stmt, "si", $disposalDate, $fixedAssetId);
 if (mysqli_stmt_execute($stmt)) {
  echo 1;
 } else {
  echo "ERROR_FA:02 - " . mysqli_error($link);
 }
 mysqli_stmt_close($stmt);
?>

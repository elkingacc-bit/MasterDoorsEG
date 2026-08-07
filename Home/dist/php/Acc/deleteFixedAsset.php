<?php
 include_once("../authCheck.php");
 include_once("../connection.php");

 $fixedAssetId = (int)($_POST['fixedAssetId'] ?? 0);
 if ($fixedAssetId <= 0) { echo "Invalid Id"; exit; }

 $stmt = mysqli_prepare($link, "DELETE FROM `fixedAssets` WHERE `fixedAssetId` = ?");
 mysqli_stmt_bind_param($stmt, "i", $fixedAssetId);
 if (mysqli_stmt_execute($stmt)) {
  echo 1;
 } else {
  echo "ERROR_FA:03 - " . mysqli_error($link);
 }
 mysqli_stmt_close($stmt);
?>

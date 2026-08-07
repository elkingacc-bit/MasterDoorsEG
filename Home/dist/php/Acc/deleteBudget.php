<?php
 include_once("../authCheck.php");
 include_once("../connection.php");

 $budgetId = (int)($_POST['budgetId'] ?? 0);
 if ($budgetId <= 0) { echo "Invalid Id"; exit; }

 $stmt = mysqli_prepare($link, "DELETE FROM `budgetPlan` WHERE `budgetId` = ?");
 mysqli_stmt_bind_param($stmt, "i", $budgetId);
 if (mysqli_stmt_execute($stmt)) {
  echo 1;
 } else {
  echo "ERROR_BUD:03 - " . mysqli_error($link);
 }
 mysqli_stmt_close($stmt);
?>

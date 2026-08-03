<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $action="Edit In Investment Change ";
 $rowid=$_POST['rRowId'];
 $col=$_POST['rColum'];
 $oldDate=$_POST['roDate'];
 $oldAmount=$_POST['roAmount'];
 $oldQun=$_POST['roQun'];
 $oldGroup=$_POST['roGroup'];
 $oldDisc=$_POST['roDisc'];
 $newDate=$_POST['rnDate'];
 $newAmount=$_POST['rnAmount'];
 $newQun=$_POST['rnQun'];
 $newGroup=$_POST['rnGroup'];
 $newDisc=$_POST['rnDisc'];
 if($newDate != $oldDate){ $action .=" Date From $oldDate To $newDate";}
 if($newAmount != $oldAmount){ $action .=" Amount From $oldAmount To $newAmount";}
 if($newQun != $oldQun){ $action .=" Qun From $oldQun To $newQun";}
 if($newGroup != $oldGroup){ $action .=" Group From $oldGroup To $newGroup";}
 if($newDisc != $oldDisc){ $action .=" Discr From $oldDisc To $newDisc";}
 $logRef=120;
 if($col == "buyingAmount"){
  // Update Investment
  $sqlUpdateInvestment="UPDATE `investment` SET `transactionDate` = '$newDate', `quantaty` = '$newQun', `buyingAmount` = '$newAmount', `investmentGroup` = '$newGroup',
  `description`='$newDisc' WHERE `investmentId` = $rowid";
  mysqli_query($link,$sqlUpdateInvestment);
 }
 else if($col == "salesAmount"){
  // Update Investment
  $sqlUpdateInvestment="UPDATE `investment` SET `transactionDate` = '$newDate', `quantaty` = '$newQun', `salesAmount` = '$newAmount', `investmentGroup` = '$newGroup',
  `description` = '$newDisc' WHERE `investmentId` = $rowid";
  mysqli_query($link,$sqlUpdateInvestment);
 }
 include_once("aduLog.php");
?>
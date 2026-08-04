<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $partnerRowId = $_POST['rRowId'];
 $oName = $_POST['roName'];
 $oPer = $_POST['roPer'];
 $nName = $_POST['rnName']; 
 $nPer = $_POST['rnPer'];
 $action = "Edit In partnership ";
 $logRef=120;
 if($oName != $nName){
  $action .= "& Name From $oName To $nName";
 }
 else if($oPer != $nPer){
  $action .= "& Per From $oPer To $nPer";
 }
 // Update partnership  
 $sqlUpdatePartnership="UPDATE `partnershipRatio` SET `partnersName` = '$nName',`partnerPercentage` = '$nPer' WHERE `partnershipRatioId` = $partnerRowId";
 mysqli_query($link,$sqlUpdatePartnership);
 include_once("../aduLog.php");
?>
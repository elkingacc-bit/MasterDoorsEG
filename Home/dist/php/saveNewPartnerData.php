<?php
 @session_start();  
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $logRef=115;
 $partnerPer=$_POST['fper'];
 $partner=$_POST['name']; 
 $result="";
 //Cheak Entry
 $sqlCashTransactionCount="SELECT `partnersName`,`partnerPercentage` FROM `partnershipRatio` WHERE `partnersName` = '$partner' AND `partnerPercentage`= '$partnerPer'";
 $queryCashTransactionCount=mysqli_query($link,$sqlCashTransactionCount)or die("ERROR_SNSC : 01");
 $cashTransactionCount=mysqli_num_rows($queryCashTransactionCount);
 if($cashTransactionCount > 0){
  $sqlWithdrawalGeneralCash="UPDATE `partnershipRatio` SET `partnerPercentage`= '$partnerPer' WHERE `partnersName`= '$partner'";
  mysqli_query($link,$sqlWithdrawalGeneralCash);
  $result="Change Partner $partner Percent";
  $action="Change Partner $partner Percent";
 }
 else{
  $sqlWithdrawalGeneralCash="INSERT INTO `partnershipRatio`(`partnersName`, `partnerPercentage`) VALUES ('$partner','$partnerPer')";
  mysqli_query($link,$sqlWithdrawalGeneralCash);
  $action="Add New Partner";
  $result = 1;
 }
 include_once("aduLog.php");
 echo $result;
?>
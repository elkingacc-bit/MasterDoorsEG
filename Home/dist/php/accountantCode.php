<?php
 date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
 $sqlAccountData="SELECT `accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 12 AND `accountCode` LIKE '3%'";
 $queryAccountlData=mysqli_query($link,$sqlAccountData)or die("ERROR LOA_S:01");
 echo "<option value=''>Choose</option>";  
 while($accountData=mysqli_fetch_assoc($queryAccountlData)){
  $group=substr($accountData['accountCode'], 0, 3);
  $sqlGroupData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $group AND `codeLen` = 3";
  $queryGroupData=mysqli_query($link,$sqlGroupData)or die("ERROR LOA_S:01");
  $groupName=mysqli_fetch_assoc($queryGroupData);
  echo"<option value='$accountData[accountCode]'>$groupName[accountName] - $accountData[accountName]</option>";
 }
?>
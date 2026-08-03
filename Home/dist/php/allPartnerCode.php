<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $sqlFirstLevelData="SELECT `accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 12 AND `accountCode` LIKE '5%' 
 AND `accountCode` NOT IN (SELECT `partnersName` FROM `partnershipRatio`)";
 $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
 echo "<option value=''>Choose</option>";  
 while($firstLevel=mysqli_fetch_assoc($queryFirstLevelData)){
  echo"<option value='$firstLevel[accountCode]'>$firstLevel[accountName]</option>";
 }
?>
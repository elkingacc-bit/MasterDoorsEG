<?php
 date_default_timezone_set("Africa/Cairo");
include_once("../connection.php");
 $sqlFirstLevelData="SELECT `accountCode`,`accountName` FROM `accountantcode`
  WHERE `codeLen` = 12 AND `accountCode` LIKE '312%' AND `accountCode` != 312103100000 AND `accountCode` != 312103312103100001 AND `accountCode` != 312100100000";
 $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
 echo "<option value=''>Choose</option>";  
 while($firstLevel=mysqli_fetch_assoc($queryFirstLevelData)){
  echo"<option value='$firstLevel[accountCode]'>$firstLevel[accountName]</option>";
 }
?>
<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $lastCode=(int)$_POST['accCode'];
 $sqlFirstLevelData="SELECT `accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 12 AND `accountCode` != $lastCode";
 $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
 echo "<option value=''>Choose</option>";  
 while($firstLevel=mysqli_fetch_assoc($queryFirstLevelData)){
  $mynumber = $firstLevel['accountCode'];
  $group=substr($mynumber, 0, 1);
  $sqlgroup="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $group";
  $querygroup=mysqli_query($link,$sqlgroup)or die("ERROR LOA_S:01");
  $groupLevel=mysqli_fetch_assoc($querygroup);
  echo"<option value='$firstLevel[accountCode]'>$groupLevel[accountName] / $firstLevel[accountName]</option>";
 }
?>
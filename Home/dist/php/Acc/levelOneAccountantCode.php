<?php
 date_default_timezone_set("Africa/Cairo");
include_once("../connection.php");
 $sqlFirstLevelData="SELECT `level1_name`,`level1Code` FROM `accountantcode` WHERE `codeLen` = 1";
 $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
 echo "<option value=''>Choose</option>";  
 while($firstLevel=mysqli_fetch_assoc($queryFirstLevelData)){
  echo"<option value='$firstLevel[level1Code]'>$firstLevel[level1_name]</option>";
 }
?>
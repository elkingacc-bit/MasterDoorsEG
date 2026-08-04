<?php
 date_default_timezone_set("Africa/Cairo");
include_once("../connection.php");
 $firstCode=$_POST['firstCode'];
 $sqlFirstLevelData="SELECT `level2Name`, `level2Code` FROM `accountantcode` WHERE `level1Code` = '$firstCode' AND `codeLen` = 3";
 $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("Login ERROR :01");
 echo "<option value=''>Choose</option>";  
 while($firstLevel=mysqli_fetch_assoc($queryFirstLevelData)){
  echo"<option value='$firstLevel[level2Code]'>$firstLevel[level2Name]</option>";
 }
?>
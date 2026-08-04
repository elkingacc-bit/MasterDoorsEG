<?php
 date_default_timezone_set("Africa/Cairo");
include_once("../connection.php");
 $firstCode=$_POST['firstCode'];
 $secundCode=$_POST['secoundCode'];
 $sqlThierdLevelData="SELECT `level3Name`,`level3Code` FROM `accountantcode` WHERE `level1Code` = '$firstCode' AND `level2Code` = '$secundCode'  AND `codeLen` = 6 ";
 $queryThierdLevelData=mysqli_query($link,$sqlThierdLevelData)or die("Login ERROR :01");
 echo "<option value=''>Choose</option>";  
 while($thierdLevel=mysqli_fetch_assoc($queryThierdLevelData)){
  echo"<option value='$thierdLevel[level3Code]'>$thierdLevel[level3Name]</option>";
 }
?>
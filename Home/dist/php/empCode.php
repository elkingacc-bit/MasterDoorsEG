<?php
 date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
 $sqlFirstLevelData="SELECT `userid`,`fullname` FROM `users`";
 $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
 echo "<option value=''>Choose</option>";  
 while($firstLevel=mysqli_fetch_assoc($queryFirstLevelData)){
  echo"<option value='$firstLevel[userid]'>$firstLevel[fullname]</option>";
 }
?>
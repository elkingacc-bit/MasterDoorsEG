<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 echo "<option value=''>Choose</option>";  
 $sqlEmpAdvance="SELECT `empId`, (sum(`recived`)-sum( `cashback`))as balance FROM `advance` WHERE `recevedRef` = 1 GROUP BY `empId`";
 $queryEmpAdvance=mysqli_query($link,$sqlEmpAdvance)or die("ERROR LOA_S:01");
 $advanceCount=mysqli_num_rows($queryEmpAdvance);
 if($advanceCount > 0){
  while($empData=mysqli_fetch_assoc($queryEmpAdvance)){
   $empCode=$empData['empId'];
   $sqlFirstLevelData="SELECT `userid`,`fullname` FROM `users` WHERE `userid` = $empCode";
   $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:02");
   $firstLevel=mysqli_fetch_assoc($queryFirstLevelData);
   echo"<option value='$firstLevel[userid]'>$firstLevel[fullname]</option>";
  }
 }
?>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 echo "<option value=''>Choose</option>";
 $sqlEmpAdvance="SELECT `empId`, (sum(`recived`)-sum( `cashback`))as balance FROM `advance` WHERE `recevedRef` = 2 GROUP BY `empId`";
 $queryEmpAdvance=mysqli_query($link,$sqlEmpAdvance)or die("ERROR LOA_S:01");
 $advanceCount=mysqli_num_rows($queryEmpAdvance);
 if($advanceCount > 0){
  while($empData=mysqli_fetch_assoc($queryEmpAdvance)){
   $empCode=$empData['empId'];
   $sqlFirstLevelData="SELECT `id`, `staffname` FROM `allstaff` WHERE `id` = $empCode";
   $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:02");
   $firstLevel=mysqli_fetch_assoc($queryFirstLevelData);
   echo"<option value='$firstLevel[id]'>$firstLevel[staffname]</option>";
  }
 }
?>
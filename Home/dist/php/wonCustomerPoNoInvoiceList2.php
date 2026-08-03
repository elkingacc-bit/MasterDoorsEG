<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $sqlPOData="SELECT `PoNum`,`jobidref` FROM `customerpo` 
 WHERE `jobidref` IN (SELECT `jobId` FROM `job` WHERE `offerStatus` = 'Won' AND `invoice` = 'No')
 AND `jobidref` IN (SELECT `jobRowId` FROM `custorderdeliver`)

 ";
 $queryPOlData=mysqli_query($link,$sqlPOData)or die("ERROR LOA_S:01");
 echo "<option value=''>Choose</option>";  
 while($project=mysqli_fetch_assoc($queryPOlData)){
  $jopId=$project['jobidref'];
  $sqlProjectName="SELECT `projectName` FROM `job` WHERE `jobId` = $jopId";
  $quaryProjectName=mysqli_query($link,$sqlProjectName)or die("ERROR LOA_S:01");
  $projectName=mysqli_fetch_assoc($quaryProjectName);
  echo "<option value='$project[jobidref]' title='$project[PoNum]'>$projectName[projectName]</option>";
 }
?>
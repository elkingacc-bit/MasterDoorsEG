<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $sqlPOData="SELECT `poId`,`custCode`,`PoNum`,`jobidref` FROM `customerpo` WHERE `jobidref` IN (SELECT `jobId` FROM `job` WHERE `offerStatus` = 'Won');";
 $queryPOlData=mysqli_query($link,$sqlPOData)or die("ERROR LOA_S:01");
 echo"
  <option value=''>Choose</option>
  <option class='btn btn-success' value='0'>General</option>
 ";  
 while($project=mysqli_fetch_assoc($queryPOlData)){
  $jopId=$project['jobidref'];
  $sqlProjectName="SELECT `projectName` FROM `job` WHERE `jobId` = $jopId";
  $quaryProjectName=mysqli_query($link,$sqlProjectName)or die("ERROR LOA_S:01");
  $projectName=mysqli_fetch_assoc($quaryProjectName);

    $custCode=$project['custCode'];
  $sqlCustomerName="SELECT `customername` FROM `customers` WHERE `customercode` = $custCode";
  $quaryCustomerName=mysqli_query($link,$sqlCustomerName)or die("ERROR LOA_S:01");
  $customerName=mysqli_fetch_assoc($quaryCustomerName);

  
  echo "<option value='$project[poId]' title='$project[PoNum]'>$projectName[projectName] / ($customerName[customername])</option>";
 }
?>
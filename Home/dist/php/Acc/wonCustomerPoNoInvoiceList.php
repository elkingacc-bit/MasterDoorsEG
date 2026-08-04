<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $sqlPOData="SELECT `PoNum`,`custCode`,`jobidref` FROM `customerpo` WHERE `jobidref` IN (SELECT `jobId` FROM `job` WHERE `offerStatus` = 'Won' AND `invoice` = 'No' AND `custCode` = `customer`) ORDER BY `jobidref`;";
 $queryPOlData=mysqli_query($link,$sqlPOData)or die("ERROR LOA_S:01");
 echo "<option value=''>Choose</option>";  
 while($project=mysqli_fetch_assoc($queryPOlData)){
  $jopId=$project['jobidref'];
  $custCode=$project['custCode'];
  $sqlCustomerName="SELECT `customername` FROM `customers` WHERE `customercode` = $custCode";
  $quaryCustomerName=mysqli_query($link,$sqlCustomerName)or die("ERROR LOA_S:01");
  $customerName=mysqli_fetch_assoc($quaryCustomerName);
  $sqlProjectName="SELECT `projectName` FROM `job` WHERE `jobId` = $jopId ";
  $quaryProjectName=mysqli_query($link,$sqlProjectName)or die("ERROR LOA_S:01");
  $projectName=mysqli_fetch_assoc($quaryProjectName);
  
  $safeProjectName = htmlspecialchars($projectName['projectName'], ENT_QUOTES);
  $safeCustomerName = htmlspecialchars($customerName['customername'], ENT_QUOTES);
  echo "<option data-value='$jopId' value='$safeProjectName'>/ ($safeCustomerName)";
 
 }
?>
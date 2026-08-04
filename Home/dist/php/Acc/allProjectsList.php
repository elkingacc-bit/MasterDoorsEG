<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $sqlPOData="SELECT `poId`,`PoNum`,`custCode`,`jobidref`,`projectName` FROM `customerpo`, `job`
 WHERE `customerpo`.`jobidref` = `job`.`jobId`  AND `job`.`jobref` = 3";
 $queryPOlData=mysqli_query($link,$sqlPOData)or die("ERROR LOA_S:01");
 echo "<option value=''>Choose</option>";
 while($project=mysqli_fetch_assoc($queryPOlData)){
  $customerCode=$project['custCode'];
  $sqlCheckCustN="SELECT `customername` FROM `customers` WHERE `customercode` = $customerCode";
  $queryCheckCustN=mysqli_query($link,$sqlCheckCustN)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
  $customerData=mysqli_fetch_assoc($queryCheckCustN);    
  $account= $customerData['customername'];
  echo "<option value='$project[poId]'>$project[projectName] ($account) </option>";
 }
 ?>
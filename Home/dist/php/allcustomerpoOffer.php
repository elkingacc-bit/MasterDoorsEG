<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 if(!empty($_SESSION['username'])){
  echo "<option value=''>Choose</option>";
  $sqlGetAllPO="SELECT `poId`,`PoNum`,`jobidref`  FROM `customerpo` WHERE `poId` NOT IN (SELECT `poId` FROM `purchasesorder`)  AND `orderType` != 'Maintenance'";
  $queryGetAllPO=mysqli_query($link,$sqlGetAllPO)or die("ERROR :01-AU_AU_S".mysqli_error($link));
  while($resGetAllPO= mysqli_fetch_assoc($queryGetAllPO)){
     $jopId=$resGetAllPO['jobidref'];






$customerPo=$resGetAllPO['poId'];
 $sqlSuplierOrder="SELECT `SuppCode` FROM `supplierorder` WHERE `custPOId` = $customerPo";
 $querySuplierOrder=mysqli_query($link,$sqlSuplierOrder)or die("ERROR_SNSC : 01");
 if(mysqli_num_rows($querySuplierOrder) > 0){
  $getSuplierOrder=mysqli_fetch_assoc($querySuplierOrder);
  $supplier=$getSuplierOrder['SuppCode'];
  $sqlGetSupplier="SELECT `suppliername`, `suppliercode` FROM `allsuppliers` WHERE `suppliercode` = $supplier";
  $queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :01-AU_AU_S".mysqli_error($link));
  $resGetSupplier = mysqli_fetch_assoc($queryGetSupplier);
  $supName= $resGetSupplier['suppliername'];
}




  $sqlProjectName="SELECT `projectName` FROM `job` WHERE `jobId` = $jopId";
  $quaryProjectName=mysqli_query($link,$sqlProjectName)or die("ERROR LOA_S:01");
  $projectName=mysqli_fetch_assoc($quaryProjectName);



  echo "<option value='$resGetAllPO[poId]' title='$resGetAllPO[PoNum]'>$projectName[projectName] / $supName</option>";

  }
 }

?>
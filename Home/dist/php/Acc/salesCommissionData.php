<table  class="table table-bordered text-center">
 <thead class="bg-primary text-center">
  <th>#</th>
  <th>Name</th>
  <th>Po Value</th>
  <th>Commission</th>
  <th>Paied</th>
  <th>Valid</th>
  <th>Progress</th>
 </thead>
 <tbody>
  <?php
   include_once("../authCheck.php");
   date_default_timezone_set("Africa/Cairo");
   include_once("../connection.php");
   $num=0;
   $sqlTotalPO="SELECT `salesman`,sum(`offerValue`) as poValue,sum(`offerValue`*`Commotion`) as test FROM `job` WHERE `jobref` = 3 AND `offerStatus` = 'Won' Group By `salesman`";
   $queryTotalPO=mysqli_query($link,$sqlTotalPO);
   while($TotalPO=mysqli_fetch_assoc($queryTotalPO)){
    $userCode=(int)$TotalPO['salesman'];
    if(empty($userCode)){ continue; }
    $sqlGetSales="SELECT `userid`,`fullname` FROM `users` WHERE `codeid` = $userCode";
    $queryGetSales=mysqli_query($link,$sqlGetSales)or die("ERROR :01-AU_AU_S");
    $resGetSales= mysqli_fetch_assoc($queryGetSales);
    if(!$resGetSales || empty($resGetSales['userid'])){ continue; }
    $userId=(int)$resGetSales['userid'];
    $sqlStatment="SELECT sum(`withdrawal`) as amountPaid FROM `cash_transaction` WHERE `empCode` = $userId  AND `account`= 312105100001";
    $queryStatment=mysqli_query($link,$sqlStatment);
    $statment=mysqli_fetch_assoc($queryStatment);
    $validComm=($TotalPO['test'] - $statment['amountPaid']);
    $receiveProgress=($TotalPO['test'] != 0) ? ($validComm / $TotalPO['test'])*100 : 0;
    if($receiveProgress  == 0 ){
     $receiveProgressA = "<i class='fas fa-check'></i>";
    }
    else if($receiveProgress  <= 25 ){
     $receiveProgressA = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-success' style='width: $receiveProgress%'></div></div>";
    }
    else if($receiveProgress  <= 50 ){
     $receiveProgressA = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-info' style='width: $receiveProgress%'></div></div>";
    }
    else if($receiveProgress  <= 75 ){
     $receiveProgressA = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-warning' style='width: $receiveProgress%'></div></div>";
    }
    else if($receiveProgress  <= 100 ){
     $receiveProgressA = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-danger' style='width: $receiveProgress%'></div></div>";
    }
    $num++;    
    echo"<tr>
     <td>$num</td>
     <td><button type='button' class='btn btn-link showData' value='$userId'>$resGetSales[fullname]</button></td>
     <td>".number_format(($TotalPO['poValue']), 2)."</td>
     <td>".number_format(($TotalPO['test']), 2)."</td>
     <td>".number_format(($statment['amountPaid']), 2)."</td>
     <td>".number_format(($validComm), 2)."</td>
     <td>$receiveProgressA</td>
    </tr>";
   }
   $sqlTotalPO2="SELECT sum(`poVal`) as poValue , sum(`poVal` * `InstallationComsion`) as test2 FROM `customerpo` WHERE  `InstallationComsion` > 0 ";  
   $queryTotalPO2=mysqli_query($link,$sqlTotalPO2);
   $TotalPO2=mysqli_fetch_assoc($queryTotalPO2);
   $sqlGetInstallation="SELECT `fullname` FROM `users` WHERE `codeid` = 711101611";
   $queryGetInstallation=mysqli_query($link,$sqlGetInstallation)or die("ERROR :01-AU_AU_S");
   $resGetInstallation= mysqli_fetch_assoc($queryGetInstallation);  
   $sqlInstallationStatment="SELECT sum(`withdrawal`) as amountPaid FROM `cash_transaction` WHERE `empCode` = 22  AND `account`= 312105100001";  
   $queryInstallationStatment=mysqli_query($link,$sqlInstallationStatment);
   $statmentInstallation=mysqli_fetch_assoc($queryInstallationStatment);
   $validComm2=($TotalPO2['test2'] - $statmentInstallation['amountPaid']);
   $supBrog2=($TotalPO2['test2'] != 0) ? ($validComm2 / $TotalPO2['test2'])*100 : 0;
   if($supBrog2  == 0 ){
    $receiveProgressB = "<i class='fas fa-check'></i>";
   }
   else if($supBrog2  <= 25 ){
    $receiveProgressB = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-success' style='width: $supBrog2%'></div></div>";
   }
   else if($supBrog2  <= 50 ){
    $receiveProgressB = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-info' style='width: $supBrog2%'></div></div>";
   }
   else if($supBrog2  <= 75 ){
    $receiveProgressB = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-warning' style='width: $supBrog2%'></div></div>";
   }
   else if($supBrog2  <= 100 ){
    $receiveProgressB = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-danger' style='width: $supBrog2%'></div></div>";
   }
   $num++;
   echo"<tr>
    <td>$num</td>
    <td><button type='button' class='btn btn-link showData' value='22'>$resGetInstallation[fullname]</button></td>
    <td>".number_format(($TotalPO2['poValue']), 2)."</td>
    <td>".number_format(($TotalPO2['test2']), 2)."</td>
    <td>".number_format(($statmentInstallation['amountPaid']), 2)."</td>
    <td>".number_format(($validComm2), 2)."</td>
    <td>$receiveProgressB</td>
   </tr>";
  ?>
 </tbody>
</table>
<script>
 $(document).ready(function(){
  $(".showData").click(function(){
   var rowId = $(this).val();
   $.ajax({
    url:'dist/php/Acc/getSalesCommissionData.php',
    type:"POST",
    data:{orderId:rowId},
    success: function(getSuuplierOrderData){
     $(".allSalesCommission").html('');
     $(".allSalesCommission").html(getSuuplierOrderData);
     
    }
   });
   return false;
  });
 });
</script>
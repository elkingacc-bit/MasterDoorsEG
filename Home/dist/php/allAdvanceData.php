<div class="table-responsive-lg">
 <h3><p>Advance For Employee</p></h3>
 <table class='table table-sm table-bordered table-striped w-100'>
  <thead class='bg-primary text-center'>
   <th class="col-2">Employee</th>
   <th class="col-2">Last Payment</th>
   <th class="col-2">Payment</th>
   <th class="col-2">Repayment</th>
   <th class="col-2">Balance</th>
   <th></th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $sqlEmployee="SELECT `userid`,`fullname` FROM `users`";
    $queryEmployee=mysqli_query($link,$sqlEmployee)or die("ERROR LOA_S:01");
    while($employeeData=mysqli_fetch_assoc($queryEmployee)){
     $empId=$employeeData['userid'];
     $empName=$employeeData['fullname'];
     //Last Advance
     $sqlAdvanceLast="SELECT `advanceDate` FROM `advance` WHERE `recevedRef` = 1 AND `empId` = $empId AND `recived` > 0 ORDER BY `advanceDate` DESC LIMIT 1";  
     $queryAdvanceLast=mysqli_query($link,$sqlAdvanceLast);
     if($advanceLast=mysqli_fetch_assoc($queryAdvanceLast)){
      $lastDate=$advanceLast['advanceDate'];
     }
     $sqlAdvanceStatment="SELECT sum(`recived`) as withdrow , sum(`cashback`) as returned FROM `advance` WHERE `recevedRef` = 1 AND `empId` = $empId";  
     $queryAdvanceStatment=mysqli_query($link,$sqlAdvanceStatment);
     if($advanceStatment=mysqli_fetch_assoc($queryAdvanceStatment)){
      $advanceBalance=($advanceStatment['withdrow'] - $advanceStatment['returned']);
      if($advanceStatment['withdrow'] > 0 Or $advanceStatment['returned'] > 0){
       echo"<tr>
        <td>$empName</td>
        <td>$lastDate</td>
        <td>".number_format(($advanceStatment['withdrow']), 2)."</td>
        <td>".number_format(($advanceStatment['returned']), 2)."</td>
        <td>".number_format(($advanceBalance), 2)."</td>
        <td><button class='btn btn-info empValue w-100' value='$empId'><i class='fas fa-info'></i></button></td>
       </tr>";                
      }
     }
    }
   ?>
  </tbody>
 </table>
 <h3><p>Advance For Staff</p></h3>
 <table class='table table-sm table-bordered table-striped w-100'>
  <thead class='bg-primary text-center'>
   <th class="col-2">Staff</th>
   <th class="col-2">Last Payment</th>
   <th class="col-2">Payment</th>
   <th class="col-2">Repayment</th>
   <th class="col-2">Balance</th>
   <th></th>
  </thead>
  <tbody>
   <?php
    $sqlWorker="SELECT `id`, `staffname` FROM `allstaff`";
    $queryWorker=mysqli_query($link,$sqlWorker)or die("ERROR LOA_S:01");
    while($workerData=mysqli_fetch_assoc($queryWorker)){
     $workId=$workerData['id'];
     $workName=$workerData['staffname'];
     //Last Advance
     $sqlAdvanceLastw="SELECT `advanceDate` FROM `advance` WHERE `recevedRef` = 2 AND `empId` = $workId AND `recived` > 0 ORDER BY `advanceDate` DESC LIMIT 1";  
     $queryAdvanceLastw=mysqli_query($link,$sqlAdvanceLastw);
     if($advanceLastw=mysqli_fetch_assoc($queryAdvanceLastw)){
      $lastDatew=$advanceLastw['advanceDate'];
     }
     $sqlAdvanceStatmentw="SELECT sum(`recived`) as withdrow , sum(`cashback`) as returned FROM `advance` WHERE `recevedRef` = 2 AND `empId` = $workId";  
     $queryAdvanceStatmentw=mysqli_query($link,$sqlAdvanceStatmentw);
     if($advanceStatmentw=mysqli_fetch_assoc($queryAdvanceStatmentw)){
      $advanceBalancew=($advanceStatmentw['withdrow'] - $advanceStatmentw['returned']);
      if($advanceStatmentw['withdrow'] > 0 Or $advanceStatmentw['returned'] > 0){
       echo"<tr>
        <td>$workName</td>
        <td>$lastDatew</td>
        <td>".number_format(($advanceStatmentw['withdrow']), 2)."</td>
        <td>".number_format(($advanceStatmentw['returned']), 2)."</td>
        <td>".number_format(($advanceBalancew), 2)."</td>
        <td><button class='btn btn-info workerValue w-100' value='$workId'><i class='fas fa-info'></i></button></td>
       </tr>";                
      }
     }
    }
   ?>
  </tbody>
 </table>
</div>
<!-- Modal -->
<div class="modal fade" id="advanceModal" aria-hidden="true" aria-labelledby="advanceModalData" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title" id="advanceModalData"></h5>
    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   </div>
   <div class="modal-body advanceData"></div>
  </div>
 </div>
</div>
<script type="text/javascript">
 $(".close").click(function(){
  $("#advanceModal").modal('toggle');
 });
 // Invoice Items
 $(".empValue").click(function(){
  var invId = $(this).val();
  $.ajax({
   url:'dist/php/advanceSummaryData.php',
   type:"POST",
   data:{empId:invId},
   success: function(getAdvanceSummaryData){
    $("#advanceModalData").html('');
    $("#advanceModalData").html('Advance Employee Details');
    $(".advanceData").html('');
    $(".advanceData").html(getAdvanceSummaryData);
    $("#advanceModal").modal('show');  
   }
  });
  return false;
 });
 $(".workerValue").click(function(){
  var invId = $(this).val();
  $.ajax({
   url:'dist/php/advanceWorkerData.php',
   type:"POST",
   data:{empId:invId},
   success: function(getAdvanceWorkerData){
    $("#advanceModalData").html('');
    $("#advanceModalData").html('Advance Worker Details');
    $(".advanceData").html('');
    $(".advanceData").html(getAdvanceWorkerData);
    $("#advanceModal").modal('show');  
   }
  });
  return false;
 });
</script>
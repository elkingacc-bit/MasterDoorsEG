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

    // Pre-fetch per-employee advance aggregates in 2 queries instead of 2 per employee
    $advanceLastDates1=[];
    $sqlAdvanceLastAll1="SELECT `empId`, MAX(`advanceDate`) as lastDate FROM `advance` WHERE `recevedRef` = 1 AND `recived` > 0 GROUP BY `empId`";
    $queryAdvanceLastAll1=mysqli_query($link,$sqlAdvanceLastAll1);
    while($row=mysqli_fetch_assoc($queryAdvanceLastAll1)){
     $advanceLastDates1[$row['empId']]=$row['lastDate'];
    }
    $advanceStatments1=[];
    $sqlAdvanceStatmentAll1="SELECT `empId`, sum(`recived`) as withdrow, sum(`cashback`) as returned FROM `advance` WHERE `recevedRef` = 1 GROUP BY `empId`";
    $queryAdvanceStatmentAll1=mysqli_query($link,$sqlAdvanceStatmentAll1);
    while($row=mysqli_fetch_assoc($queryAdvanceStatmentAll1)){
     $advanceStatments1[$row['empId']]=$row;
    }

    $sqlEmployee="SELECT `userid`,`fullname` FROM `users`";
    $queryEmployee=mysqli_query($link,$sqlEmployee)or die("ERROR LOA_S:01");
    while($employeeData=mysqli_fetch_assoc($queryEmployee)){
     $empId=$employeeData['userid'];
     $empName=$employeeData['fullname'];
     //Last Advance
     $lastDate=$advanceLastDates1[$empId] ?? null;
     $advanceStatment=$advanceStatments1[$empId] ?? null;
     if($advanceStatment){
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
    // Pre-fetch per-staff advance aggregates in 2 queries instead of 2 per staff member
    $advanceLastDates2=[];
    $sqlAdvanceLastAll2="SELECT `empId`, MAX(`advanceDate`) as lastDate FROM `advance` WHERE `recevedRef` = 2 AND `recived` > 0 GROUP BY `empId`";
    $queryAdvanceLastAll2=mysqli_query($link,$sqlAdvanceLastAll2);
    while($row=mysqli_fetch_assoc($queryAdvanceLastAll2)){
     $advanceLastDates2[$row['empId']]=$row['lastDate'];
    }
    $advanceStatments2=[];
    $sqlAdvanceStatmentAll2="SELECT `empId`, sum(`recived`) as withdrow, sum(`cashback`) as returned FROM `advance` WHERE `recevedRef` = 2 GROUP BY `empId`";
    $queryAdvanceStatmentAll2=mysqli_query($link,$sqlAdvanceStatmentAll2);
    while($row=mysqli_fetch_assoc($queryAdvanceStatmentAll2)){
     $advanceStatments2[$row['empId']]=$row;
    }

    $sqlWorker="SELECT `id`, `staffname` FROM `allstaff`";
    $queryWorker=mysqli_query($link,$sqlWorker)or die("ERROR LOA_S:01");
    while($workerData=mysqli_fetch_assoc($queryWorker)){
     $workId=$workerData['id'];
     $workName=$workerData['staffname'];
     //Last Advance
     $lastDatew=$advanceLastDates2[$workId] ?? null;
     $advanceStatmentw=$advanceStatments2[$workId] ?? null;
     if($advanceStatmentw){
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
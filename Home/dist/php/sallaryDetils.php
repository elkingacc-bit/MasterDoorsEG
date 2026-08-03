<div class="table-responsive-lg text-center">
 <table class='table table-sm table-bordered table-striped'>
  <thead class='bg-info'>
   <th>Name</th>
   <th>Position</th>
   <th>Works Day</th>
   <th>Day Value</th>
   <th>Day Amount</th>
   <th>Reword</th>
   <th>pelanty</th>
   <th>Net</th>
  </thead>
  <tbody>
   <?php
    @session_start();  
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $stafeId=$_POST['stafId'];
    $payment=0;
    $installments=0;
     // Get Sales Invoice From Sales Invoice
    $sqlSalesInoice="SELECT `staffRId`,count(`attendDate`) as dayWork, sum(`penalty`) as nag, sum(`Reward`) as plu 
    FROM `outsidemanpower` WHERE `paymentRef` = 0 AND `staffRId` = $stafeId group by `staffRId`";
    $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 01");
    While($salesInoiceData=mysqli_fetch_assoc($querySalesInoice)){
     $sqlStaff="SELECT `staffname`,`staffposition`,`dayval` FROM `allstaff` WHERE `id` = $stafeId";
     $queryStaff=mysqli_query($link,$sqlStaff)or die("ERROR_SNSC : 01");
     $staffData=mysqli_fetch_assoc($queryStaff);
     $workAmount=($salesInoiceData['dayWork'] * $staffData['dayval'] );
     $rewordAmount=($salesInoiceData['plu'] * $staffData['dayval'] );
     $pelantyAmount=($salesInoiceData['nag'] * $staffData['dayval'] );
     $net=(($workAmount + $rewordAmount ) - $pelantyAmount);
     echo"<tr>
      <td>$staffData[staffname]</td>
      <td>$staffData[staffposition]</td>
      <td>$salesInoiceData[dayWork]</td>
      <td>$staffData[dayval]</td>
      <td>$workAmount</td> 
      <td>$rewordAmount</td>
      <td>$pelantyAmount</td>
      <td>$net</td>         
     </tr>";  
    }
    $sqlAdvanceStatment="SELECT `empId`,sum(`recived`) as adv ,sum(`cashback`) as cas, `installment` 
    FROM `advance` WHERE `recevedRef` = 2 AND `empId`= $stafeId GROUP BY `empId`,`installment` ";  
    $queryAdvanceStatment=mysqli_query($link,$sqlAdvanceStatment);
    $cheakAdvance=mysqli_num_rows($queryAdvanceStatment);
    if($cheakAdvance > 0){
     $advanceStatment=mysqli_fetch_assoc($queryAdvanceStatment);
     $valid= ( $advanceStatment['adv'] - $advanceStatment['cas']);
     if($valid > 0){
      $installments=$advanceStatment['installment'];        
     }
     $netSallary=($net - $installments);
    } 
    else{
     $netSallary=$net;
    }
    echo "<tr>
     <td colspan='2'>Salary</td>
     <td>$net</td>
</tr>
<tr>
     <td colspan='2'>Installment</td>
     <td>$installments</td>
  </tr>
  <tr>
     <td colspan='2'>Net</td>
     <td>$netSallary</td>
    </tr>
  <tr>
     <td><button class='btn btn-success' id='saveSallary' value='$stafeId'>Pay</button></td>

          <td><button class='btn btn-primary' id='printSallary' value='$stafeId'>Print</button></td>
    </tr>";
   ?>
  </tbody>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  $("#saveSallary").click(function(){
   var stafRow = $(this).val();
   $.ajax({
    url:'dist/php/saveWithdrawalSallary.php',
    type:"POST",
    data:{stafId:stafRow},
    success: function(getSuuplierWithdrawalData){
     if(getSuuplierWithdrawalData == 1){
      $(".close").click();
      alert("Save Pay Sallary"); 
     } 
     else{
      alert(getSuuplierWithdrawalData);
      $(".close").click();
     }
     /*
     
     $(".data_display").html('');
     $(".m-0").html("All Project Sallary Data");
     $(".data_display").load("dist/html/withdrawprojectSallary.html");
     */
    }
   });
   return false;
  });
  $("#printSallary").click(function(){
   var stafRowId = $(this).val();
   $.ajax({
    url:'dist/php/printWithdrawalSallary.php',
    type:"POST",
    data:{stafId:stafRowId},
    success: function(printWithdrawalData){
     window.open(`dist/php/printWithdrawalSallary.php?stafId= <?php echo $stafeId ?>`, '_blank');
    }
   });
   return false;
  });
 });
</script>
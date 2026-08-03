<div class="table-responsive">
 <table class="table table-bordered table-striped tableCommissionData w-100 text-center">
  <thead class="bg-info text-center">
   <th>#</th>
   <th>Customer</th>
   <th>Project</th>
   <th>Po Value</th>
   <th>Commission</th>
   <th>Paied</th>
   <th>Valid</th>
   <th>Date</th>
   <th>Amount</th>
   <th>Payment</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $salesId=$_POST['orderId'];
    $num=0;
    $valid=0;
    echo "<input type='text' class='form-control' id='withdrawCommisionRecipient' value='$salesId' hidden>";
    $sqlGetSales="SELECT `codeid`,`fullname` FROM `users` WHERE `userid` = $salesId";
    $queryGetSales=mysqli_query($link,$sqlGetSales)or die("ERROR :01-AU_AU_S");
    $resGetSales= mysqli_fetch_assoc($queryGetSales);
    if( $salesId == 22){
     $sqlBasicData="SELECT `InstallationComsion`,`jobidref` FROM `customerpo` WHERE  `InstallationComsion` > 0";
     $queryBasicData=mysqli_query($link,$sqlBasicData)or die("ERROR :01-AU_AU_S".mysqli_error($link));
     while($resBasicData= mysqli_fetch_assoc($queryBasicData)){
        $num++;
      $sqlGetAllPO="SELECT `jobId`,`projectName`,`customer`,`offerValue` FROM `job` WHERE  `jobId` = $resBasicData[jobidref]";
      $queryGetAllPO=mysqli_query($link,$sqlGetAllPO)or die("ERROR :01-AU_AU_S".mysqli_error($link));
      $resGetAllPO= mysqli_fetch_assoc($queryGetAllPO);
      $sqlCheckCustN="SELECT `customername` FROM `customers` WHERE `customercode` =$resGetAllPO[customer]";
      $queryCheckCustN=mysqli_query($link,$sqlCheckCustN)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
      $resCheckCustN=mysqli_fetch_assoc($queryCheckCustN);
      $sqlStatment="SELECT sum(`withdrawal`) as payment  FROM `cash_transaction` WHERE `empCode` = $salesId  AND `account`= 312105100001 AND `poNum` = $resGetAllPO[jobId] ";  
      $queryStatment=mysqli_query($link,$sqlStatment);
      $statment=mysqli_fetch_assoc($queryStatment);
      $customerName=$resCheckCustN['customername'];
      $comission = ($resGetAllPO['offerValue'] * $resBasicData['InstallationComsion']);
      $paide =$statment['payment'];
      $valid=($comission - $paide);
      if($valid >= 10){
      echo "<input type='text' class='form-control' id='projectsId$num' value='$resGetAllPO[jobId]' hidden>";
      echo "<input value='$resGetSales[fullname] Commission From $resGetAllPO[projectName]' id='withdrawCommisionDiscrebtion$num' hidden>";
            echo "<input type='text' id='amountV$num' class='form-control' value='$valid' hidden>";
      echo"<tr>
       <td>$num</td>
       <td>$resCheckCustN[customername]</td>
       <td>$resGetAllPO[projectName]</td>
       <td>".number_format(($resGetAllPO['offerValue']), 2)."</td>
       <td>".number_format(($comission), 2)."</td>
       <td>".number_format(($paide), 2)."</td>
       <td>".number_format(($valid), 2)."</td>
       <td><input type='date' class='form-control' id='withdrawCommisionDate$num'></td>
       <td><input type='text' id='amount$num' class='form-control'></td>
       <td><button class='btn btn-success btn-lg btn-block saveCommisionWithdraw' value='$num'><i class='fa fa-check' aria-hidden='true'></i></button></td>
      </tr>";          
      }

     }
    }
    else{
     $sqlGetAllPO="SELECT `jobId`,`projectName`,`customer`,`Commotion`,`offerValue`,`salesman` FROM `job` 
     WHERE `jobref` = 3 AND `offerStatus` = 'Won' AND `salesman` = $resGetSales[codeid]";
     $queryGetAllPO=mysqli_query($link,$sqlGetAllPO)or die("ERROR :01-AU_AU_S".mysqli_error($link));
     while($resGetAllPO= mysqli_fetch_assoc($queryGetAllPO)){
      $sqlCheckCustN="SELECT `customername` FROM `customers` WHERE `customercode` =$resGetAllPO[customer]";
      $queryCheckCustN=mysqli_query($link,$sqlCheckCustN)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
      $resCheckCustN=mysqli_fetch_assoc($queryCheckCustN);
      $customerName=$resCheckCustN['customername'];
      $comission = ($resGetAllPO['offerValue'] * $resGetAllPO['Commotion']);
      $sqlStatment="SELECT sum(`withdrawal`) as payment  FROM `cash_transaction` WHERE `empCode` = $salesId  AND `account`= 312105100001 AND `poNum` = $resGetAllPO[jobId] ";  
      $queryStatment=mysqli_query($link,$sqlStatment);
      $statment=mysqli_fetch_assoc($queryStatment);
      $paide =$statment['payment'];
      $num++;
      echo "<input type='text' class='form-control' id='projectsId$num' value='$resGetAllPO[jobId]' hidden>";
      $valid=($comission - $paide);
      if($valid >= 10){
      echo "<input value='$resGetSales[fullname] Commission From $resGetAllPO[projectName]' id='withdrawCommisionDiscrebtion$num' hidden>";
      echo "<input type='text' id='amountV$num' class='form-control' value='$valid' hidden>";
      echo"<tr>
       <td>$num</td>
       <td>$resCheckCustN[customername]</td>
       <td>$resGetAllPO[projectName]</td>
       <td>".number_format(($resGetAllPO['offerValue']), 2)."</td>
       <td>".number_format(($comission), 2)."</td>
       <td>".number_format(($paide), 2)."</td>
       <td>".number_format(($valid), 2)."</td>
       <td><input type='date' class='form-control' id='withdrawCommisionDate$num'></td>
       <td><input type='text' id='amount$num' class='form-control'></td>
       <td><button class='btn btn-success btn-lg btn-block saveCommisionWithdraw' value='$num'><i class='fa fa-check' aria-hidden='true'></i></button></td>
      </tr>";         
      }

     }
    }

   ?>
  </tbody>
 </table>
</div>
<script>
 $(document).ready(function(){
  var table = $('.tableCommissionData').DataTable({
   fixedHeader: true,
    scrollY:'65vh',
   scrollX: true,
   scrollCollapse: true,
   order:[[0, "asc"]],
    paging: false, 
  });
  // Save
  $(".saveCommisionWithdraw").click(function(){
   var num =$(this).val();
   var accName = "312105100001";
   var recipient = $("#withdrawCommisionRecipient").val();
   var PONum = $('#projectsId'+num).val();
   var actionDate = $("#withdrawCommisionDate"+num).val();
   var discrebtion = $("#withdrawCommisionDiscrebtion"+num).val();
   var vailedAmount=$("#amountV"+num).val();
   var amount = $("#amount"+num).val();
   if( actionDate == ''){ alert ("Do'not Leave Date Blank.");}
   else if(amount == ''){ alert ("Do'not Leave Amount Blank.");}  
   else{
    if(vailedAmount >= amount ){
     $.ajax({ 
      url:'dist/php/saveNewProjectWithdraw.php',
      type:"POST",
      data:{fDate:actionDate,fCode:accName,frecipient:recipient,famount:amount,fdiscrebtion:discrebtion,poNumber:PONum},
      beforeSend:function(){
       $('#saveOtherExpensesWithdraw').prop('disabled', true);
      },
      success: function(saveWithdrawOtherTransaction){
       if(saveWithdrawOtherTransaction == 1){
        alert("Data Saved");
        var rowId = $("#withdrawCommisionRecipient").val();
        $.ajax({
         url:'dist/php/getAllSalesCommissionData.php',
         type:"POST",
         data:{orderId:rowId},
         success: function(getSalesCommissionData2){
          $(".commisionData").html('');
          $(".commisionData").html(getSalesCommissionData2); 
         }
        });
       }
       else{
        alert(saveWithdrawOtherTransaction);
       }
      }
     });
    }
    else{
     alert ("Can't Paid This Amount.");
     $("#amount"+num).val('');
    }
   } 
  });
 });
</script>
<div class="table-responsive-lg">
 <table class='table table-sm w-100 table-bordered table-striped'>
  <thead class='bg-info text-center'>
   <th>Customer</th>
   <th>Count Po</th>
   <th>Total Amount</th>
   <th>Total Collect</th>
   <th>Valid</th>
   </thead>
   <tbody>
    <?php
     include_once("authCheck.php");
     date_default_timezone_set("Africa/Cairo");
     include_once("connection.php");
     $customerId=(int)$_POST['custId'];
     //Customer Name
     $sqlGetCustName="SELECT `customername` FROM `customers` WHERE `customercode` = $customerId";
     $queryGetCustName=mysqli_query($link,$sqlGetCustName)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
     $resCustName=mysqli_fetch_assoc($queryGetCustName);
     /*--------------------- #{ New 2025-09-24 }# --------------------------*/
     // PO Count
     $sqlSalesInoice="SELECT `jobId`,`projectName`,`offerValue`,`invoice` FROM `job` WHERE  `customer` = $customerId AND `offerStatus` = 'Won' ORDER BY `startDate` ASC";
     $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 02");
     $jopCount=mysqli_num_rows($querySalesInoice);
     // Po Value
     $sqlCustomerPo="SELECT sum(`poVal`) as amount, sum(`POVat`) as vat FROM `customerpo` WHERE `custCode` = $customerId";
     $quaryCustomerPo=mysqli_query($link,$sqlCustomerPo)or die("ERROR_SNSC : 02");
     $resultCustomerPo=mysqli_fetch_assoc($quaryCustomerPo);
     $totalAmount = ($resultCustomerPo['amount'] + $resultCustomerPo['vat']);
     // Collect
     $sqlDwonpayment="SELECT sum(`income`) as collectAmount  FROM `cash_transaction` WHERE `account` = $customerId ";
     $quaryDwonpayment=mysqli_query($link,$sqlDwonpayment)or die("ERROR_SNSC : 02");
     $dwonpaymentData=mysqli_fetch_assoc($quaryDwonpayment);
     $totalCollect = $dwonpaymentData['collectAmount'];       
     // Valide
     $totalValid=($totalAmount - $totalCollect);
     echo"
      <td>$resCustName[customername]</td>
      <td>$jopCount</td>
      <td>".number_format(($totalAmount), 2)."</td>
      <td>".number_format(($totalCollect), 2)."</td>
      <td>".number_format(($totalValid), 2)."</td>
      <input type='number' class='form-control' id='validAccount' value='$totalValid' style='display: none;'>
      <input type='number' class='form-control' id='paymentAccountName' value='$customerId' style='display: none;'
      ";
   
    ?>
   </tbody>
  </table>
  <div class="paymentData" style="display: none;">
   <table class='table table-sm w-100 table-bordered table-striped'>
    <thead>
     <th>Date</th>
     <th>Treasury</th>
     <th>Amount</th>
     <th>Description</th>
    </thead>
    <tbody>
     <td><input type="date" class="form-control" id="paymentDate"></td>
     <td><select  class="form-control" id="paymentTrasuty"></select></td>
     <td><input type="number" class="form-control" id="paymentAmount"></td>
     <td><input type="text"  class="form-control" id="paymentDisc"> </td>
    </tbody>
   </table>
   <center>
    <table class="table table-sm w-75 table-borderless bankData" style="display: none;">
     <thead class="text-center">
      <th class="col-2">Due Date</th>
      <th class="col-3">Cheque Number</th>
     </thead>
     <tbody>
      <td><input type="date" id="paymentDueDate" class="form-control"></td>
      <td><input type="number" id="paymentCheaquNumber" class="form-control"></td>
     </tbody>
    </table>
    <button class="btn btn-success" id="paymentSaveData">Save</button>
   </center>
  </div>
 </div>
 <div class="testPayment"></div>

 <script type="text/javascript">
 $(document).ready(function(){

  var dueAmount = $("#validAccount").val();
  if(dueAmount >= 1){
   $(".paymentData").css('display','block');    
  }
  else{
   $(".paymentData").css('display','none'); 
  }
  $("#paymentTrasuty").load("dist/php/cashCode.php");
  $("#paymentTrasuty").change(function(){
   var cashType = $(this).val();
   var firstDigit = cashType.substring(0, 5);
   // If Choose Bank
   if(firstDigit == 11620){
    $(".bankData").css('display','block');
   }
   // If Choose Cash
   else{
    $(".bankData").css('display','none');
   }
  });
  $("#paymentSaveData").click(function(){
   var actionDate = $("#paymentDate").val();
   var accName = $("#paymentAccountName").val();
   var amount = $("#paymentAmount").val();
   var discrebtion = $("#paymentDisc").val();
   var cash = $("#paymentTrasuty").val();
   var chickNum = $("#paymentCheaquNumber").val();
   var dueDate = $("#paymentDueDate").val();
   if( actionDate == ''){
    alert ("Do'not Leave Date Blank.");
   }
   else if(amount == ''){
    alert ("Do'not Leave Amount Blank.");
   }  
   else if(discrebtion == ''){
    alert ("Do'not Leave Description Blank.");
   }
   else if(cash == ''){
    alert ("Do'not Leave Treasury Blank.");
   }
   else if(Number(amount) > Number(dueAmount)){
    alert ("Amount Over Than Statement");
   }
   else{
    $.ajax({ 
     url:'dist/php/saveCustomerCollecdData.php',
     type:"POST",
     data:{pDate:actionDate,pSupplier:accName,pAmount:amount,pDiscrebtion:discrebtion,typeCash:cash,numCheak:chickNum,cheakDate:dueDate},
     beforeSend:function(){
      //$('#paymentSaveData').prop('disabled', true);
     },
     success: function(saveWithdrawSupplierPayment){
      if(saveWithdrawSupplierPayment == 1){
       alert("Data Saved ");
       //$('.card-text').load('dist/html/newPaymentSupplierTotal.html');
      }
      //else if(saveWithdrawSupplierPayment == 2){alert("Balance Not Avaliable");}
      else{
        alert(saveWithdrawSupplierPayment);
      }
     
     }
    });
   }
  });
  return false;
 });
</script>
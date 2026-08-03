<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $invId=$_POST['invNum'];
 $sqlSalesTax="SELECT `salesInvoiceNumber`,`jopRef`,`salesInvoiceDate`,`customerCode`,`salesInvoiceType`,`salesInvoiceSupTotal` FROM `salesInvoice` 
 WHERE  `salesInvoiceId` = $invId";
 $querySalesTax=mysqli_query($link,$sqlSalesTax)or die("ERROR_SNSC : 02");
 $taxData=mysqli_fetch_assoc($querySalesTax);
 $invType=$taxData['salesInvoiceType'];
 $sqlCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $taxData[customerCode]";
 $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
 $customerData=mysqli_fetch_assoc($quaryCustomer);
 echo "<center>
  <div class='table-responsive-lg'>
   <table class='table table-sm table-borderless w-75'>
    <thead class='text-center'>
     <th>Invoice Number</th>
     <th>Customer</th>
     <th>Collect Type</th>
     <th>Date</th>
     <th>Amount</th>
    </thead>
    <tbody>
     <td><input class='form-control' id='invNum' value='$taxData[salesInvoiceNumber]' readonly></td>     
     <td><select class='form-control' id='salesAccountName' readonly> <option value='$taxData[customerCode]'>$customerData[customername]</option></select></td>
     <td><select class='form-control' id='collectRef'></select></td>
     <td><input type='date' class='form-control' id='collectDate'></td> 
     <td><input type='number' id='collectValue' class='text-center form-control'></td>
    </tbody>
   </table>
   <table class='table table-sm w-75 table-borderless bankData' style='display: none;'>
      <thead class='text-center'>
       <th class='col-2'>Maturity Date</th>
       <th class='col-3'>Cheque Number</th>
      </thead>
      <tbody>
       <td><input type='date' id='dueDate' class='form-control'></td>
       <td><input type='number' id='cheaquNumber' class='form-control'></td>
      </tbody>
     </table>
     <br>
     <button class='btn btn-info' id='saveCollectInvoice' value='$invId'>Save</button>
  </div>
 </center>";
?>
<script type="text/javascript">
 $("#collectRef").load("dist/php/cashCode.php");
 $("#collectRef").change(function(){
  var cashType = $(this).val();
  var firstDigit = cashType.substring(0, 5);
  if(firstDigit == 11620){
   $(".bankData").css('display','block');
  }
  else{
   $(".bankData").css('display','none');
  }
 });
 $("#saveCollectInvoice").click(function(){
  var colletRef = $("#collectRef").val();
  var colletDate = $("#collectDate").val();
  var colletAmount = $("#collectValue").val();
  var colletInvNum = $(this).val();
  var colletCustomer = $("#salesAccountName").val();
  var chickNum = $("#cheaquNumber").val();
  var dueDate = $("#dueDate").val();
  if(colletRef == ''){
   alert('Choose The Collect By');
  }
  else if(colletDate == ''){
   alert('Type The Date');
  }
  else if(colletAmount == ''){
   alert('Type The Amount');
  }
  else{
   $.ajax({
    url:'dist/php/saveNewCollectSalesInvoice.php',
    type:"POST",
    data:{fRef:colletRef,fDate:colletDate,fAmount:colletAmount,fInvNum:colletInvNum,fCustomer:colletCustomer,numCheak:chickNum,cheakDate:dueDate},
    success: function(doneHoldingTax){   
     $("#salesModal").modal('toggle');
     alert(doneHoldingTax);
     $(".data_display").load("dist/html/newSalesInvoiceCollect.html");
    }
   });
  }
  return false;
 });
</script>
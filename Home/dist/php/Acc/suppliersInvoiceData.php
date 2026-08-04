<div class="table-responsive-lg">
 <table class='table table-sm w-100 table-bordered table-striped'>
  <thead class='bg-info text-center'>
   <th>Supplier</th>
   <th>Count INV</th>
   <th>Total Amount</th>
   <th>Total Paid</th>
   <th>Valid</th>
   </thead>
   <tbody>
    <?php
     include_once("../authCheck.php");
     date_default_timezone_set("Africa/Cairo");
     include_once("../connection.php");
     $supplierId=(int)$_POST['supId'];
     //
     $sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $supplierId";
     $queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :01-AU_AU_S".mysqli_error($link));
     $resGetSupplier = mysqli_fetch_assoc($queryGetSupplier);
     $supplierName=$resGetSupplier['suppliername'];
     // Paid Invoice  
     $sqlSupplierStockInv="SELECT count(`suppliersInvoiceNumber`) as invNum,sum(`suppliersInvoiceTotal`) as tAmount, sum(`paidAmount`) as tPaid 
     FROM `supplierInvoice` WHERE `suppliersInvoiceTotal` != `paidAmount` AND `supplierCode` = $supplierId";
     $querySupplierStockInv=mysqli_query($link,$sqlSupplierStockInv)or die("ERROR_SNSC : 01");
     if(mysqli_num_rows($querySupplierStockInv) > 0 ){
      $supplierStockInv=mysqli_fetch_assoc($querySupplierStockInv);
      $validAccount = ($supplierStockInv['tAmount'] - $supplierStockInv['tPaid']);
      echo"
       <td>$supplierName</td>
   	   <td>$supplierStockInv[invNum]</td>
   	   <td>".number_format(($supplierStockInv['tAmount']), 2)."</td>
   	   <td>".number_format(($supplierStockInv['tPaid']), 2)."</td>
   	   <td>".number_format(($validAccount), 2)."</td>
       <input type='number' id='validAccount' value='$validAccount' style='display:none;'>
       <input type='number' id='paymentAccountName' value='$supplierId' style='display:none;'>
      ";  
     }
    ?>
  </tbody>
 </table>
 <div class="paymentData">
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
/*
  var dueAmount = $("#validAccount").val();
  if(dueAmount >= 1){
   $(".paymentData").css('display','block');	
  }
  else{
   $(".paymentData").css('display','none');	
  }
*/

  $("#paymentTrasuty").load("dist/php/Acc/cashCode.php");
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
  /* else if(Number(amount) > Number(dueAmount)){
    alert ("Amount Over Than Statement");
   }
  */
   else{
    $.ajax({ 
     url:'dist/php/Acc/saveNewPymentToSupplier.php',
     type:"POST",
     data:{pDate:actionDate,pSupplier:accName,pAmount:amount,pDiscrebtion:discrebtion,typeCash:cash,numCheak:chickNum,cheakDate:dueDate},
     beforeSend:function(){
      //$('#paymentSaveData').prop('disabled', true);
     },
     success: function(saveWithdrawSupplierPayment){
      $('.testPayment').html(saveWithdrawSupplierPayment);
      //if(saveWithdrawSupplierPayment == 1){alert("Data Saved ");$('.card-text').load('dist/html/Acc/newPaymentSupplierTotal.html');}
      //else if(saveWithdrawSupplierPayment == 2){alert("Balance Not Avaliable");}
      //else{alert(saveWithdrawSupplierPayment);}
     
     }
    });
   }
  });
  return false;
 });
</script>
<style type="text/css">
 .errColor{
  background-color: hotpink;
 }
 .successColor{
    background-color: palegreen;
 }
</style>
<div hidden>
 <?php
  include_once("../authCheck.php");
  date_default_timezone_set("Africa/Cairo");
  include_once("../connection.php");
  $orderId=(int)$_POST['invId'];

  // Get Supplier Invoice Date
  $sqlSupplierInvoice="SELECT `suppliersInvoiceId`,`suppliersInvoiceNumber`,`suppliersInvoiceDate`,`supplierCode`,`suppliersInvoiceType`,`suppliersInvoiceSupTotal`,
  `suppliersInvoiceDiscount`,`suppliersInvoiceVat`,`suppliersInvoiceTotal`,`paidAmount`,`paidType`,`paiedStuts` 
  FROM `supplierInvoice` 
  WHERE `suppliersInvoiceId` = $orderId ";
  // Get Supplier Name
  $querySupplierInvoiceData=mysqli_query($link,$sqlSupplierInvoice)or die("ERROR_SNSC : 01");
  $supplierInvoiceData=mysqli_fetch_assoc($querySupplierInvoiceData);
  $sqlSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $supplierInvoiceData[supplierCode]";
  $querySupplierData=mysqli_query($link,$sqlSupplier)or die("ERROR_SNSC : 01");
  $supplierData=mysqli_fetch_assoc($querySupplierData);
  $invoiceRemining=($supplierInvoiceData['suppliersInvoiceTotal']-$supplierInvoiceData['paidAmount']);
  echo"
   <input value='$supplierInvoiceData[paidAmount]' id='lPaied' hidden>
   <input value='$supplierInvoiceData[suppliersInvoiceId]' id='invoiceNumber' hidden>
   <input value='$invoiceRemining' id='validAmount' hidden>
   <h4 class='text-center'>$supplierData[suppliername] Invoice <span><b style='color: red;'>$supplierInvoiceData[suppliersInvoiceNumber]</b></span>  Valid</h4>
   <table class='table table-sm table-bordered table-striped'>
    <thead class='bg-primary'>
     <th>Date</th>
     <th>Amount</th>
     <th>Discount</th>
     <th>Vat</th>
     <th>Total</th>
     <th>Paid Amount</th>
     <th>Valid Amount</th>
    </thead>
    <tbody>
     <td>$supplierInvoiceData[suppliersInvoiceDate]</td>
     <td>".number_format(($supplierInvoiceData['suppliersInvoiceSupTotal']), 2)."</td>
	 <td>".number_format(($supplierInvoiceData['suppliersInvoiceDiscount']), 2)."</td>
 	 <td>".number_format(($supplierInvoiceData['suppliersInvoiceVat']), 2)."</td>
	 <td><button class='btn btn-link' title='Invoice Detils' id='showDetils'>".number_format(($supplierInvoiceData['suppliersInvoiceTotal']), 2)."</button></td>
	 <td><button class='btn btn-link' title='Paied Detils' id='showPaid'>".number_format(($supplierInvoiceData['paidAmount']), 2)."</button></td>
     <td>".number_format(($invoiceRemining), 2)."</td>
    </tbody>
   </table>
  ";
 ?>
</div>
  <select class="form-control" id="withdrawRecipientSupplier" style="display: none;">
   <option value="<?php echo $_SESSION['id'] ?>"><?php echo $_SESSION['fname']?></option>
  </select>
<table class='table table-sm table-bordered table-striped'>
    <thead class='bg-primary'>
     <th>Date</th>
     <th>From</th>
     <th>Supplier</th>
     <th>Amount</th>
     <th>Notes</th>
     <th></th>
     </thead>
    <tbody>
     <td><input type="date" class="form-control" id="supplierWithdrawDate"></td>
     <td><select class="form-control" id="supplierPaiedTypy"></select></td>
      <td>
       <select class="form-control" id="supplierAccountName">
        <option value="<?php echo $supplierInvoiceData['supplierCode'] ?>"><?php echo $supplierData['suppliername']?></option>
       </select>
      </td>
      <td><input type="number" class="form-control" id="supplierWithdrawAmount"></td>
      <td><textarea class="form-control" id="supplierWithdrawDiscrebtion"></textarea></td>
      <td><button class="btn btn-success btn-lg" id="saveSupplierWithdraw">Save</button></td>
     </tbody>
   </table>




<script type="text/javascript">
 $(document).ready(function(){  


   $("#supplierPaiedTypy").load("dist/php/Acc/cashCode.php");


  // Cheak Paied Value
  $("#supplierWithdrawAmount").change(function(){
   var vAmount = $('#validAmount').val();
   var pAmount = $(this).val();
   if(Number(pAmount) > Number(vAmount)){
    $('#supplierWithdrawAmount').addClass("errColor");
    $('#saveSupplierWithdraw').prop('disabled', true);
   }
   else{
    $('#supplierWithdrawAmount').addClass("successColor");
    $('#saveSupplierWithdraw').prop('disabled', false);
   }
   return false;
  });




  // Save Data
  $("#saveSupplierWithdraw").click(function(){
   var vaAmount = $('#validAmount').val(); 
   var actionDate=$("#supplierWithdrawDate").val();
   var actionType=$("#supplierPaiedTypy").val();
   var accName =$("#supplierAccountName").val();
   var recipient =$("#withdrawRecipientSupplier").val();
   var amount =$("#supplierWithdrawAmount").val();
   var discrebtion =$("#supplierWithdrawDiscrebtion").val();
   var iNum=$("#invoiceNumber").val();
   var soNum=$("#lPaied").val();
   if( actionDate == ''){ alert ("Do'not Leave Date Blank.");}
   else if(actionType == ''){ alert ("Do'not Leave Paied Typy Blank.");}  
   else if(amount == ''){ alert ("Do'not Leave Amount Blank.");}  
   else if(discrebtion == ''){ alert ("Do'not Leave Discrebtion Blank.");}  
   else{
    $.ajax({ 
     url:'dist/php/Acc/saveNewWithdrawToSupplier.php',
     type:"POST",
     data:{fDate:actionDate,fType:actionType,fCode:accName,frecipient:recipient,famount:amount,fdiscrebtion:discrebtion,invNumber:iNum,paiedL:soNum,fValid:vaAmount},
     beforeSend:function(){
      $('#saveSupplierWithdraw').prop('disabled', true);
     },
     success: function(saveWithdrawTransaction){
      if(saveWithdrawTransaction == 1){
       $("#supplierModal").modal('toggle');
       alert("Data Saved");
       //$(".data_display").load("dist/php/Acc/purchesingInvoiceData.php");
      }
       else if(saveWithdrawTransaction == 2){
alert("Balance Not Avaliable");

     }
      else{
       alert(saveWithdrawTransaction);
      }
     }
    });
   }
   return false;
  });
 });
</script>
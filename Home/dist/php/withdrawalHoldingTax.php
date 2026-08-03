<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $invId=(int)$_POST['orderId'];
 $sqlSalesTax="SELECT `salesInvoiceNumber`,`jopRef`,`salesInvoiceDate`,`customerCode`,`salesInvoiceType`,`salesInvoiceSupTotal` FROM `salesInvoice` 
 WHERE  `salesInvoiceId` = $invId";
 $querySalesTax=mysqli_query($link,$sqlSalesTax)or die("ERROR_SNSC : 02");
 $taxData=mysqli_fetch_assoc($querySalesTax);
 $invType=$taxData['salesInvoiceType'];
 $invoiceNumber=$taxData['salesInvoiceNumber'];
 $sqlCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $taxData[customerCode]";
 $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
 $customerData=mysqli_fetch_assoc($quaryCustomer);  
 $sqlSalesVat="SELECT `transactionsDate`,`debtor`,`creditor`,`description` FROM `financialTransactions` WHERE `transactionCode` = 21210110000 
 AND `description` like '$invoiceNumber %'";
 $querySalesVat=mysqli_query($link,$sqlSalesVat)or die("ERROR_SNSC : 02");
 $collectVat=mysqli_num_rows($querySalesVat);
 if($collectVat >= 1){
  echo "You Already Make HoldingTax For This Invoice";    
 }
 else{
  $sqlHWTType="SELECT `jobtype` FROM `job` WHERE  `jobId` = $taxData[jopRef]";
  $quaryHWT=mysqli_query($link,$sqlHWTType)or die("ERROR_SNSC : 02");
  $HWTData=mysqli_fetch_assoc($quaryHWT);
  $jopType=$HWTData['jobtype'];
  if($invType == 'VAT'){
   if($jopType == 'Maintenance'){
    $validTax=($taxData['salesInvoiceSupTotal'] * 0.03);
   }
   else{
    $validTax=($taxData['salesInvoiceSupTotal'] * 0.01);
   }
   
  

   echo "
    <div class='row'>
     <div class='col-2 text-center form-control border-0'>Invoice Number</div>
     <div class='col-3'><input class='form-control' id='invNum' value='$taxData[salesInvoiceNumber]'  readonly></div>
     <div class='col-2'></div>
     <div class='col-2 text-center form-control border-0'>Date</div>
     <div class='col-3'><input type='date' class='form-control' id='taxInvoiceDate'></div>
    </div>
    <br>
    <div class='row'>
     <div class='col-2 text-center form-control border-0'>Amount</div>
     <div class='col-3'><input type='number' id='taxValue' class='text-center form-control' value='$validTax' readonly></div>
     <div class='col-2'></div>
     <div class='col-2 text-center form-control border-0'>Invoice Customer</div>
     <div class='col-3'>
      <select class='form-control' id='salesAccountName' readonly> <option value='$taxData[customerCode]'>$customerData[customername]</option></select>
     </div>
    </div>
    <center>
     <button class='btn btn-info' id='saveTax' value='$invId'>Save</button>
    </center>
   ";
  }
  else{
   echo "Can't Make HoldingTax For Invoice Not Include VAT";
  }
 }
?>
<script type="text/javascript">
 $("#saveTax").click(function(){
  var invId = $(this).val();
  var invDate = $("#taxInvoiceDate").val();
  if(invDate == ''){
   alert('Type The Date');
  }
  else{
   $.ajax({
    url:'dist/php/saveNewHoldingTax.php',
    type:"POST",
    data:{jopId:invId,fDate:invDate},
    success: function(doneHoldingTax){
     alert(doneHoldingTax);
     $("#salesModal").modal('toggle');
     $(".data_display").load("dist/html/newSalesInvoiceCollect.html");
    }
   });
  }
  return false;
 });
</script>
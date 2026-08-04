<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $invId=(int)$_POST['invNum'];
 $sqlSalesTax="SELECT `salesInvoiceNumber`,`jopRef`,`salesInvoiceDate`,`customerCode`,`salesInvoiceType`,`salesInvoiceSupTotal` FROM `salesInvoice` 
 WHERE  `salesInvoiceId` = $invId";
 $querySalesTax=mysqli_query($link,$sqlSalesTax)or die("ERROR_SNSC : 02");
 $taxData=mysqli_fetch_assoc($querySalesTax);
 $invType=$taxData['salesInvoiceType'];
 $sqlCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $taxData[customerCode]";
 $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
 $customerData=mysqli_fetch_assoc($quaryCustomer);
 echo "<style>
  #collectInvoiceForm .form-control{transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;}
  #collectInvoiceForm .form-control:focus{box-shadow: 0 0 0 0.2rem rgba(23,162,184,.25);}
  #collectInvoiceForm .amountPreview{font-size: .85rem; color:#6c757d; margin-top:.25rem;}
  #collectInvoiceForm .invalid-feedback{display:none;}
  #collectInvoiceForm .is-invalid ~ .invalid-feedback,
  #collectInvoiceForm .invalid-feedback.show{display:block;}
  #saveCollectInvoice{transition: transform .1s ease-in-out;}
  #saveCollectInvoice:active{transform: scale(0.97);}
 </style>
 <div id='collectInvoiceForm'>
  <div class='row'>
   <div class='form-group col-md-3'>
    <label for='invNum'>Invoice Number</label>
    <input class='form-control' id='invNum' value='$taxData[salesInvoiceNumber]' readonly>
   </div>
   <div class='form-group col-md-3'>
    <label for='salesAccountName'>Customer</label>
    <select class='form-control' id='salesAccountName' readonly> <option value='$taxData[customerCode]'>$customerData[customername]</option></select>
   </div>
   <div class='form-group col-md-2'>
    <label for='collectRef'>Collect Type</label>
    <select class='form-control' id='collectRef'></select>
    <div class='invalid-feedback'>Collect type is required</div>
   </div>
   <div class='form-group col-md-2'>
    <label for='collectDate'>Date</label>
    <input type='date' class='form-control' id='collectDate'>
    <div class='invalid-feedback'>Date is required</div>
   </div>
   <div class='form-group col-md-2'>
    <label for='collectValue'>Amount</label>
    <input type='number' id='collectValue' class='text-center form-control'>
    <div class='amountPreview'></div>
    <div class='invalid-feedback'>Amount is required</div>
   </div>
  </div>
  <div class='row bankData' style='display: none;'>
   <div class='form-group col-md-3'>
    <label for='dueDate'>Maturity Date</label>
    <input type='date' id='dueDate' class='form-control'>
   </div>
   <div class='form-group col-md-3'>
    <label for='cheaquNumber'>Cheque Number</label>
    <input type='number' id='cheaquNumber' class='form-control'>
   </div>
  </div>
  <button class='btn btn-info' id='saveCollectInvoice' value='$invId'>Save</button>
 </div>";
?>
<script type="text/javascript">
 $("#collectRef").load("dist/php/Acc/cashCode.php");
 $("#collectRef").change(function(){
  var cashType = $(this).val();
  var firstDigit = cashType.substring(0, 5);
  if(firstDigit == 11620){
   $(".bankData").slideDown(200);
  }
  else{
   $(".bankData").slideUp(200);
  }
  validateField($(this));
 });

 $("#collectValue").on('input', function(){
  var val = parseFloat($(this).val());
  if(!isNaN(val)){
   $(this).closest('.form-group').find('.amountPreview').text(val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
  }
  else{
   $(this).closest('.form-group').find('.amountPreview').text('');
  }
  validateField($(this));
 });

 function validateField($field){
  var val = $.trim($field.val());
  var $group = $field.closest('.form-group');
  if(val === ''){
   $field.addClass('is-invalid');
   $group.find('.invalid-feedback').addClass('show');
  }
  else{
   $field.removeClass('is-invalid');
   $group.find('.invalid-feedback').removeClass('show');
  }
 }

 $("#collectRef, #collectDate, #collectValue").on('blur change', function(){
  validateField($(this));
 });

 $("#saveCollectInvoice").click(function(){
  var colletRef = $("#collectRef").val();
  var colletDate = $("#collectDate").val();
  var colletAmount = $("#collectValue").val();
  var colletInvNum = $(this).val();
  var colletCustomer = $("#salesAccountName").val();
  var chickNum = $("#cheaquNumber").val();
  var dueDate = $("#dueDate").val();

  $("#collectRef, #collectDate, #collectValue").each(function(){
   validateField($(this));
  });

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
   var $btn = $('#saveCollectInvoice');
   var originalText = $btn.text();
   $.ajax({
    url:'dist/php/Acc/saveNewCollectSalesInvoice.php',
    type:"POST",
    data:{fRef:colletRef,fDate:colletDate,fAmount:colletAmount,fInvNum:colletInvNum,fCustomer:colletCustomer,numCheak:chickNum,cheakDate:dueDate},
    beforeSend:function(){
     $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    },
    success: function(doneHoldingTax){
     $btn.prop('disabled', false).text(originalText);
     $("#salesModal").modal('toggle');
     alert(doneHoldingTax);
     $(".data_display").load("dist/html/Acc/newSalesInvoiceCollect.html");
    }
   });
  }
  return false;
 });
</script>
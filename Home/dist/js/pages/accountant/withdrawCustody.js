$(document).ready(function(){
 $("#custodyData").hide();
 $("#saveWithdrawCustody").hide();
 $("#saveCashBackCustody").hide();
 $("#withdrawCustodyRecipient").load("dist/php/empCode.php");
 $("#projectsCustodyList").load("dist/php/projectsList2.php");

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

 $(document).on('input', '#withdrawCustodyAmount', function(){
  var val = parseFloat($(this).val());
  if(!isNaN(val)){
   $(this).closest('.form-group').find('.amountPreview').text(val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
  }
  else{
   $(this).closest('.form-group').find('.amountPreview').text('');
  }
  validateField($(this));
 });

 $(document).on('blur change', '#withdrawCustodyDate, #withdrawCustodyAmount, #withdrawCustodyDiscrebtion', function(){
  validateField($(this));
 });

 //--------------------*{}*--------------------\\
 $("#projectsCustodyList").change(function(){
  $("#withdrawCustodyRecipient").fadeIn(150);
 });
 //--------------------*{}*--------------------\\
 $("#withdrawCustodyRecipient").change(function(){
  var empName =$(this).val();
  $.ajax({
   url:'dist/php/cheackWithdrawCustody.php',
   type:"POST",
   data:{fName:empName},
   success: function(custodyCheack){
    $("#projectsCustodyList").prop('disabled',true);
    $("#withdrawCustodyRecipient").prop('disabled',true);
    if(custodyCheack == 3){
     $("#custodyData").slideDown(200);
     $("#saveWithdrawCustody").fadeIn(200);
    }
    else{
     $("#custodyData").html(custodyCheack);
     $("#saveCashBackCustody").fadeIn(200);
     $("#custodyData").slideDown(200);
    }
   }
  });
 });
 //--------------------*{}*--------------------\\
 $("#saveWithdrawCustody").click(function(){
  var PONum = $('#projectsCustodyList :selected').val();
  var recipient = $("#withdrawCustodyRecipient").val();
  var actionDate = $("#withdrawCustodyDate").val();
  var amount = $("#withdrawCustodyAmount").val();
  var casher= $("#trasuty").val();
  var discrebtion = $("#withdrawCustodyDiscrebtion").val();

  $("#withdrawCustodyDate, #withdrawCustodyAmount, #withdrawCustodyDiscrebtion").each(function(){
   validateField($(this));
  });

  if( actionDate == ''){
   alert ("Do'not Leave Date Blank.");
  }
  else if(amount == ''){
   alert ("Do'not Leave Amount Blank.");
  }
  else if(discrebtion == ''){
   alert ("Do'not Leave Discrebtion Blank.");
  }
  else{
   var $btn = $('#saveWithdrawCustody');
   var originalText = $btn.text();
   $.ajax({
    url:'dist/php/saveNewWithdrawCustody.php',
    type:"POST",
    data:{fDate:actionDate,frecipient:recipient,famount:amount,fdiscrebtion:discrebtion,poNumber:PONum},
    beforeSend:function(){
     $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    },
    success: function(saveWithdrawOtherTransaction){
     $btn.prop('disabled', false).text(originalText);
     if(saveWithdrawOtherTransaction == 1){
      alert("Data Saved ");
      $("#withdrawCustodyRecipient").hide();
      $("#custodyData").slideUp(200);
      $("#withdrawCustodyRecipient").load("dist/php/empCode.php");
      $("#projectsCustodyList").load("dist/php/projectsList2.php");
      $("#withdrawCustodyDate").val('');
      $("#withdrawCustodyAmount").val('');
      $("#withdrawCustodyDiscrebtion").val('');
      $(".amountPreview").text('');
      $('#saveWithdrawCustody').prop('disabled', false);
      $("#projectsCustodyList").prop('disabled', false);
      $("#withdrawCustodyRecipient").prop('disabled', false);
     }
     else if(saveWithdrawOtherTransaction == 2){
alert("Balance Not Avaliable");

     }
     else{
      alert(saveWithdrawOtherTransaction);
     }
    }
   });
  }
  return false;
 });
 //--------------------*{}*--------------------\\
 $("#saveCashBackCustody").click(function(){
  var PONum = $('#projectsCustodyList :selected').val();
  var emp = $("#withdrawCustodyRecipient").val();
  $.ajax({
   url:'dist/php/getWithdrawCustodyData.php',
   type:"POST",
   data:{frecipient:emp,poNumber:PONum},
   success: function(getWithdrawCustody){
    $(".modal-body").html(getWithdrawCustody);
    $("#custodyModalCashBack").modal('show');
   }
  });
  return false;
 });
});

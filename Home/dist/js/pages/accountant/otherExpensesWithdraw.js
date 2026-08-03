$(document).ready(function(){
 $("#withdrawOtherExpensesRecipient").load("dist/php/empCode.php");
 $("#accountName3").load("dist/php/accountantCodeProcessing.php");

 $("#withdrawOtherExpensesAmount").on('input', function(){
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

 $("#withdrawOtherExpensesDate, #accountName3, #withdrawOtherExpensesRecipient, #withdrawOtherExpensesAmount, #withdrawOtherExpensesDiscrebtion").on('blur change', function(){
  validateField($(this));
 });

 $("#saveOtherExpensesWithdraw").click(function(){
  var PONum = $('#projectsId').val();
  var actionDate = $("#withdrawOtherExpensesDate").val();
  var accName = $("#accountName3").val();
  var recipient = $("#withdrawOtherExpensesRecipient").val();
  var amount = $("#withdrawOtherExpensesAmount").val();
  var discrebtion = $("#withdrawOtherExpensesDiscrebtion").val();

  $("#withdrawOtherExpensesDate, #accountName3, #withdrawOtherExpensesRecipient, #withdrawOtherExpensesAmount, #withdrawOtherExpensesDiscrebtion").each(function(){
   validateField($(this));
  });

  if( actionDate == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Dont Skip Date Empty").fadeIn(150);
   $('#withdrawOtherExpensesDate').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(accName == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Dont Skip Account Name Empty").fadeIn(150);
   $('#accountName3').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(recipient == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Dont Skip Recipient Empty").fadeIn(150);
   $('#withdrawOtherExpensesRecipient').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(amount == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Dont Skip Amount Empty").fadeIn(150);
   $('#withdrawOtherExpensesAmount').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(discrebtion == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Dont Skip Description Empty").fadeIn(150);
   $('#withdrawOtherExpensesDiscrebtion').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else{
   var $btn = $('#saveOtherExpensesWithdraw');
   var originalText = $btn.text();
   $.ajax({
    url:'dist/php/saveNewProjectWithdraw.php',
    type:"POST",
    data:{fDate:actionDate,fCode:accName,frecipient:recipient,famount:amount,fdiscrebtion:discrebtion,poNumber:PONum},
    beforeSend:function(){
     $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    },
    success: function(saveWithdrawOtherTransaction){
     $btn.prop('disabled', false).text(originalText);
     if(saveWithdrawOtherTransaction == 1){
      $('.msg').removeClass('alert-danger').addClass('alert-success').hide().html("Data Saved").fadeIn(150);
      $(".msg").delay(2000).fadeOut(600);
      $("#withdrawOtherExpensesDate").val('');
      $("#accountName3").val('');
      $("#withdrawOtherExpensesRecipient").val('');
      $("#withdrawOtherExpensesAmount").val('');
      $("#withdrawOtherExpensesDiscrebtion").val('');
      $(".amountPreview").text('');
     }
     else{
      $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html(saveWithdrawOtherTransaction).fadeIn(150);
      $(".msg").delay(6000).fadeOut(600);
     }
    }
   });
  }
  return false;
 });
});

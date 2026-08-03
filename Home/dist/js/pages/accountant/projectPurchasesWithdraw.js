$(document).ready(function(){

 $("#withdrawProjectPurchasesAmount").on('input', function(){
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

 $("#withdrawProjectPurchasesDate, #accountName1, #withdrawProjectPurchasesRecipient, #withdrawProjectPurchasesAmount, #withdrawProjectPurchasesDiscrebtion").on('blur change', function(){
  validateField($(this));
 });

 $("#saveProjectPurchasesWithdraw").click(function(){
  var PONum = $('#projectsId').val();
  var actionDate = $("#withdrawProjectPurchasesDate").val();
  var accName = $("#accountName1").val();
  var recipient = $("#withdrawProjectPurchasesRecipient").val();
  var amount = $("#withdrawProjectPurchasesAmount").val();
  var discrebtion = $("#withdrawProjectPurchasesDiscrebtion").val();

  $("#withdrawProjectPurchasesDate, #accountName1, #withdrawProjectPurchasesRecipient, #withdrawProjectPurchasesAmount, #withdrawProjectPurchasesDiscrebtion").each(function(){
   validateField($(this));
  });

  if( actionDate == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Dont Skip Date Empty").fadeIn(150);
   $('#withdrawProjectPurchasesDate').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(accName == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Dont Skip Account Name Empty").fadeIn(150);
   $('#accountName1').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(recipient == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Dont Skip Recipient Empty").fadeIn(150);
   $('#withdrawProjectPurchasesRecipient').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(amount == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Dont Skip Amount Empty").fadeIn(150);
   $('#withdrawProjectPurchasesAmount').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(discrebtion == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Dont Skip Description Empty").fadeIn(150);
   $('#withdrawProjectPurchasesDiscrebtion').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else{
   var $btn = $('#saveProjectPurchasesWithdraw');
   var originalText = $btn.text();
   $.ajax({
    url:'dist/php/saveNewProjectWithdraw.php',
    type:"POST",
    data:{fDate:actionDate,fCode:accName,frecipient:recipient,famount:amount,fdiscrebtion:discrebtion,poNumber:PONum},
    beforeSend:function(){
     $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    },
    success: function(saveWithdrawPurchTransaction){
     $btn.prop('disabled', false).text(originalText);
     if(saveWithdrawPurchTransaction == 1){
      $('.msg').removeClass('alert-danger').addClass('alert-success').hide().html("Data Saved").fadeIn(150);
      $(".msg").delay(2000).fadeOut(600);
      $("#withdrawProjectPurchasesDate").val('');
      $("#accountName1").val('');
      $("#withdrawProjectPurchasesRecipient").val('');
      $("#withdrawProjectPurchasesAmount").val('');
      $("#withdrawProjectPurchasesDiscrebtion").val('');
      $(".amountPreview").text('');
     }
     else{
      $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html(saveWithdrawPurchTransaction).fadeIn(150);
      $(".msg").delay(6000).fadeOut(600);
     }
    }
   });
  }
  return false;
 });
});

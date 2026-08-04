$(document).ready(function(){
 $("#accountName").load("dist/php/Acc/accountantCodeGeneral.php");
 $("#withdrawRecipient").load("dist/php/Acc/empCode.php");
 $("#trasuty").load("dist/php/Acc/cashCode.php");

 $("#trasuty").change(function(){
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

 // Live formatted preview of the entered amount
 $("#withdrawAmount").on('input', function(){
  var val = parseFloat($(this).val());
  if(!isNaN(val)){
   $(this).closest('.form-group').find('.amountPreview').text(val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
  }
  else{
   $(this).closest('.form-group').find('.amountPreview').text('');
  }
  validateField($(this));
 });

 // Inline validation as the user types/leaves a field, instead of only on Save click
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

 $("#withdrawDate, #trasuty, #accountName, #withdrawRecipient, #withdrawAmount, #withdrawDiscrebtion").on('blur change', function(){
  validateField($(this));
 });

 $("#saveGeneralWithdraw").click(function(){
  var actionDate = $("#withdrawDate").val();
  var accName = $("#accountName").val();
  var recipienta = $("#withdrawRecipient").val();
  var recipient = recipienta.replace(/\s/g, "");
  var amount = $("#withdrawAmount").val();
  var discrebtion = $("#withdrawDiscrebtion").val();
  var cash = $("#trasuty").val();
  var chickNum = $("#cheaquNumber").val();
  var dueDate = $("#dueDate").val();

  $("#withdrawDate, #trasuty, #accountName, #withdrawRecipient, #withdrawAmount, #withdrawDiscrebtion").each(function(){
   validateField($(this));
  });

  if( actionDate == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html('Dont Skip Date Empty').fadeIn(150);
   $('#withdrawDate').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(accName == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html('Dont Skip Account Name Empty').fadeIn(150);
   $('#accountName').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(recipient == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html('Dont Skip Recipient Empty').fadeIn(150);
   $('#withdrawRecipient').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(amount == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html('Dont Skip Amount Empty').fadeIn(150);
   $('#withdrawAmount').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(discrebtion == ''){
   $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html('Dont Skip Description Empty').fadeIn(150);
   $('#withdrawDiscrebtion').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else{
   var $btn = $('#saveGeneralWithdraw');
   var originalText = $btn.text();
   $.ajax({
    url:'dist/php/Acc/saveNewWithdraw.php',
    type:"POST",
    data:{fDate:actionDate,fCode:accName,frecipient:recipient,famount:amount,fdiscrebtion:discrebtion,typeCash:cash,numCheak:chickNum,cheakDate:dueDate},
    beforeSend:function(){
     $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    },
    success: function(saveWithdrawTransaction){
     $btn.prop('disabled', false).text(originalText);
     if(saveWithdrawTransaction == 1){
      $('.msg').removeClass('alert-danger').addClass('alert-success').hide().html("Data Saved").fadeIn(150);
      $(".msg").delay(2000).fadeOut(600);
      $('.card-text').load('dist/html/Acc/withdrawGeneralExpenses.html');
     }
     else if(saveWithdrawTransaction == 2){
      $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html("Balance Not Avaliable").fadeIn(150);
      $(".msg").delay(6000).fadeOut(600);
     }
     else{
      $('.msg').removeClass('alert-success').addClass('alert-danger').hide().html(saveWithdrawTransaction).fadeIn(150);
      $(".msg").delay(6000).fadeOut(600);
     }
    }
   });
  }
  return false;
 });
});

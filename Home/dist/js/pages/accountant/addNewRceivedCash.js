$(document).ready(function(){
   $("#trasuty").load("dist/php/cashCode.php");
   $("#accountName").load("dist/php/allCodeSelect.php");
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

   // Live formatted preview of the entered amount (e.g. "1,250.00")
   $("#receivAmount").on('input', function(){
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

   $("#receDate, #trasuty, #accountName, #receivAmount, #receivDescription").on('blur change', function(){
    validateField($(this));
   });

   $("#saverRceivedCash").click(function(){
    var rDate = $('#receDate').val();
    var rCode = $('#accountName').val();
    var rAmount = $('#receivAmount').val();
    var rDes = $('#receivDescription').val();
    var cash = $("#trasuty").val();
    var chickNum = $("#cheaquNumber").val();
    var dueDate = $("#dueDate").val();

    // Run inline validation on every required field so the user sees exactly which ones are empty
    $("#receDate, #trasuty, #accountName, #receivAmount, #receivDescription").each(function(){
     validateField($(this));
    });

    if(rCode == cash){
     alert("Duplicate Account");
    }
    else if(rDate == ""){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').hide().html('Dont Skip Date Empty').fadeIn(150);
     $('#receDate').focus();
     $(".msg").delay(3000).fadeOut(600);
    }
    else if(rCode == ""){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').hide().html('Dont Skip Account Empty').fadeIn(150);
     $('#accountName').focus();
     $(".msg").delay(3000).fadeOut(600);
    }
    else if(rAmount == ""){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').hide().html('Dont Skip Amount Empty').fadeIn(150);
     $('#receivAmount').focus();
     $(".msg").delay(3000).fadeOut(600);
    }
    else if(rDes == ""){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').hide().html('Dont Skip Description Empty').fadeIn(150);
     $('#receivDescription').focus();
     $(".msg").delay(3000).fadeOut(600);
    }
    else{
     var $btn = $('#saverRceivedCash');
     var originalText = $btn.text();
     $.ajax({
      url:'dist/php/saveNewReceivedCash.php',
      type:"POST",
      data:{fDate:rDate,fCode:rCode,fAmount:rAmount,fDes:rDes,typeCash:cash,numCheak:chickNum,cheakDate:dueDate},
      beforeSend:function(){
       $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
      },
      success: function(saveReceivedCashDone){
       $btn.prop('disabled', false).text(originalText);
       if(saveReceivedCashDone == 1){
        $('.msg').removeClass('alert alert-danger');
        $('.msg').addClass('alert alert-success');
        $('.msg').hide().html("Data Saved").fadeIn(150);
        $(".msg").delay(2000).fadeOut(600);
        $(".data_display").load("dist/html/newCashReceive.html");
       }
       else{
        $('.msg').removeClass('alert alert-success');
        $('.msg').addClass('alert alert-danger');
        $('.msg').hide().html(saveReceivedCashDone).fadeIn(150);
        $(".msg").delay(6000).fadeOut(600);
       }
      }
     });
    }
   });
   return false;
  });

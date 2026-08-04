$(document).ready(function(){

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

  $("#investmentAmount").on('input', function(){
   var val = parseFloat($(this).val());
   if(!isNaN(val)){
    $(this).closest('.form-group').find('.amountPreview').text(val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
   }
   else{
    $(this).closest('.form-group').find('.amountPreview').text('');
   }
   validateField($(this));
  });

  $("#investmentDate, #investmentQun, #investmentGroup, #investmentDescription").on('blur change', function(){
   validateField($(this));
  });

  $("#saveInvestment").click(function(){
   var investmentDate = $('#investmentDate').val();
   var investmentAmount= $('#investmentAmount').val();
   var investmentQuant= $('#investmentQun').val();
   var investmentGroup = $('#investmentGroup').val();
   var investmentDis= $('#investmentDescription').val();

   $("#investmentDate, #investmentAmount, #investmentQun, #investmentGroup, #investmentDescription").each(function(){
    validateField($(this));
   });

    if(investmentDate == ''){
    $('.msg').removeClass('alert alert-success');
    $('.msg').addClass('alert alert-danger');
    $('.msg').hide().html('Dont Skip Date Empty').fadeIn(150);
    $(".msg").delay(3000).fadeOut(600);
   }
   else if(investmentAmount == ''){
    $('.msg').removeClass('alert alert-success');
    $('.msg').addClass('alert alert-danger');
    $('.msg').hide().html('Dont Skip Amount Empty').fadeIn(150);
    $(".msg").delay(3000).fadeOut(600);
   }
   else if(investmentQuant == ''){
    $('.msg').removeClass('alert alert-success');
    $('.msg').addClass('alert alert-danger');
    $('.msg').hide().html('Dont Skip Quantaty Empty').fadeIn(150);
    $(".msg").delay(3000).fadeOut(600);
   }
   else if(investmentGroup == ''){
    $('.msg').removeClass('alert alert-success');
    $('.msg').addClass('alert alert-danger');
    $('.msg').hide().html('Dont Skip Category Empty').fadeIn(150);
    $(".msg").delay(3000).fadeOut(600);
   }
   else if(investmentDis == ''){
    $('.msg').removeClass('alert alert-success');
    $('.msg').addClass('alert alert-danger');
    $('.msg').hide().html('Dont Skip Discription Empty').fadeIn(150);
    $(".msg").delay(3000).fadeOut(600);
   }
   else{
    var $btn = $('#saveInvestment');
    var originalText = $btn.text();
    $.ajax({
     url:"dist/php/Acc/saveNewABuyInvestment.php",
     type:"POST",
     data:{fDate:investmentDate,fAmount:investmentAmount,fGroup:investmentGroup,fDis:investmentDis,fQun:investmentQuant},
     beforeSend:function(){
      $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
     },
     success: function(addInvestmentDone){
      $btn.prop('disabled', false).text(originalText);
      if(addInvestmentDone == 1){
       $('.msg').removeClass('alert alert-danger');
       $('.msg').addClass('alert alert-success');
       $('.msg').hide().html("Data Saved").fadeIn(150);
       $(".msg").delay(2000).fadeOut(600);
       $('#investmentDate').val('');
       $('#investmentAmount').val('');
       $('#investmentQun').val('');
       $('#investmentGroup').val('');
       $('#investmentDescription').val('');
       $('.amountPreview').text('');
      }
      else{
       $('.msg').removeClass('alert alert-success');
       $('.msg').addClass('alert alert-danger');
       $('.msg').hide().html(addInvestmentDone).fadeIn(150);
       $(".msg").delay(6000).fadeOut(600);
      }
     }
    });
   }
  return false;
 });
      });

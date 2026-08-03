$(document).ready(function(){
   $("#advanceType").css('display','none');
   $("#saveAdvance").css('display','none');
   $(".employeesAdvance").css('display','none');
   $(".workersAdvance").css('display','none');
   $("#employee").load("dist/php/empCode.php");
   $("#workers").load("dist/php/allStafSelect.php");

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

   $("#employeeAmount, #workersAmount").on('input', function(){
    var val = parseFloat($(this).val());
    if(!isNaN(val)){
     $(this).closest('.form-group').find('.amountPreview').text(val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }
    else{
     $(this).closest('.form-group').find('.amountPreview').text('');
    }
    validateField($(this));
   });

   $("#employee, #monthInstallment, #employeeDate, #workers, #weekInstallment, #workersDate").on('blur change', function(){
    validateField($(this));
   });

   $("#advanceWorkers").click(function(){
    $(".employeesAdvance").slideUp(150);
    $(".workersAdvance").slideDown(150);
    $("#saveAdvance").css('display','block');
    $("#advanceType").val(2);
   });
   $("#advanceEmployees").click(function(){
    $(".workersAdvance").slideUp(150);
    $(".employeesAdvance").slideDown(150);
    $("#saveAdvance").css('display','block');
    $("#advanceType").val(1);
   });
   $("#saveAdvance").click(function(){
	 var advanceType= $('#advanceType').val();
    if(advanceType == 1){
     var emp = $('#employee').val();
	  var advanceDate = $('#employeeDate').val();
	  var advanceAmounts= $('#employeeAmount').val();
     var instaVal= $('#monthInstallment').val();
     $("#employee, #employeeAmount, #monthInstallment, #employeeDate").each(function(){ validateField($(this)); });
    }
    else if(advanceType == 2){
     var emp = $('#workers').val();
	  var advanceDate = $('#workersDate').val();
	  var advanceAmounts= $('#workersAmount').val();
     var instaVal= $('#weekInstallment').val();
     $("#workers, #workersAmount, #weekInstallment, #workersDate").each(function(){ validateField($(this)); });
    }
    if(emp == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').hide().html('Dont Skip Name Empty').fadeIn(150);
     $(".msg").delay(3000).fadeOut(600);
    }
    else if(advanceDate == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').hide().html('Dont Skip Advance Date Empty').fadeIn(150);
     $(".msg").delay(3000).fadeOut(600);
    }
    else if(advanceAmounts == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').hide().html('Dont Skip Advance Amount Empty').fadeIn(150);
     $(".msg").delay(3000).fadeOut(600);
    }
    else if(instaVal == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').hide().html('Dont Skip Installment Amount Empty').fadeIn(150);
     $(".msg").delay(3000).fadeOut(600);
    }
    else{
     var $btn = $('#saveAdvance');
     var originalText = $btn.text();
     $.ajax({
      url:"dist/php/saveNewAdvance.php",
      type:"POST",
      data:{advType:advanceType,user:emp,advancMonth:advanceDate,amount:advanceAmounts,instAmount:instaVal},
      beforeSend: function(){
       $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
      },
      success: function(addAdvanceDone){
       $btn.prop('disabled', false).text(originalText);
       if(addAdvanceDone == 1){
        $('.msg').removeClass('alert alert-danger');
        $('.msg').addClass('alert alert-success');
        $('.msg').hide().html("Data Saved").fadeIn(150);
        $(".msg").delay(2000).fadeOut(600);
        $(".data_display").load("dist/html/newAdvanceWithdraw.html");
       }
       else{
        $('.msg').removeClass('alert alert-success');
        $('.msg').addClass('alert alert-danger');
        $('.msg').hide().html(addAdvanceDone).fadeIn(150);
        $(".msg").delay(6000).fadeOut(600);
       }
      }
     });
    }
	return false;
   });
  });

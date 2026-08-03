$(document).ready(function(){
   $("#advanceType").css('display','none');
   $("#saveAdvance").css('display','none');
   $(".employeesAdvance").css('display','none');
   $(".workersAdvance").css('display','none');
   $("#employee").load("dist/php/empCode.php");
   $("#workers").load("dist/php/allStafSelect.php");
   $("#advanceWorkers").click(function(){
    $(".employeesAdvance").css('display','none');
    $(".workersAdvance").css('display','block');
    $("#saveAdvance").css('display','block');    
    $("#advanceType").val(2); 
   });
   $("#advanceEmployees").click(function(){
    $(".workersAdvance").css('display','none');
    $(".employeesAdvance").css('display','block');    
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
    }
    else if(advanceType == 2){
     var emp = $('#workers').val();
	  var advanceDate = $('#workersDate').val();
	  var advanceAmounts= $('#workersAmount').val();
     var instaVal= $('#weekInstallment').val();
    }
    if(emp == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Name Empty');
     $(".msg").fadeOut(5000);
    }
    else if(advanceDate == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Advance Date Empty');
     $(".msg").fadeOut(5000);
    }
    else if(advanceAmounts == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Advance Amount Empty');
     $(".msg").fadeOut(5000);
    }
    else if(instaVal == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Installment Amount Empty');
     $(".msg").fadeOut(5000);
    }
    else{
     $.ajax({
      url:"dist/php/saveNewAdvance.php",
      type:"POST",
      data:{advType:advanceType,user:emp,advancMonth:advanceDate,amount:advanceAmounts,instAmount:instaVal},
      beforeSend: function(){
       //("#saveAdvance").prop('disabled', true); // disable button
      },
      success: function(addAdvanceDone){
       if(addAdvanceDone == 1){
        $('.msg').removeClass('alert alert-danger');
        $('.msg').addClass('alert alert-success');
        $('.msg').html("Data Saved");
        $(".msg").fadeOut(3000);
        $(".data_display").load("dist/html/newAdvanceWithdraw.html");
       }
       else{
        $('.msg').removeClass('alert alert-success');
        $('.msg').addClass('alert alert-danger');
        $('.msg').html(addAdvanceDone);
        $(".msg").fadeOut(9000);
       }
      }
     });
    }
	return false;
   });
  });
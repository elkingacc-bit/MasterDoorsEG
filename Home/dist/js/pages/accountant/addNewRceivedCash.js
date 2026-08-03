$(document).ready(function(){
   $("#trasuty").load("dist/php/cashCode.php");
   $("#accountName").load("dist/php/allCodeSelect.php");
   $("#trasuty").change(function(){
    var cashType = $(this).val();
    var firstDigit = cashType.substring(0, 5);
    if(firstDigit == 11620){
     $(".bankData").css('display','block');
    }
    else{
     $(".bankData").css('display','none');
    }
   });
   $("#saverRceivedCash").click(function(){
    var rDate = $('#receDate').val();
    var rCode = $('#accountName').val();
    var rAmount = $('#receivAmount').val();
    var rDes = $('#receivDescription').val();
    var cash = $("#trasuty").val();
    var chickNum = $("#cheaquNumber").val();
    var dueDate = $("#dueDate").val();
    if(rCode == cash){
     alert("Duplicate Account"); 
    }
    else if(rDate == ""){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Date Empty');
     $('#receDate').focus();
     $(".msg").fadeOut(5000);
    }
    else if(rCode == ""){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Account Empty');
     $('#accountName').focus();
     $(".msg").fadeOut(5000);
    }
    else if(rAmount == ""){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Amount Empty');
     $('#receivAmount').focus();
     $(".msg").fadeOut(5000);
    }
    else if(rDes == ""){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Description Empty');
     $('#receivDescription').focus();
     $(".msg").fadeOut(5000);
    }
    else{
     $.ajax({ 
      url:'dist/php/saveNewReceivedCash.php',
      type:"POST",
      data:{fDate:rDate,fCode:rCode,fAmount:rAmount,fDes:rDes,typeCash:cash,numCheak:chickNum,cheakDate:dueDate},
      beforeSend:function(){
       $('#saverRceivedCash').prop('disabled', true);
      },
      success: function(saveReceivedCashDone){
       if(saveReceivedCashDone == 1){
        $('.msg').removeClass('alert alert-danger');
        $('.msg').addClass('alert alert-success');
        $('.msg').html("Data Saved");
        $(".msg").fadeOut(3000);
        $(".data_display").load("dist/html/newCashReceive.html");
       }
       else{
        $('.msg').removeClass('alert alert-success');
        $('.msg').addClass('alert alert-danger');
        $('.msg').html(saveReceivedCashDone);
        $(".msg").fadeOut(9000);
       }
      }
     });
    }
   });
   return false;
  });
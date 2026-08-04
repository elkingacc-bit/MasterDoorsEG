$(document).ready(function(){
  $("#partnerName").load("dist/php/Acc/allPartnerCode.php");
  $("#partnerPer").load("dist/php/Acc/partnerPercanteg.php");
  $("#saveNewPartner").click(function(){
   var partnerName = $('#partnerName').val();
   var partnerPer = $('#partnerPer').val();
   if(partnerName == ""){
    $('.msg').removeClass('alert-success');
    $('.msg').addClass('alert-danger');
    $('.msg').html('Dont Skip Name Empty');
    $('#partnerName').focus();
    $(".msg").fadeOut(5000);
   }
   else if(partnerPer == ""){
    $('.msg').removeClass('alert-success');
    $('.msg').addClass('alert-danger');
    $('.msg').html('Dont Skip Percentage Empty');
    $('#partnerPer').focus();
    $(".msg").fadeOut(5000);
   }
   else{
    $.ajax({ 
     url:'dist/php/Acc/saveNewPartnerData.php',
     type:"POST",
     data:{name:partnerName,fper:partnerPer},
     beforeSend:function(){
      $('#accountName').prop('disabled', true);
     },
     success: function(savedepartmentDone){
      if(savedepartmentDone == 1){
       $('.msg').removeClass('alert-danger');
       $('.msg').addClass('alert-success');
       $('.msg').html('Data Saved');
       $('#partnerName').val("");
       $('#partnerPer').val("");
       $(".msg").fadeOut(5000); 
      }
      else{
       $('.msg').removeClass('alert-success');
       $('.msg').addClass('alert-danger');
       $('.msg').html((savedepartmentDone));
       $(".msg").fadeOut(9000); 
      }
     }
    });
   }
   return false;
  });
 });
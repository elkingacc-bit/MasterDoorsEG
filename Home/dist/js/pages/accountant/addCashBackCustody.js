$(document).ready(function(){
  $("#withdrawCustodyRecipient").load("dist/php/Acc/empcode3.php");
  $("#withdrawCustodyRecipient").change(function(){
   var emp = $(this).val();
   $.ajax({
    url:'dist/php/Acc/getWithdrawCustodyData.php',
    type:"POST",
    data:{frecipient:emp},
    success: function(getWithdrawCustody){
     $("#withdrawCustodyRecipient").prop('disabled',true);
     $(".custodyData").fadeOut(150, function(){
      $(".custodyData").html(getWithdrawCustody).fadeIn(150);
     });
    }
   });
   return false;
  });
 });

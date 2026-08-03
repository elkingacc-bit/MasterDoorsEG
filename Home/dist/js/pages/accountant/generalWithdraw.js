$(document).ready(function(){
 //
 $("#accountName").load("dist/php/accountantCodeGeneral.php");
 //
 $("#withdrawRecipient").load("dist/php/empCode.php");
 //
 $("#trasuty").load("dist/php/cashCode.php");
 //
 $("#trasuty").change(function(){
  var cashType = $(this).val();
  var firstDigit = cashType.substring(0, 5);
  //
  if(firstDigit == 11620){
   $(".bankData").css('display','block');
  }
  //
  else{
   $(".bankData").css('display','none');
  }
 });
 //
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
  if( actionDate == ''){
   alert ("Do'not Leave Date Blank.");
  }
  else if(accName == ''){
   alert ("Do'not Leave Account Name Blank.");
  }
  else if(recipient == ''){
   alert ("Do'not Leave Recipient Blank.");
  }
  else if(amount == ''){
   alert ("Do'not Leave Amount Blank.");
  }  
  else if(discrebtion == ''){
   alert ("Do'not Leave Discription Blank.");
  }
  else{
   $.ajax({ 
    url:'dist/php/saveNewWithdraw.php',
    type:"POST",
    data:{fDate:actionDate,fCode:accName,frecipient:recipient,famount:amount,fdiscrebtion:discrebtion,typeCash:cash,numCheak:chickNum,cheakDate:dueDate},
    beforeSend:function(){
     $('#saveGeneralWithdraw').prop('disabled', true);
    },
    success: function(saveWithdrawTransaction){
     if(saveWithdrawTransaction == 1){
      alert("Data Saved ");
      $('.card-text').load('dist/html/withdrawGeneralExpenses.html');
     }
      else if(saveWithdrawTransaction == 2){
alert("Balance Not Avaliable");

     }
     else{
      alert(saveWithdrawTransaction);
     }
    }
   });
  }
  return false;
 });
});
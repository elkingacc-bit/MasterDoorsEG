$(document).ready(function(){
 $("#saveProjectPurchasesWithdraw").click(function(){
  var PONum = $('#projectsId').val();
  var actionDate = $("#withdrawProjectPurchasesDate").val();
  var accName = $("#accountName1").val();
  var recipient = $("#withdrawProjectPurchasesRecipient").val();
  var amount = $("#withdrawProjectPurchasesAmount").val();
  var discrebtion = $("#withdrawProjectPurchasesDiscrebtion").val();
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
   alert ("Do'not Leave Discrebtion Blank.");
  }
  else{
   $.ajax({ 
    url:'dist/php/saveNewProjectWithdraw.php',
    type:"POST",
    data:{fDate:actionDate,fCode:accName,frecipient:recipient,famount:amount,fdiscrebtion:discrebtion,poNumber:PONum},
    beforeSend:function(){
     $('#saveProjectPurchasesWithdraw').prop('disabled', true);
    },
    success: function(saveWithdrawPurchTransaction){
     if(saveWithdrawPurchTransaction == 1){
      alert("Data Saved");
      $("#withdrawProjectPurchasesDate").val('');
      $("#accountName1").val('');
      $("#withdrawProjectPurchasesRecipient").val('');
      $("#withdrawProjectPurchasesAmount").val('');
      $("#withdrawProjectPurchasesDiscrebtion").val('');
      $('#saveProjectPurchasesWithdraw').prop('disabled', false);
     }
     else{
      alert(saveWithdrawPurchTransaction);
     }
    }
   });
  }
  return false;
 });
});
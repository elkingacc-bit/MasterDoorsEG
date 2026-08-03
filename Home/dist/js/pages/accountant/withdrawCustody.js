$(document).ready(function(){
 $("#custodyData").hide();
 $("#saveWithdrawCustody").hide();
 $("#saveCashBackCustody").hide();  
 $("#withdrawCustodyRecipient").load("dist/php/empCode.php");
 $("#projectsCustodyList").load("dist/php/projectsList2.php");
 //--------------------*{}*--------------------\\
 $("#projectsCustodyList").change(function(){
  $("#withdrawCustodyRecipient").show();
 });
 //--------------------*{}*--------------------\\
 $("#withdrawCustodyRecipient").change(function(){
  var empName =$(this).val();
  $.ajax({
   url:'dist/php/cheackWithdrawCustody.php',
   type:"POST",
   data:{fName:empName},
   success: function(custodyCheack){
    $("#projectsCustodyList").prop('disabled',true);
    $("#withdrawCustodyRecipient").prop('disabled',true);
    if(custodyCheack == 3){
     $("#custodyData").show();
     $("#saveWithdrawCustody").show();
    }
    else{
     $("#custodyData").html(custodyCheack);
     $("#saveCashBackCustody").show();
     $("#custodyData").show();
    }  
   }
  });
 });
 //--------------------*{}*--------------------\\
 $("#saveWithdrawCustody").click(function(){
  var PONum = $('#projectsCustodyList :selected').val();
  var recipient = $("#withdrawCustodyRecipient").val();
  var actionDate = $("#withdrawCustodyDate").val();
  var amount = $("#withdrawCustodyAmount").val();
  var casher= $("#trasuty").val();
  var discrebtion = $("#withdrawCustodyDiscrebtion").val();
  if( actionDate == ''){
   alert ("Do'not Leave Date Blank.");
  }
  else if(amount == ''){
   alert ("Do'not Leave Amount Blank.");
  }  
  else if(discrebtion == ''){
   alert ("Do'not Leave Discrebtion Blank.");
  }  
  else{
   $.ajax({ 
    url:'dist/php/saveNewWithdrawCustody.php',
    type:"POST",
    data:{fDate:actionDate,frecipient:recipient,famount:amount,fdiscrebtion:discrebtion,poNumber:PONum},
    beforeSend:function(){
     $('#saveWithdrawCustody').prop('disabled', true);
    },
    success: function(saveWithdrawOtherTransaction){
     if(saveWithdrawOtherTransaction == 1){
      alert("Data Saved ");
      $("#withdrawCustodyRecipient").hide();
      $("#custodyData").hide();
      $("#withdrawCustodyRecipient").load("dist/php/empCode.php");
      $("#projectsCustodyList").load("dist/php/projectsList2.php");
      $("#withdrawCustodyDate").val('');
      $("#withdrawCustodyAmount").val('');
      $("#withdrawCustodyDiscrebtion").val('');
      $('#saveWithdrawCustody').prop('disabled', false);
      $("#projectsCustodyList").prop('disabled', false);
      $("#withdrawCustodyRecipient").prop('disabled', false);
     }
     else if(saveWithdrawOtherTransaction == 2){
alert("Balance Not Avaliable");

     }
     else{
      alert(saveWithdrawOtherTransaction);
     }
    }
   });
  }
  return false;
 });
 //--------------------*{}*--------------------\\
 $("#saveCashBackCustody").click(function(){
  var PONum = $('#projectsCustodyList :selected').val();
  var emp = $("#withdrawCustodyRecipient").val();
  $.ajax({
   url:'dist/php/getWithdrawCustodyData.php',
   type:"POST",
   data:{frecipient:emp,poNumber:PONum},
   success: function(getWithdrawCustody){
    $(".modal-body").html(getWithdrawCustody);
    $("#custodyModalCashBack").modal('show');  
   }
  });
  return false;
 });
});
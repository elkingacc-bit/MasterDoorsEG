$(document).ready(function(){
$("#withdrawOtherExpensesRecipient").load("dist/php/empCode.php");  
$("#accountName3").load("dist/php/accountantCodeProcessing.php");
$("#saveOtherExpensesWithdraw").click(function(){
var PONum = $('#projectsId').val();
var actionDate = $("#withdrawOtherExpensesDate").val();
var accName = $("#accountName3").val();
var recipient = $("#withdrawOtherExpensesRecipient").val();
var amount = $("#withdrawOtherExpensesAmount").val();
var discrebtion = $("#withdrawOtherExpensesDiscrebtion").val();
if( actionDate == ''){ alert ("Do'not Leave Date Blank.");}
else if(accName == ''){ alert ("Do'not Leave Account Name Blank.");}  
else if(recipient == ''){ alert ("Do'not Leave Recipient Blank.");}
else if(amount == ''){ alert ("Do'not Leave Amount Blank.");}  
else if(discrebtion == ''){ alert ("Do'not Leave Discription Blank.");}  
else{
$.ajax({ 
url:'dist/php/saveNewProjectWithdraw.php',
type:"POST",
data:{fDate:actionDate,fCode:accName,frecipient:recipient,famount:amount,fdiscrebtion:discrebtion,poNumber:PONum},
beforeSend:function(){
$('#saveOtherExpensesWithdraw').prop('disabled', true);
},
success: function(saveWithdrawOtherTransaction){
if(saveWithdrawOtherTransaction == 1){
alert("Data Saved");
$("#withdrawOtherExpensesDate").val('');
$("#accountName3").val('');
$("#withdrawOtherExpensesRecipient").val('');
$("#withdrawOtherExpensesAmount").val('');
$("#withdrawOtherExpensesDiscrebtion").val('');
$('#saveOtherExpensesWithdraw').prop('disabled', false);
}
else{
alert(saveWithdrawOtherTransaction);
}
}
});
}
return false;
});
});
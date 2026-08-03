$(document).ready(function(){
 $("#customersPoList").load("dist/php/wonCustomerPoNoInvoiceList.php");
 //--------------------*{ Get Customer Po Data }*--------------------\\
 $("#invData").click(function(){
  var data = {};
  $("#customersPoList option").each(function(i,el) {  
   data[$(el).data("value")] = $(el).val();
  });
  console.log(data, $("#customersPoList option").val());
  var suplierCode = $("#chooseCustomersPoList").val();
  var jopRef = $('#customersPoList [value="' + suplierCode + '"]').data('value');
  var suplierChosenValideate = $('#customersPoList [value="' + suplierCode + '"]');  
  if(suplierChosenValideate.length <= 0){
   alert('Please Choose Customer name form the list');
   $("#chooseCustomersPoList").css("border-color","red");
   setTimeout(function(){
    $("#chooseCustomersPoList").css("border-color","#EBEBEB");               
    $("#chooseCustomersPoList").val(''); 
    $("#chooseCustomersPoList").focus();             
   }, 1500);
  }
  $.ajax({
   url:'dist/php/getCustomerPoData.php',
   type:"POST",
   data:{jopId:jopRef},
   success: function(customerPoData){
    $(".data_display").html(customerPoData);
   }
  });
 });
});
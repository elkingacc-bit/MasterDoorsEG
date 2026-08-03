$(document).ready(function(){
  $("#allVATTransaction").click(function(){
   $.ajax({ 
    url:'dist/php/taxStatment.php',
    type:"POST",
    success: function(vatStatmentData){
     $(".data_display").html('');
     $(".data_display").html(vatStatmentData); 
    }
   });
   return false;
  });

 $("#allHoldingTaxTransaction").click(function(){
   $.ajax({ 
    url:'dist/php/allHoldingTaxStatment.php',
    type:"POST",
    success: function(holdingTaxStatmentData){
     $(".data_display").html('');
     $(".data_display").html(holdingTaxStatmentData); 
    }
   });
   return false;
  });
  

 });
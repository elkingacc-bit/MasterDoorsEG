$(document).ready(function(){
 $.ajax({ 
  url:'dist/php/Acc/allAdvanceData.php',
  type:"POST",
  success: function(advanceData){
   $(".advanceReport").html('');
   $(".advanceReport").html(advanceData); 
  }
 });
});
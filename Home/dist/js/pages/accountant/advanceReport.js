$(document).ready(function(){
 $.ajax({ 
  url:'dist/php/allAdvanceData.php',
  type:"POST",
  success: function(advanceData){
   $(".advanceReport").html('');
   $(".advanceReport").html(advanceData); 
  }
 });
});
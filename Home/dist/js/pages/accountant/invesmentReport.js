$(document).ready(function(){
 $.ajax({ 
  url:'dist/php/Acc/allInvesmentYear.php',
  type:"POST",
  success: function(yearlyInvesmentData){
   $(".InvesmentReport").html('');
   $(".InvesmentReport").html(yearlyInvesmentData); 
  }
 });
});
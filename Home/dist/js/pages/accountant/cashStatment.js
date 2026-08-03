$(document).ready(function(){
    
   $("#limitTransaction").click(function(){
    $(".limitTransaction").css('display','block');    
   });

  $("#allCashTransaction").click(function(){
   $.ajax({ 
    url:'dist/php/allCashStatment.php',
    type:"POST",
    success: function(bankStatmentData){
     $(".data_display").html('');
     $(".data_display").html(bankStatmentData); 
    }
   });
   return false;
  });


  $("#showCashStatment").click(function(){
   var sDate =$('#startDate').val();
   var eDate =$('#endDate').val();
   if(sDate == ""){
    alert('Dont Skip Start Date Empty');
   }
   else if(eDate == ""){
    alert('Dont Skip End Date Empty');
   }
   else if(eDate < sDate){
    alert('Must End Date Bigger than Start Date');
   }
   else{
    $.ajax({ 
     url:'dist/php/cashStatment.php',
     type:"POST",
     data:{startDate:sDate,endDate:eDate},
     success: function(bankStatmentData){
      $(".data_display").html('');
      $(".data_display").html(bankStatmentData); 
     }
    });
   }
   return false;
  });
 });
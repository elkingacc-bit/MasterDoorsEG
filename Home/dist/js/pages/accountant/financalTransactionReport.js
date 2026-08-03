$(document).ready(function(){
 $("#financelTransactionYear").load('dist/php/years.php');
  $("#monthFinancelTransaction").click(function(){
  $(".financelTransactionData").html('');  
  $(".monthFinancelTransaction").css('display','block');    
 });
 $("#allFinancelTransaction").click(function(){
  $(".financelTransactionData").html('');
  $(".monthFinancelTransaction").css('display','none');
 });
 //
 $("#showFinancelTransaction").click(function(){
  var yearC =$('#financelTransactionYear').val();
  var monthC =$('#financelTransactionMonth').val();
  if(yearC == ""){
   alert('Dont Skip Year Empty');
  }
  else if(monthC == ""){
   alert('Dont Skip Quarter Empty');
  }
  else{
   $.ajax({ 
    url:'dist/php/allFinancelTransactionMonth.php',
    type:"POST",
    data:{startYear:yearC,startQuarter:monthC},
    success: function(monthFinancelTransactionData){
     $(".monthFinancelTransaction").css('display','none');
     $(".financelTransactionData").html('');
     $(".financelTransactionData").html(monthFinancelTransactionData); 
    }
   });
  }
 });
 $("#allFinancelTransaction").click(function(){
  $.ajax({ 
   url:'dist/php/allFinancelTransaction.php',
   type:"POST",
   success: function(monthFinancelTransactionData){
    $(".monthFinancelTransaction").css('display','none');
    $(".financelTransactionData").html('');
    $(".financelTransactionData").html(monthFinancelTransactionData); 
   }
  });
 });

});
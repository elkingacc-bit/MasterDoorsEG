$(document).ready(function(){
 $("#accountName").load("dist/php/Acc/allSuppliers.php");
 $(".supplierData").load("dist/php/Acc/suppliersDashboard.php");
 $("#limitSupplierTransaction").click(function(){
  $(".limitSupplierTransaction").css('display','block');    
 });
 $("#allSupplierTransaction").click(function(){
  var acountCode=$('#accountName').val();
  if(acountCode == ""){
   alert('Dont Skip Name Empty');
  }
  else{
   $.ajax({ 
    url:'dist/php/Acc/allSupplierStatmentData.php',
    type:"POST",
    data:{accCode:acountCode},
    success: function(supplierAllStatmentData){
     $(".data_display").html('');
     $(".data_display").html(supplierAllStatmentData); 
    }
   });     
  }
 });
 $("#valiedSupplierTransaction").click(function(){
  var acountCode=$('#accountName').val();
  if(acountCode == ""){
   alert('Dont Skip Name Empty');
  }
  else{
   $.ajax({ 
    url:'dist/php/Acc/allSupplierBalanceData.php',
    type:"POST",
    data:{accCode:acountCode},
    success: function(supplierBalancedStatmentData){
     $(".data_display").html('');
     $(".data_display").html(supplierBalancedStatmentData); 
    }
   });     
  }
 });
 $("#showSupplierStatment").click(function(){
  var sDate =$('#startDate').val();
  var eDate =$('#endDate').val();
  var acountCode=$('#accountName').val();
  if(acountCode == ""){
   alert('Dont Skip Name Empty');
  }
  else if(sDate == ""){
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
    url:'dist/php/Acc/supplierStatment.php',
    type:"POST",
    data:{startDate:sDate,endDate:eDate,accCode:acountCode},
     success: function(supplierStatmentData){
      $(".data_display").html('');
      $(".data_display").html(supplierStatmentData); 
     }
    });
   }
   return false;
  });
 });
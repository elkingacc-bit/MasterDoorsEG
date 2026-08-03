$(document).ready(function(){
   $("#bankName").load("dist/php/bankCode.php");
   $("#bankName2").load("dist/php/bankCode.php");  
   $("#allBank").click(function(){
    $.ajax({ 
     url:'dist/php/banksBalance.php',
     type:"POST",
     success: function(allBankStatmentData){
      $(".data_display").html('');
      $(".data_display").html(allBankStatmentData); 
     }
    }); 
   });
   $("#allTransaction").click(function(){
    $(".bank1").css('display','none');
    $(".bank2").css('display','block');    
   });
   $("#limitTransaction").click(function(){
    $(".bank2").css('display','none');
    $(".bank1").css('display','block');    
   });
   $("#showBankStatment").click(function(){
    var sDate =$('#startDate').val();
    var eDate =$('#endDate').val();
    var mybank=$('#bankName').val();
    if(mybank == ""){
     alert('Dont Skip Bank Name Empty');
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
      url:'dist/php/bankStatment.php',
      type:"POST",
      data:{startDate:sDate,endDate:eDate,bank:mybank},
      success: function(bankStatmentData){
       $(".data_display").html('');
       $(".data_display").html(bankStatmentData); 
      }
     });
    }
    return false;
   });
   $("#showAllBankStatment").click(function(){
    var mybank=$('#bankName2').val();
    if(mybank == ""){
     alert('Dont Skip Bank Name Empty');
    }
    else{
     $.ajax({ 
      url:'dist/php/bankAllStatment.php',
      type:"POST",
      data:{bank:mybank},
      success: function(bankStatmentData){
       $(".data_display").html('');
       $(".data_display").html(bankStatmentData); 
      }
     });
    }
    return false;
   });
  });
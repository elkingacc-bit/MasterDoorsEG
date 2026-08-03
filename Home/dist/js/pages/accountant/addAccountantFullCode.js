$(document).ready(function(){
$('.oldCodeData').load('dist/php/allFullCode.php');
 //
 $("#level1f").load("dist/php/levelOneAccountantCode.php");
 //
 $("#level1f").change(function(){
  var level1Name = $('#level1f').val();
  $.ajax({ 
   url:'dist/php/levelTowAccountantCode.php',
   type:"POST",
   data:{firstCode:level1Name},
   success: function(allLevel2Code){
    $("#level2").html(allLevel2Code);
   }
  });
  return false;
 });
 //
 $("#level2").change(function(){
  var level1Code = $('#level1f').val();
  var level2code = $('#level2').val();
  $.ajax({ 
   url:'dist/php/levelThreeAccountantCode.php',
   type:"POST",
   data:{firstCode:level1Code,secoundCode:level2code},
   success: function(getLevel3Code){
    $("#level3").html(getLevel3Code);
   }
  });
  return false;
 });
 //
 $("#AccountName").change(function(){
  var level1Code = $('#level1f').val();
  var level2code = $('#level2').val();
  var level3code = $('#level3').val();
  $.ajax({ 
   url:'dist/php/getAccountantCodeNewCode.php',
   type:"POST",
   data:{firstCode:level1Code,secoundCode:level2code,thierdCode:level3code},
   success: function(getLevel4Code){
    if(getLevel4Code == 0){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html("You Already Been Registered All Limit For This Level");
    }
    else{
     var Lev4Code = getLevel4Code.replace(/^\s+|\s+$|\s+(?=\s)/g, "");  
     $("#accountantFullCode").val(Lev4Code);
     $('#saveAccountCode').prop('disabled', false);    
    }
   }
  });
  return false;
 });
 //
 $("#saveAccountCode").click(function(){
  var fiCode = $('#level1f').val();
  var secondCode = $('#level2').val();
  var thierdCode = $('#level3').val();
  var accName = $('#AccountName').val();
  var accCode = $('#accountantFullCode').val();
  if(fiCode == ""){
   $('.msg').removeClass('alert alert-success');
   $('.msg').addClass('alert alert-danger');
   $('.msg').html('Dont Skip branch Name Empty');
   $('#level1f').focus();
   $(".msg").fadeOut(5000);
  }
  else if(secondCode == ""){
   $('.msg').removeClass('alert alert-success');
   $('.msg').addClass('alert alert-danger');
   $('.msg').html('Dont Skip Second Name Empty');
   $('#level2').focus();
   $(".msg").fadeOut(5000);
  }
  else if(thierdCode == ""){
   $('.msg').removeClass('alert alert-success');
   $('.msg').addClass('alert alert-danger');
   $('.msg').html('Dont Skip Thierd Name Empty');
   $('#level3').focus();
   $(".msg").fadeOut(5000);
  }
  else if(accName == ""){
   $('.msg').removeClass('alert alert-success');
   $('.msg').addClass('alert alert-danger');
   $('.msg').html('Dont Skip Account Name Empty');
   $('#AccountName').focus();
   $(".msg").fadeOut(5000);
  }
  else if(accCode == ""){
   $('.msg').removeClass('alert alert-success');
   $('.msg').addClass('alert alert-danger');
   $('.msg').html('Dont Skip Account Code Empty');
   $('#accountantFullCode').focus();
   $(".msg").fadeOut(5000);
  }
  else{
   $.ajax({ 
    url:'dist/php/saveNewFullAccountCode.php',
    type:"POST",
    data:{fCode:fiCode,scode:secondCode,tcode:thierdCode,aName:accName,aCode:accCode},
    beforeSend:function(){
     $('#saveAccountCode').prop('disabled', true);
    },
    success: function(savedepartmentDone){
     if(savedepartmentDone == 1){
      $('.msg').removeClass('alert alert-danger');
      $('.msg').addClass('alert alert-success');
      $('.msg').html("Data Saved");
      $(".msg").fadeOut(5000);
      $('.card-text').load('dist/html/accountantCode.html');
     }
     else{
      $('.msg').removeClass('alert alert-success');
      $('.msg').addClass('alert alert-danger');
      $('.msg').html(savedepartmentDone);
      $(".msg").fadeOut(5000);
     }
    }
   });
  }
  return false;
 });
});
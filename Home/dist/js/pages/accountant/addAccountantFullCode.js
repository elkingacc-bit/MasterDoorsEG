$(document).ready(function(){
$('.oldCodeData').load('dist/php/Acc/allFullCode.php');
 //
 $("#level1f").load("dist/php/Acc/levelOneAccountantCode.php");
 //
 $("#level1f").change(function(){
  var level1Name = $('#level1f').val();
  $.ajax({
   url:'dist/php/Acc/levelTowAccountantCode.php',
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
   url:'dist/php/Acc/levelThreeAccountantCode.php',
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
   url:'dist/php/Acc/getAccountantCodeNewCode.php',
   type:"POST",
   data:{firstCode:level1Code,secoundCode:level2code,thierdCode:level3code},
   success: function(getLevel4Code){
    if(getLevel4Code == 0){
     $('.msg').removeClass('alert-success');
     $('.msg').addClass('alert-danger');
     $(".msg").hide().html("You Already Been Registered All Limit For This Level").fadeIn(150);
     $(".msg").delay(3000).fadeOut(600);
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
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip branch Name Empty').fadeIn(150);
   $('#level1f').focus();
   $(".msg").delay(5000).fadeOut(600);
  }
  else if(secondCode == ""){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Second Name Empty').fadeIn(150);
   $('#level2').focus();
   $(".msg").delay(5000).fadeOut(600);
  }
  else if(thierdCode == ""){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Thierd Name Empty').fadeIn(150);
   $('#level3').focus();
   $(".msg").delay(5000).fadeOut(600);
  }
  else if(accName == ""){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Account Name Empty').fadeIn(150);
   $('#AccountName').focus();
   $(".msg").delay(5000).fadeOut(600);
  }
  else if(accCode == ""){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Account Code Empty').fadeIn(150);
   $('#accountantFullCode').focus();
   $(".msg").delay(5000).fadeOut(600);
  }
  else{
   var $btn = $('#saveAccountCode');
   var originalText = $btn.text();
   $.ajax({
    url:'dist/php/Acc/saveNewFullAccountCode.php',
    type:"POST",
    data:{fCode:fiCode,scode:secondCode,tcode:thierdCode,aName:accName,aCode:accCode},
    beforeSend:function(){
     $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    },
    success: function(savedepartmentDone){
     $btn.prop('disabled', false).text(originalText);
     if(savedepartmentDone == 1){
      $('.msg').removeClass('alert-danger');
      $('.msg').addClass('alert-success');
      $(".msg").hide().html("Data Saved").fadeIn(150);
      $(".msg").delay(2000).fadeOut(600);
      $('.card-text').load('dist/html/Acc/accountantCode.html');
     }
     else{
      $('.msg').removeClass('alert-success');
      $('.msg').addClass('alert-danger');
      $(".msg").hide().html(savedepartmentDone).fadeIn(150);
      $(".msg").delay(5000).fadeOut(600);
     }
    }
   });
  }
  return false;
 });
});

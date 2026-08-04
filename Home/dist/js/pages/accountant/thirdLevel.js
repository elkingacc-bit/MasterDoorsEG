$(document).ready(function(){

$('.oldThirdtData').load('dist/php/Acc/allThirdCode.php');


 $('#saveNewAsset').prop('disabled', true);
 // Loade First Code Data
 $("#level1f").load("dist/php/Acc/levelOneAccountantCode.php");
 // Get Second Code Data Mathed With First Code
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
 // Cheak Avilable Third Category Lens And Get Theird Level Code
 $("#AssetsName").change(function(){
  var level1Code = $('#level1f').val();
  var level2code = $('#level2').val();
  $.ajax({
   url:'dist/php/Acc/getLevelThreeNewCode.php',
   type:"POST",
   data:{firstCode:level1Code,secoundCode:level2code},
   success: function(getLevel3Code){
    if(getLevel3Code == 0){
     $('.msg').removeClass('alert-success');
     $('.msg').addClass('alert-danger');
     $(".msg").hide().html("You Already Been Registered All Limit For This Level").fadeIn(150);
     $(".msg").delay(3000).fadeOut(600);
    }
    else{
     var Lev3Code = getLevel3Code.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
     $("#level3").val(Lev3Code);
     $('#saveNewAsset').prop('disabled', false);
    }
   }
  });
  return false;
 });
 // Saved New Thierd Accountant Data
 $("#saveNewAsset").click(function(){
  var firstCode = $('#level1f').val();
  var secoundCode = $('#level2').val();
  var therdName = $('#AssetsName').val();
  var therdCode = $('#level3').val();
  if(firstCode == ""){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip First Name Empty').fadeIn(150);
   $('#level1f').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(secoundCode == ""){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Secound Name Empty').fadeIn(150);
   $('#level2').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else if(therdName == ""){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Third Name Empty').fadeIn(150);
   $('#AssetsName').focus();
   $(".msg").delay(3000).fadeOut(600);
  }
  else{
   var $btn = $('#saveNewAsset');
   var originalText = $btn.text();
   $.ajax({
    url:'dist/php/Acc/saveNewThierdlCode.php',
    type:"POST",
    data:{fCode:firstCode,scode:secoundCode,tName:therdName,tcode:therdCode},
    beforeSend:function(){
     $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    },
    success: function(saveThierdlCodeDone){
     $btn.prop('disabled', false).text(originalText);
     if(saveThierdlCodeDone == 1){
      $('.msg').removeClass('alert-danger');
      $('.msg').addClass('alert-success');
      $(".msg").hide().html("Data Saved").fadeIn(150);
      $('#level1f').val("");
      $('#level2').val("");
      $('#AssetsName').val("");
      $('#level3').val("");
      $('#assetRefCode').val("");
      $(".msg").delay(2000).fadeOut(600);
      $('.card-text').load('dist/html/Acc/thierdLevel.html');
     }
     else{
      $('.msg').removeClass('alert-success');
      $('.msg').addClass('alert-danger');
      $(".msg").hide().html(saveThierdlCodeDone).fadeIn(150);
      $(".msg").delay(5000).fadeOut(600);
     }
    }
   });
  }
  return false;
 });
});

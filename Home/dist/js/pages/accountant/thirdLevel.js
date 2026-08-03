$(document).ready(function(){

$('.oldThirdtData').load('dist/php/allThirdCode.php');


 $('#saveNewAsset').prop('disabled', true);
 // Loade First Code Data
 $("#level1f").load("dist/php/levelOneAccountantCode.php");
 // Get Second Code Data Mathed With First Code 
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
 // Cheak Avilable Third Category Lens And Get Theird Level Code
 $("#AssetsName").change(function(){
  var level1Code = $('#level1f').val();
  var level2code = $('#level2').val();
  $.ajax({ 
   url:'dist/php/getLevelThreeNewCode.php',
   type:"POST",
   data:{firstCode:level1Code,secoundCode:level2code},
   success: function(getLevel3Code){
    if(getLevel3Code == 0){
     $('.msg').removeClass('alert-success');
     $('.msg').addClass('alert-danger');
     $(".msg").html("You Already Been Registered All Limit For This Level");
     $(".msg").fadeOut(3000);
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
   $('.msg').removeClass('alert alert-success');
   $('.msg').addClass('alert alert-danger');
   $('.msg').html('Dont Skip First Name Empty');
   $('#level1').focus();
   $(".msg").fadeOut(3000);
  }
  else if(secoundCode == ""){
   $('.msg').removeClass('alert alert-success');
   $('.msg').addClass('alert alert-danger');
   $('.msg').html('Dont Skip Secound Name Empty');
   $('#AssetsLevel2Name').focus();
   $(".msg").fadeOut(3000);
  }
  else if(therdName == ""){
   $('.msg').removeClass('alert alert-success');
   $('.msg').addClass('alert alert-danger');
   $('.msg').html('Dont Skip Third Name Empty');
   $('#AssetsLevel2Name').focus();
   $(".msg").fadeOut(3000);
  }
  else{
   $.ajax({ 
    url:'dist/php/saveNewThierdlCode.php',
    type:"POST",
    data:{fCode:firstCode,scode:secoundCode,tName:therdName,tcode:therdCode},
    beforeSend:function(){
     $('#saveNewAsset').prop('disabled', true);
    },
    success: function(saveThierdlCodeDone){
     if(saveThierdlCodeDone == 1){       
      $('.msg').removeClass('alert-danger');
      $('.msg').addClass('alert-success');
      $(".msg").html("Data Saved");
      $('#level1f').val("");
      $('#level2').val("");
      $('#AssetsName').val("");
      $('#level3').val("");
      $('#assetRefCode').val("");
      $(".msg").fadeOut(3000);
      $('.card-text').load('dist/html/thierdLevel.html');
     }
     else{
      $('.msg').removeClass('alert-success');
      $('.msg').addClass('alert-danger');
      $(".msg").html(saveThierdlCodeDone);
      $(".msg").fadeOut(5000);
     }
    }
   });
  }
  return false;
 });
});
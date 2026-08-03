$(document).ready(function(){
$('.oldSecoundtData').load('dist/php/allSecoundCode.php');


   $('#saveLivel2').prop('disabled', true);
   $("#level1").load("dist/php/levelOneAccountantCode.php");
   $("#AssetsLevel2Name").change(function(){
    var level1Name = $('#level1').val();
    $.ajax({
     url:'dist/php/getLevelTowNewCode.php',
     type:"POST",
     data:{firstCode:level1Name},
     beforeSend:function(){
      $('#saveLivel2').prop('disabled', true);
     },
     success: function(getLevel2Code){
      if(getLevel2Code == 0){
       $('.msg').removeClass('alert-success');
       $('.msg').addClass('alert-danger');
       $(".msg").hide().html("You Already Been Registered All Limit For This Level").fadeIn(150);
       $(".msg").delay(5000).fadeOut(600);
      }
      else{
       var Lev2Code = getLevel2Code.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
       $("#AssetsLevel2Code").val("");
       $("#AssetsLevel2Code").val(Lev2Code);
       $('#saveLivel2').prop('disabled', false);
      }
     }
    });
    return false;
   });
   $("#saveLivel2").click(function(){
    var firstCode = $('#level1').val();
    var secondName = $('#AssetsLevel2Name').val();
    var secondCode = $('#AssetsLevel2Code').val();
    if(firstCode == ""){
     $('.msg').removeClass('alert-success');
     $('.msg').addClass('alert-danger');
     $('.msg').hide().html('Dont Skip branch Name Empty').fadeIn(150);
     $('#level1').focus();
    }
    else if(secondName == ""){
     $('.msg').removeClass('alert-success');
     $('.msg').addClass('alert-danger');
     $('.msg').hide().html('Dont Skip branch Name Empty').fadeIn(150);
     $('#AssetsLevel2Name').focus();
    }
    else{
     var $btn = $('#saveLivel2');
     var originalText = $btn.text();
     $.ajax({
      url:'dist/php/saveNewSecondCode.php',
      type:"POST",
      data:{fCode:firstCode,scode:secondCode,sName:secondName},
      beforeSend:function(){
       $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
      },
      success: function(savedepartmentDone){
       $btn.prop('disabled', false).text(originalText);
       if(savedepartmentDone == 1){
        $('.msg').removeClass('alert-danger');
        $('.msg').addClass('alert-success');
        $(".msg").hide().html("Data Saved").fadeIn(150);
        $('#level1').val("");
        $('#AssetsLevel2Name').val("");
        $('#AssetsLevel2Code').val("");
        $(".msg").delay(2000).fadeOut(600);
        $('.card-text').load('dist/html/secondLevel.html');
       }
       else{
        $('.msg').removeClass('alert-success');
        $('.msg').addClass('alert-danger');
        $(".msg").hide().html(savedepartmentDone).fadeIn(150);
        $(".msg").delay(3000).fadeOut(600);
       }
      }
     });
    }
    return false;
   });
  });

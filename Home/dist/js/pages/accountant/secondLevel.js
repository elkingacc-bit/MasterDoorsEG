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
       $(".msg").html("You Already Been Registered All Limit For This Level");
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
     $('.msg').html('Dont Skip branch Name Empty');
     $('#level1').focus();
    }
    else if(secondName == ""){
     $('.msg').removeClass('alert-success');
     $('.msg').addClass('alert-danger');
     $('.msg').html('Dont Skip branch Name Empty');
     $('#AssetsLevel2Name').focus();
    }
    else{
     $.ajax({ 
      url:'dist/php/saveNewSecondCode.php',
      type:"POST",
      data:{fCode:firstCode,scode:secondCode,sName:secondName},
      beforeSend:function(){
       $('#saveLivel2').prop('disabled', true);
      },
      success: function(savedepartmentDone){
       if(savedepartmentDone == 1){
        $('.msg').removeClass('alert-danger');
        $('.msg').addClass('alert-success');
        $(".msg").html("Data Saved");
        $(".msg").fadeIn(1000);
        $('#level1').val("");
        $('#AssetsLevel2Name').val("");
        $('#AssetsLevel2Code').val(""); 
        $(".msg").fadeOut(3000);
        $('.card-text').load('dist/html/secondLevel.html');
       }
       else{
        $('.msg').removeClass('alert-success');
        $('.msg').addClass('alert-danger');
        $(".msg").html(savedepartmentDone);
        $(".msg").fadeOut(3000);
       }
      }
     });
    }
    return false;
   });
  }); 
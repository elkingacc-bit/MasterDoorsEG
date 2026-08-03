 $(document).ready(function(){
      $("#saveSalesAdvance").click(function(){
    var investmentDate = $('#investmentDate').val();
    var investmentAmount= $('#investmentAmount').val();
var investmentQuant= $('#investmentQun').val();
    var investmentGroup = $('#investmentGroup').val();
    var investmentDis= $('#investmentDescription').val();
     if(investmentDate == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Date Empty');
     $(".msg").fadeOut(5000);
    }
    else if(investmentAmount == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Amount Empty');
     $(".msg").fadeOut(5000);
    }
    else if(investmentQuant == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Quantaty Empty');
     $(".msg").fadeOut(5000);
    }
    else if(investmentGroup == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Category Empty');
     $(".msg").fadeOut(5000);
    }
    else if(investmentDis == ''){
     $('.msg').removeClass('alert alert-success');
     $('.msg').addClass('alert alert-danger');
     $('.msg').html('Dont Skip Discription Empty');
     $(".msg").fadeOut(5000);
    }
    else{
     $.ajax({
      url:"dist/php/saveNewASalesInvestment.php",
      type:"POST",
      data:{fDate:investmentDate,fAmount:investmentAmount,fGroup:investmentGroup,fDis:investmentDis,fQun:investmentQuant},
      success: function(addInvestmentDone){
       if(addInvestmentDone == 1){
        $('.msg').removeClass('alert alert-danger');
        $('.msg').addClass('alert alert-success');
        $('.msg').html("Data Saved");
        $(".msg").fadeOut(3000);
        $('#investmentDate').val('');
        $('#investmentAmount').val('');
        $('#investmentQun').val('');
        $('#investmentGroup').val('');
        $('#investmentDescription').val('');
       }
       else{
        $('.msg').removeClass('alert alert-success');
        $('.msg').addClass('alert alert-danger');
        $('.msg').html(addInvestmentDone);
        $(".msg").fadeOut(9000);
       }
      }
     });
    }
  return false;
 });
       });

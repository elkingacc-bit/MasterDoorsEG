$(document).ready(function(){
  var monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];

  $("#budgetAccount").load("dist/php/Acc/allCodeSelect.php");

  var thisYear = new Date().getFullYear();
  var yearOptions = "";
  for (var y = thisYear + 1; y >= thisYear - 2; y--) {
   yearOptions += "<option value='" + y + "'" + (y === thisYear ? " selected" : "") + ">" + y + "</option>";
  }
  $("#budgetYear").html(yearOptions);

  var monthOptions = "";
  var thisMonth = new Date().getMonth() + 1;
  for (var m = 1; m <= 12; m++) {
   monthOptions += "<option value='" + m + "'" + (m === thisMonth ? " selected" : "") + ">" + monthNames[m-1] + "</option>";
  }
  $("#budgetMonth").html(monthOptions);

  function loadBudgetList(){
   $(".budgetListResult").load("dist/php/Acc/allBudgetData.php");
  }
  loadBudgetList();

  $("#saveNewBudget").click(function(){
   var accountCode = $("#budgetAccount").val();
   var year = $("#budgetYear").val();
   var month = $("#budgetMonth").val();
   var amount = $("#budgetAmount").val();

   if(accountCode == ""){
    $('.msg').removeClass('alert-success');
    $('.msg').addClass('alert-danger');
    $('.msg').html('Choose an Account');
    $('#budgetAccount').focus();
    $(".msg").fadeOut(5000);
   }
   else if(amount == "" || isNaN(amount) || Number(amount) <= 0){
    $('.msg').removeClass('alert-success');
    $('.msg').addClass('alert-danger');
    $('.msg').html('Enter a Valid Budget Amount');
    $('#budgetAmount').focus();
    $(".msg").fadeOut(5000);
   }
   else{
    $.ajax({
     url:'dist/php/Acc/saveNewBudget.php',
     type:"POST",
     data:{accountCode:accountCode, year:year, month:month, amount:amount},
     beforeSend:function(){
      $('#saveNewBudget').prop('disabled', true);
     },
     success: function(response){
      $('#saveNewBudget').prop('disabled', false);
      if(response == 1){
       $('.msg').removeClass('alert-danger');
       $('.msg').addClass('alert-success');
       $('.msg').html('Budget Saved');
       $('#budgetAmount').val("");
       $(".msg").fadeOut(5000);
       loadBudgetList();
      }
      else{
       $('.msg').removeClass('alert-success');
       $('.msg').addClass('alert-danger');
       $('.msg').html(response);
       $(".msg").fadeOut(9000);
      }
     }
    });
   }
   return false;
  });

  $(document).on('click', '.deleteBudgetLine', function(){
   var budgetId = $(this).data('id');
   $.ajax({
    url:'dist/php/Acc/deleteBudget.php',
    type:"POST",
    data:{budgetId:budgetId},
    success: function(response){
     if(response == 1){ loadBudgetList(); }
    }
   });
   return false;
  });
 });

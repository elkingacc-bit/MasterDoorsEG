$(document).ready(function(){
  $("#assetAccountCode").load("dist/php/Acc/allCodeSelect.php");

  $("#saveNewFixedAsset").click(function(){
   var assetName = $('#assetName').val().trim();
   var assetCategory = $('#assetCategory').val().trim();
   var accountCode = $('#assetAccountCode').val();
   var purchaseDate = $('#assetPurchaseDate').val();
   var cost = $('#assetCost').val();
   var usefulLife = $('#assetUsefulLife').val();
   var salvage = $('#assetSalvage').val();

   if(assetName == ""){
    $('.msg').removeClass('alert-success');
    $('.msg').addClass('alert-danger');
    $('.msg').html('Enter Asset Name');
    $('#assetName').focus();
    $(".msg").fadeOut(5000);
   }
   else if(purchaseDate == ""){
    $('.msg').removeClass('alert-success');
    $('.msg').addClass('alert-danger');
    $('.msg').html('Choose Purchase Date');
    $('#assetPurchaseDate').focus();
    $(".msg").fadeOut(5000);
   }
   else if(cost == "" || isNaN(cost) || Number(cost) <= 0){
    $('.msg').removeClass('alert-success');
    $('.msg').addClass('alert-danger');
    $('.msg').html('Enter a Valid Cost');
    $('#assetCost').focus();
    $(".msg").fadeOut(5000);
   }
   else if(usefulLife == "" || isNaN(usefulLife) || Number(usefulLife) <= 0){
    $('.msg').removeClass('alert-success');
    $('.msg').addClass('alert-danger');
    $('.msg').html('Enter a Valid Useful Life');
    $('#assetUsefulLife').focus();
    $(".msg").fadeOut(5000);
   }
   else{
    $.ajax({
     url:'dist/php/Acc/saveNewFixedAsset.php',
     type:"POST",
     data:{
      assetName:assetName, assetCategory:assetCategory, accountCode:accountCode,
      purchaseDate:purchaseDate, cost:cost, usefulLife:usefulLife, salvage:salvage
     },
     beforeSend:function(){
      $('#saveNewFixedAsset').prop('disabled', true);
     },
     success: function(response){
      $('#saveNewFixedAsset').prop('disabled', false);
      if(response == 1){
       $('.msg').removeClass('alert-danger');
       $('.msg').addClass('alert-success');
       $('.msg').html('Asset Saved');
       $('#assetName').val("");
       $('#assetCategory').val("");
       $('#assetPurchaseDate').val("");
       $('#assetCost').val("");
       $('#assetUsefulLife').val("");
       $('#assetSalvage').val("0");
       $(".msg").fadeOut(5000);
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
 });

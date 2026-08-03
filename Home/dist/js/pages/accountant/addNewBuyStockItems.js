$(document).ready(function(){
 $("#suppliers").load("dist/php/allSuppDDList.php");
 $(".reminingText").hide();
 $("#AddItems").hide();
 $("#closePage").hide();

 function validateField($field){
  var val = $.trim($field.val());
  var $group = $field.closest('.form-group');
  if(val === ''){
   $field.addClass('is-invalid');
   $group.find('.invalid-feedback').addClass('show');
  }
  else{
   $field.removeClass('is-invalid');
   $group.find('.invalid-feedback').removeClass('show');
  }
 }

 $("#invoiceNumber, #invoiceDate, #chooseSuppliers, #invoiceSuptotal, #vatType").on('blur change', function(){
  validateField($(this));
 });

 //
 $("#AddItems").click(function(){
  var invoiceNumber =$("#invoiceNumber").val();
  $.ajax({
   url:'dist/php/itemsToBuyStock.php',
   type:"POST",
   data:{invId:invoiceNumber},
   success: function(newItemsInvData){
    $("#items").html(newItemsInvData);
   }
  });
  $("#supplierInvoiceModal").modal('show');
 });
 //
 function recalcVat(){
  var taxType = $("#vatType").val();
  var supTotal=$("#invoiceSuptotal").val();
  if(taxType == ''){
   $("#invoiceVat").val(0);
  }
  else if(taxType == 1){
   var taxAmount=(Number(supTotal)*Number(.14));
   $("#invoiceVat").val(taxAmount.toFixed(2));
  }
  else if(taxType == 2){
   $("#invoiceVat").val(0);
  }
  var taxValue = $("#invoiceVat").val();
  var totInv = (Number(supTotal) + Number(taxValue));
  $("#invoiceTotal").val(totInv.toFixed(2));
 }
 $("#vatType").change(function(){
  recalcVat();
  validateField($(this));
 });
 $("#invoiceSuptotal").on('input', function(){
  recalcVat();
  validateField($(this));
 });
 //
 $("#saveInvoice").click(function(){
  var invNum = $("#invoiceNumber").val();
  var invDate = $("#invoiceDate").val();
  var data = {};
  $("#suppliers option").each(function(i,el) {
   data[$(el).data("value")] = $(el).val();
  });
  console.log(data, $("#suppliers option").val());
  var suplierCode = $("#chooseSuppliers").val();
  var invSuplier = $('#suppliers [value="' + suplierCode + '"]').data('value');
  var suplierChosenValideate = $('#suppliers [value="' + suplierCode + '"]');

  $("#invoiceNumber, #invoiceDate, #chooseSuppliers, #invoiceSuptotal, #vatType").each(function(){
   validateField($(this));
  });

  if(suplierChosenValideate.length <= 0){
   alert('Please Choose Suppliers name from the list');
   $("#chooseSuppliers").addClass('is-invalid');
   setTimeout(function(){
    $("#chooseSuppliers").removeClass('is-invalid');
    $("#chooseSuppliers").val('');
    $("#chooseSuppliers").focus();
   }, 1500);
  }
  var invSupTotal = $("#invoiceSuptotal").val();
  var invVat = $("#invoiceVat").val();
  var invTotal = $("#invoiceTotal").val();
  if(invNum == ''){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Invoice Number Empty').fadeIn(150);
  }
  else if(invDate == ''){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Invoice Date Empty').fadeIn(150);
  }
  else if(invSuplier == ''){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Supplier Name Empty').fadeIn(150);
  }
  else if(invSupTotal == ''){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip branch Name Empty').fadeIn(150);
  }
  else if(invVat == ''){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Tax Empty').fadeIn(150);
  }
  else if(invTotal == ''){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip branch Name Empty').fadeIn(150);
  }
  else{
   var $btn = $('#saveInvoice');
   var originalText = $btn.text();
   $.ajax({
    url:'dist/php/saveNewSuppliersInvoicStock.php',
    type:"POST",
    data:{finvNum:invNum,finvDate:invDate,finvSuplier:invSuplier,finvSupTotal:invSupTotal,finvVat:invVat,finvTotal:invTotal},
    beforeSend:function(){
     $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    },
    success: function(getSaveInvData){
     $btn.prop('disabled', false).text(originalText);
     if(getSaveInvData == 0){
      $('.msg').removeClass('alert-success');
      $('.msg').addClass('alert-danger');
      $('.msg').hide().html('This Transaction has Already Been Registered').fadeIn(150);
     }
     else{
      var invLast = getSaveInvData;
      $("#invoiceId").val(invLast);
      $.ajax({
       url:'dist/php/suppliersInvoiceItems.php',
       type:"POST",
       data:{inv:invLast},
       success: function(getInvoiceData){
        $("#itemsValue").val(invSupTotal);
        $(".invoiceData").html(getInvoiceData);
        $("#AddItems").fadeIn(150);
        $(".reminingText").fadeIn(150);
        $("#saveInvoice").hide();
       }
      });
     }
    }
   });
  }
  return false;
 });
 //
 $("#unitPrice").on('input', function(){
  var unitPrice = $(this).val();
  var qyt = $('#invoiceQyan').val();
  var sup=(Number(qyt)*Number(unitPrice));
  $("#supTotal").val(sup.toFixed(2));
 });
 //
 $("#invoiceQyan").on('input', function(){
  var qyt = $(this).val();
  var unitPrice = $('#unitPrice').val();
  var sup = (Number(qyt)*Number(unitPrice));
  $("#supTotal").val(sup.toFixed(2));
 });
 //
 $("#saveItems").click(function(){
  var data = {};
  $("#items option").each(function(i,el) {
   data[$(el).data("value")] = $(el).val();
  });
  console.log(data, $("#items option").val());
  var itemCode = $("#chooseItems").val();
  var itemId = $('#items [value="' + itemCode + '"]').data('value');
  var itemsChosenValideate = $('#items [value="' + itemCode + '"]');
  if(itemsChosenValideate.length <= 0){
   alert('Please Choose Items name from the list');
   $("#chooseItems").css("border-color","red");
   setTimeout(function(){
    $("#chooseItems").css("border-color","#EBEBEB");
    $("#chooseItems").val('');
    $("#chooseItems").focus();
   }, 1500);
  }
  var itemQ = $("#invoiceQyan").val();
  var iUP = $("#unitPrice").val();
  var inST = $("#supTotal").val();
  var inNu = $("#invoiceId").val();
  var invNum2 = $("#invoiceNumber").val();
  var invDate2 = $("#invoiceDate").val();
  var totalRef2 = $("#refTotal").val();
  var reminingAmount = $("#itemsValue").val();
  if(itemId == ''){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Items Name Empty').fadeIn(150);
  }
  else if(itemQ == ''){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Count Empty').fadeIn(150);
  }
  else if(iUP == ''){
   $('.msg').removeClass('alert-success');
   $('.msg').addClass('alert-danger');
   $('.msg').hide().html('Dont Skip Unit Price Empty').fadeIn(150);
  }
  else{
   var newreminingAmount =(Number(reminingAmount) + .1);
   if(Number(inST) > Number(reminingAmount)){
    alert('Total Amount More Than Invoice Price');
    $("#invoiceQyan").val('');
    $("#unitPrice").val('');
    $("#supTotal").val('');
   }
   else if(Number(inST) <= Number(newreminingAmount)){
    var $itemBtn = $('#saveItems');
    var itemBtnText = $itemBtn.text();
    $.ajax({
     url:'dist/php/saveItemsToStockInvoice.php',
     type:"POST",
     data:{itemsName:itemId,itemsCount:itemQ,unitPrice:iUP,itemTotalPrice:inST,lastId:inNu,invId:invNum2,dateInv:invDate2},
     beforeSend:function(){
      $itemBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
     },
     success: function(saveItemsInvoice){
      $itemBtn.prop('disabled', false).text(itemBtnText);
      var cuAmount=Number(newreminingAmount)-Number(inST);
      $("#itemsValue").val(cuAmount.toFixed(2));
      $('.msg').removeClass('alert-danger');
      $('.msg').addClass('alert-success');
      $('.msg').hide().html('Data Saved').fadeIn(150);
      $(".msg").delay(2000).fadeOut(600);
      var invLast = $("#invoiceId").val();
      $.ajax({
       url:'dist/php/suppliersInvoiceItems.php',
       type:"POST",
       data:{inv:invLast},
       success: function(getInvoiceData){
        $(".invoiceData").html(getInvoiceData);
        $("#AddItems").show();
        $("#saveItems").show();
        $("#chooseItems").val('');
        $("#invoiceQyan").val('');
        $("#unitPrice").val('');
        $("#supTotal").val('');
        $("#supplierInvoiceModal").modal('toggle');
        var totalInv=$("#invoiceSuptotal").val();
        var totalRef=$("#refTotal").val();
        var remining = (Number(totalInv)-Number(totalRef));
        $(".invRem").html(remining.toFixed(2));
        if(Number(remining) <= 1){
         $("#supplierModal").modal('toggle');
         setTimeout(function(){
          $("#closePage").click();
          }, 500);



        }

/*
        else if(totalInv == totalRef){
         $("#AddItems").hide();
         $("#closePage").show();
        }
        else if(totalInv >= totalRef){
         $("#AddItems").prop('disabled', false);
        }
  */

       }
      });
     }
    });
   }
  }
 });
 $(".close").click(function(){
  $("#supplierModal").modal('toggle');
 });
 $("#closePage").click(function(){
  $(".data_display").html('');
  $(".m-0").html("Add New Stock Invoice");
  $(".data_display").load("dist/html/newBuyStockInvoice.html");
 });


 return false;
});

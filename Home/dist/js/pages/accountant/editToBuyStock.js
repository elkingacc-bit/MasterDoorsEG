$(document).ready(function(){
 $("#saveInvoice").hide();
 $("#closePage").hide();
 var invLast = $("#invoiceId").val();
 $.ajax({
  url:'dist/php/suppliersInvoiceItems2.php',
  type:"POST",
  data:{inv:invLast},
  success: function(getInvoiceData){
   $(".invoiceData").html(getInvoiceData);
   $("#AddItems").show();
   $("#saveItems").show();
   $("#items").val('');
   $("#invoiceQyan").val('');
   $("#unitPrice").val('');
   $("#supTotal").val('');
   var totalInv=$("#invoiceSuptotal").val();
   var totalRef=$("#refTotal").val();
   var remining = (Number(totalInv)-Number(totalRef));
   $("#itemsValue").val(remining);
   $(".invRem").html( remining.toFixed(2));
   if(Number(remining) <= 1){
    $("#AddItems").hide();
    $("#closePage").show();
   }
   else if(totalInv >= totalRef){
    $("#AddItems").prop('disabled', false); // disable button
   }
  }
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
  

  $("#vatType").change(function(){
   var taxType = $(this).val();
   var supTotal=$("#invoiceSuptotal").val();
   if(taxType == 1){ 
    var taxAmount=(Number(supTotal)*Number(.14));
    $("#invoiceVat").val(taxAmount);
   }
   else if(taxType == 2){
    $("#invoiceVat").val(0);
   }
   var taxValue = $("#invoiceVat").val();
   var totInv = (Number(supTotal) + Number(taxValue));
   $("#invoiceTotal").val(totInv);
  });
  
  $("#invoiceSuptotal").change(function(){
   var taxType = $("#vatType").val();
   var supTotal=$("#invoiceSuptotal").val();
   if(taxType == ''){ 
    $("#invoiceVat").val(0);
   }
   else{
    if(taxType == 1){ 
     var taxAmount=(Number(supTotal)*Number(.14));
     $("#invoiceVat").val(taxAmount);
    }
    else if(taxType == 2){
     $("#invoiceVat").val(0);
    }
    var taxValue = $("#invoiceVat").val();
    var totInv = (Number(supTotal) + Number(taxValue));
    $("#invoiceTotal").val(totInv);  
   }
  });
  $("#unitPrice").change(function(){
   var unitPrice = $(this).val();
   var qyt = $('#invoiceQyan').val();
   var sup=(Number(qyt)*Number(unitPrice));
   $("#supTotal").val(sup);
  });
  $("#invoiceQyan").change(function(){
   var qyt = $(this).val();
   var unitPrice = $('#unitPrice').val();
   var sup = (Number(qyt)*Number(unitPrice));
   $("#supTotal").val(sup);
  });
  
  $("#saveItems").click(function(){
   var data = {};
   $("#items option").each(function(i,el) {  
    data[$(el).data("value")] = $(el).val();
   });
   console.log(data, $("#items option").val());
   var itemCode = $("#chooseItems").val();
   var itemId = $('#items [value="' + itemCode + '"]').data('value');
   var itemsChosenValideate = $('#items [value="' + itemCode + '"]');  
   if(itemsChosenValideate.length <= 0)
   {
    alert('Please Choose Items name form the list');
    $("#chooseItems").css("border-color","red");
    setTimeout(function(){
     $("#chooseItems").css("border-color","#EBEBEB");               
     $("#chooseItems").val(''); 
     $("#chooseItems").focus();             
    }, 1500);
   }

   //var itemId = $("#items").val();
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
    $('.msg').html('Dont Skip Items Name Empty');
   }
   else if(itemQ == ''){
    $('.msg').removeClass('alert-success');
    $('.msg').addClass('alert-danger');
    $('.msg').html('Dont Skip Count Empty');
   }
   else if(iUP == ''){
    $('.msg').removeClass('alert-success');
    $('.msg').addClass('alert-danger');
    $('.msg').html('Dont Skip Unit Price Empty');
   }
   else{
    var newreminingAmount =(Number(reminingAmount) + .1);
    if(Number(inST) > Number(newreminingAmount)){
     alert('Total Amount More Than Invoice Price');
     $("#invoiceQyan").val('');
     $("#unitPrice").val('');
     $("#supTotal").val('');
     //$("#supplierInvoiceModal").modal('toggle');
    }
    else if(Number(inST) <= Number(newreminingAmount)){
     $.ajax({ 
      url:'dist/php/saveItemsToStockInvoice.php',
      type:"POST",
      data:{itemsName:itemId,itemsCount:itemQ,unitPrice:iUP,itemTotalPrice:inST,lastId:inNu,invId:invNum2,dateInv:invDate2},
      success: function(saveItemsInvoice){
       var cuAmount=Number(reminingAmount)-Number(inST);
       //$("#itemsValue").val(cuAmount);
       
       $('.msg').removeClass('alert-danger');
       $('.msg').addClass('alert-success');
       $('.msg').html('Data Saved');
       $(".msg").fadeOut(5000);
       var invLast = $("#invoiceId").val();


       $.ajax({
        url:'dist/php/suppliersInvoiceItems2.php',
        type:"POST",
        data:{inv:invLast},
        success: function(getInvoiceData){


         $(".invoiceData").html(getInvoiceData);
         $("#AddItems").show();
         $("#saveItems").show();
         $("#items").val('');
         $("#invoiceQyan").val('');
         $("#unitPrice").val('');
         $("#supTotal").val('');
         $("#supplierInvoiceModal").modal('toggle');
var totalInv=$("#invoiceSuptotal").val();
var totalRef=$("#refTotal").val();

var remining = (Number(totalInv)-Number(totalRef));
$(".invRem").html(remining.toFixed(2));
    if(Number(remining) <= 1){
    $("#AddItems").hide();
    $("#closePage").show();
   }

else if(totalInv >= totalRef){
$("#AddItems").prop('disabled', false); // disable button


}


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


 });
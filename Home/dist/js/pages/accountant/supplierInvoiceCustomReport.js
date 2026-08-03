$(document).ready(function(){
   $("#accountName").load("dist/php/allSuppliers.php"); 
   $(".yearShow").load('dist/php/years.php');     
   //
   $("#yearlySupplierInvoice").click(function(){
    $(".supplierInvoiceReport").html('');
    $(".quarterSupplierInvoice").css('display','none');
    $(".monthSupplierInvoice").css('display','none');
    $(".limitSupplierInvoice").css('display','none');
    $(".customerSupplierInvoice").css('display','none');
    $(".yearlySupplierInvoice").css('display','block');    
   });
   //
   $("#quarterSupplierInvoice").click(function(){
    $(".supplierInvoiceReport").html('');
    $(".yearlySupplierInvoice").css('display','none');
    $(".monthSupplierInvoice").css('display','none');
    $(".limitSupplierInvoice").css('display','none');
    $(".customerSupplierInvoice").css('display','none');
    $(".quarterSupplierInvoice").css('display','block');    
   });
   //
   $("#monthSupplierInvoice").click(function(){
    $(".supplierInvoiceReport").html('');
    $(".yearlySupplierInvoice").css('display','none');
    $(".quarterSupplierInvoice").css('display','none');
    $(".limitSupplierInvoice").css('display','none');
    $(".customerSupplierInvoice").css('display','none');
    $(".monthSupplierInvoice").css('display','block');    
   });
   //
   $("#limitSupplierInvoice").click(function(){
    $(".supplierInvoiceReport").html('');
    $(".yearlySupplierInvoice").css('display','none');
    $(".quarterSupplierInvoice").css('display','none');
    $(".monthSupplierInvoice").css('display','none');
    $(".customerSupplierInvoice").css('display','none');
    $(".limitSupplierInvoice").css('display','block');    
   });
   //
   $("#customerSupplierInvoice").click(function(){
    $(".supplierInvoiceReport").html('');
    $(".yearlySupplierInvoice").css('display','none');
    $(".quarterSupplierInvoice").css('display','none');
    $(".monthSupplierInvoice").css('display','none');
    $(".limitSupplierInvoice").css('display','none');
    $(".customerSupplierInvoice").css('display','block');    
   });
   //
   $("#showSupplierInvouceYear").click(function(){
    var yearC =$('#supplierInvouceYear').val();
    if(yearC == ""){
     alert('Dont Skip Start Date Empty');
    }
    else{
     $.ajax({ 
      url:'dist/php/allSupplierInvoiceYear.php',
      type:"POST",
      data:{startYear:yearC},
      success: function(yearlySupplierInvoiceData){
       $(".yearlySupplierInvoice").css('display','none');
       $(".supplierInvoiceReport").html('');
       $(".supplierInvoiceReport").html(yearlySupplierInvoiceData); 
      }
     });
    }
   });
   //
   $("#showSupplierInvouceQuarter").click(function(){
    var yearC =$('#supplierInvouceYearQ').val();
    var quaarterC =$('#supplierInvouceQuarter').val();
    if(yearC == ""){
     alert('Dont Skip Year Empty');
    }
    else if(quaarterC == ""){
     alert('Dont Skip Quarter Empty');
    }
    else{
     $.ajax({ 
      url:'dist/php/allSupplierInvoiceQuarter.php',
      type:"POST",
      data:{startYear:yearC,startQuarter:quaarterC},
      success: function(quarterSupplierInvoiceData){
       $(".quarterSupplierInvoice").css('display','none');
       $(".supplierInvoiceReport").html('');
       $(".supplierInvoiceReport").html(quarterSupplierInvoiceData); 
      }
     });
    }
   });
   //
   $("#showSupplierInvouceMonth").click(function(){
    var yearC =$('#supplierInvouceYearM').val();
    var monthC =$('#supplierInvouceMonth').val();
    if(yearC == ""){
     alert('Dont Skip Year Empty');
    }
    else if(monthC == ""){
     alert('Dont Skip Quarter Empty');
    }
    else{
     $.ajax({ 
      url:'dist/php/allSupplierInvoiceMonth.php',
      type:"POST",
      data:{startYear:yearC,startQuarter:monthC},
      success: function(monthSupplierInvoiceData){
       $(".monthSupplierInvoice").css('display','none');
       $(".supplierInvoiceReport").html('');
       $(".supplierInvoiceReport").html(monthSupplierInvoiceData); 
      }
     });
    }
   });
   //
   $("#showSupplierSalesInvoice").click(function(){
    var customerC =$('#accountName').val();
    if(customerC == ""){
     alert('Dont Skip Customer Empty');
    }
    else{
     $.ajax({ 
      url:'dist/php/allSupplierInvoiceCustomer.php',
      type:"POST",
      data:{customer:customerC},
      success: function(customerSupplierInvoiceData){
       $(".customerSupplierInvoice").css('display','none');
       $(".supplierInvoiceReport").html('');
       $(".supplierInvoiceReport").html(customerSupplierInvoiceData); 
      }
     });
    }
   });







   //
  $("#showLimitSupplierInvoice").click(function(){
   var sDate =$('#startDate').val();
   var eDate =$('#endDate').val();
   if(sDate == ""){
    alert('Dont Skip Start Date Empty');
   }
   else if(eDate == ""){
    alert('Dont Skip End Date Empty');
   }
   else if(eDate < sDate){
    alert('Must End Date Bigger than Start Date');
   }

   else{
    $.ajax({ 
     url:'dist/php/allSuppliersInvoiceData.php',
     type:"POST",
     data:{startDate:sDate,endDate:eDate},
     success: function(allSuppliersData){
     $(".limitSupplierInvoice").css('display','none');
       $(".supplierInvoiceReport").html('');
       $(".supplierInvoiceReport").html(allSuppliersData); 
     }
    });
   }
   return false;
  });

  });
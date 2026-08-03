$(document).ready(function(){
   $("#accountName").load("dist/php/allCustomars.php"); 
   $(".yearShow").load('dist/php/years.php');     
   //
   $("#yearlySalesInvoice").click(function(){
    $(".salesInvoiceReport").html('');
    $(".quarterSalesInvoice").css('display','none');
    $(".monthSalesInvoice").css('display','none');
    $(".limitSalesInvoice").css('display','none');
    $(".customerSalesInvoice").css('display','none');
    $(".yearlySalesInvoice").css('display','block');    
   });
   //
   $("#quarterSalesInvoice").click(function(){
    $(".salesInvoiceReport").html('');
    $(".yearlySalesInvoice").css('display','none');
    $(".monthSalesInvoice").css('display','none');
    $(".limitSalesInvoice").css('display','none');
    $(".customerSalesInvoice").css('display','none');
    $(".quarterSalesInvoice").css('display','block');    
   });
   //
   $("#monthSalesInvoice").click(function(){
    $(".salesInvoiceReport").html('');
    $(".yearlySalesInvoice").css('display','none');
    $(".quarterSalesInvoice").css('display','none');
    $(".limitSalesInvoice").css('display','none');
    $(".customerSalesInvoice").css('display','none');
    $(".monthSalesInvoice").css('display','block');    
   });
   //
   $("#limitSalesInvoice").click(function(){
    $(".salesInvoiceReport").html('');
    $(".yearlySalesInvoice").css('display','none');
    $(".quarterSalesInvoice").css('display','none');
    $(".monthSalesInvoice").css('display','none');
    $(".customerSalesInvoice").css('display','none');
    $(".limitSalesInvoice").css('display','block');    
   });
   //
   $("#customerSalesInvoice").click(function(){
    $(".salesInvoiceReport").html('');
    $(".yearlySalesInvoice").css('display','none');
    $(".quarterSalesInvoice").css('display','none');
    $(".monthSalesInvoice").css('display','none');
    $(".limitSalesInvoice").css('display','none');
    $(".customerSalesInvoice").css('display','block');    
   });
   //
   $("#showSalesInvouceYear").click(function(){
    var yearC =$('#salesInvouceYear').val();
    if(yearC == ""){
     alert('Dont Skip Start Date Empty');
    }
    else{
     $.ajax({ 
      url:'dist/php/allSalesInvoiceYear.php',
      type:"POST",
      data:{startYear:yearC},
      success: function(yearlySalesInvoiceData){
       $(".yearlySalesInvoice").css('display','none');
       $(".salesInvoiceReport").html('');
       $(".salesInvoiceReport").html(yearlySalesInvoiceData); 
      }
     });
    }
   });
   //
   $("#showSalesInvouceQuarter").click(function(){
    var yearC =$('#salesInvouceYearQ').val();
    var quaarterC =$('#salesInvouceQuarter').val();
    if(yearC == ""){
     alert('Dont Skip Year Empty');
    }
    else if(quaarterC == ""){
     alert('Dont Skip Quarter Empty');
    }
    else{
     $.ajax({ 
      url:'dist/php/allSalesInvoiceQuarter.php',
      type:"POST",
      data:{startYear:yearC,startQuarter:quaarterC},
      success: function(quarterSalesInvoiceData){
       $(".quarterSalesInvoice").css('display','none');
       $(".salesInvoiceReport").html('');
       $(".salesInvoiceReport").html(quarterSalesInvoiceData); 
      }
     });
    }
   });
   //
   $("#showSalesInvouceMonth").click(function(){
    var yearC =$('#salesInvouceYearM').val();
    var monthC =$('#salesInvouceMonth').val();
    if(yearC == ""){
     alert('Dont Skip Year Empty');
    }
    else if(monthC == ""){
     alert('Dont Skip Quarter Empty');
    }
    else{
     $.ajax({ 
      url:'dist/php/allSalesInvoiceMonth.php',
      type:"POST",
      data:{startYear:yearC,startQuarter:monthC},
      success: function(monthSalesInvoiceData){
       $(".monthSalesInvoice").css('display','none');
       $(".salesInvoiceReport").html('');
       $(".salesInvoiceReport").html(monthSalesInvoiceData); 
      }
     });
    }
   });
   //
   $("#showCustomerSalesInvoice").click(function(){
    var customerC =$('#accountName').val();
   
    if(customerC == ""){
     alert('Dont Skip Customer Empty');
    }
    
    else{
     $.ajax({ 
      url:'dist/php/allSalesInvoiceCustomer.php',
      type:"POST",
      data:{customer:customerC},
      success: function(customerSalesInvoiceData){
       $(".customerSalesInvoice").css('display','none');
       $(".salesInvoiceReport").html('');
       $(".salesInvoiceReport").html(customerSalesInvoiceData); 
      }
     });
    }
   });







   //
   $("#showLimitSalesInvoice").click(function(){
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
      url:'dist/php/allSalesInvoiceData.php',
      type:"POST",
      data:{startDate:sDate,endDate:eDate},
      success: function(periodSalesInvoiceData){
       $(".monthSalesInvoice").css('display','none');
       $(".salesInvoiceReport").html('');
       $(".salesInvoiceReport").html(periodSalesInvoiceData);  
      }
     });
    }
    return false;
   });

  });
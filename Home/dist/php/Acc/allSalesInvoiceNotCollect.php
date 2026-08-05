<style>
 h1{font-size: 12px;}
</style>
<div class="table-responsive-lg">
 <table class='table table-sm w-100 table-bordered table-striped salesInvoiceNotCollected'>
  <thead class='bg-info text-center'>
   <th>sn</th>
   <th>Number</th>
   <th>Date</th>
   <th>Customer</th>
   <th>SupTotal</th>
   <th>Vat</th>
   <th>Total</th>
   <th>Collecting</th>
   <th>Remaining</th>
   <th>info</th>
   <th>Collect</th>
   <th>WHT</th>
   <th>Print</th>
   <th>Delete</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("../connection.php");
    $Sn=0;
    // Get Sales Invoice From Sales Invoice
    $sqlSalesInoice="SELECT `salesInvoiceId`,`salesInvoiceNumber`,`jopRef`,`salesInvoiceDate`,`customerCode`,`salesInvoiceType`,`salesInvoiceSupTotal`,`invoiceDiscount`,
    `salesInvoictVat`,`totalInvoice`,`invoiceCollectAmount` FROM `salesInvoice` ORDER BY `salesInvoiceDate` DESC";
    $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 01");
    echo"<input value='Accrued Sales Invoice' class='reportTitel' hidden>";
    if(mysqli_num_rows($querySalesInoice) > 0){
     While($salesInoiceData=mysqli_fetch_assoc($querySalesInoice)){
      $Sn ++;
      $invId=$salesInoiceData['jopRef'];
      $invRef=$salesInoiceData['salesInvoiceId'];
      // Get Customer Data
      $sqlCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $salesInoiceData[customerCode]";
      $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
      $customerData=mysqli_fetch_assoc($quaryCustomer);     
      $validAmount=($salesInoiceData['totalInvoice']-$salesInoiceData['invoiceCollectAmount']);
      $invoiceNumber=$salesInoiceData['salesInvoiceNumber'];
      $sqlSalesVat="SELECT `transactionsDate`,`debtor`,`creditor`,`description` FROM `financialTransactions` WHERE `transactionCode` = 21210110000 
      AND `description` like '$invoiceNumber %'";
      $querySalesVat=mysqli_query($link,$sqlSalesVat)or die("ERROR_SNSC : 02");
      $collectVat=mysqli_num_rows($querySalesVat);
      if($salesInoiceData['salesInvoiceType'] =='VAT'){
       if($collectVat >= 1){
         $WHTButton="";
        }
        else{
         $WHTButton="<button class='btn btn-link btn-sm paiedTax' value='$invRef'>WHT</button>";            
        }
       }
       else{
        $WHTButton="";
       }
       if($salesInoiceData['totalInvoice'] == $salesInoiceData['invoiceCollectAmount']){
        $collectButton="";
       }
       else{
        $collectButton="<button class='btn btn-link btn-sm addCollect' value='$invRef'><i class='fas fa-wallet'></i></button>";
       }
       $deleteBtn="<button class='btn btn-link btn-sm deleteSalesInvoice' value='$invRef' data-toggle='tooltip' data-placement='left' title='Delete Invoice'>
        <i class='fas fa-trash-alt' aria-hidden='true' style='color:#d9534f'></i></button>";
       echo"<tr>
       <td>$Sn</td>
	   <td>$salesInoiceData[salesInvoiceNumber]</td>
	   <td>$salesInoiceData[salesInvoiceDate]</td>
	   <td>$customerData[customername]</td>
	   <td>".number_format(($salesInoiceData['salesInvoiceSupTotal']), 2)."</td>
	   <td>".number_format(($salesInoiceData['salesInvoictVat']), 2)."</td>
       <td>".number_format(($salesInoiceData['totalInvoice']), 2)."</td>
       <td>".number_format(($salesInoiceData['invoiceCollectAmount']), 2)."</td>
       <td>".number_format(($validAmount), 2)."</td>
	   <td><button class='btn btn-link showCollect' value='$invRef'><i class='fas fa-info'></i></button></td>
       <td>$collectButton</td>
       <td>$WHTButton</td>
       <td><button class='btn btn-link btn-sm printInv' value='$invRef'><i class='fas fa-print w-100'></i></button></td>
       <td>$deleteBtn</td>
	  </tr>";
     }
    }
   ?>
  </tbody>
  <tfoot>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
  </tfoot>
 </table>
</div>
<!-- Modal -->
<div class="modal fade" id="salesModal" aria-hidden="true" aria-labelledby="salesModalData" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title" id="salesModalData"></h5>
    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   </div>
   <div class="modal-body salesInvoiceData"></div>
  </div>
 </div>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var titleName = $(".reportTitel").val();
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
               + (currentdate.getMonth()+1)  + "/" 
               + currentdate.getFullYear() + " @ "  
               + currentdate.getHours() + ":"  
               + currentdate.getMinutes() + ":" 
               + currentdate.getSeconds();
  var table = $('.salesInvoiceNotCollected').DataTable({
   fixedHeader: false,
   scrollY:'35vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false, 
   order:[[0, "asc"]],
   dom: 'Bfrtip',
   buttons:[
    {
     extend: 'excel',
     text: 'Excel',
     extension: '.xlsx',
     title:titleName+datetime,
     filename: function () {
      return titleName 
     },
     className: 'btn btn-secondary',
     exportOptions:{
      columns: [0,1,2,3,4,5,6,7,8]
     },
     footer: false,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:titleName+datetime,
     filename: function () {
      return titleName
     },
     extension: '.pdf',
     className: 'btn btn-secondary',
     exportOptions:{
      columns: [0,1,2,3,4,5,6,7,8]
     },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
     exportOptions: {
      columns: [0,1,2,3,4,5,6,7,8]
     },          
     customize: function ( win ) {
      $(win.document.body)
      .css( {'font-size':'8pt',  'text-align': 'left'} )
      .prepend('<img src="dist/img/logoMarker.png" style="position:absolute;top:2cm;left:30%;opacity: 0.1;filter: alpha(opacity=15);width: 350px; height:400px" />');
      $(win.document.body).find( 'table' )
      .addClass( 'compact' )
      .css( {'font-size' :'inherit',  'text-align': 'left'} );
     },
    } 
   ],
  "footerCallback": function(row, data, start, end, display){
        var api = this.api(), data;
        var intval = function(i){
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };

        // Col 1
        total = api
            .column( 4 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 4, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 4 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
    // Col 2
        total = api
            .column( 5 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 5, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 5 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
        // Col 3
        total = api
            .column( 6 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 6, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 6 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
        
        // Col 4
        total = api
            .column( 8 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 8, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 8 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   

        // Col 5
        total = api
            .column( 7 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 7, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 7 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
       
        }

   });

 $(".close").click(function(){
 	$("#salesModal").modal('toggle');
 });
 // Invoice Items
 $(".showItems").click(function(){
  var invId = $(this).val();
  $.ajax({
   url:'dist/php/Acc/salesInvoiceDetils.php',
   type:"POST",
   data:{jopId:invId},
   success: function(getSuuplierWithdrawalData){
    $(".supplierInvoiceData").html('');
    $("#salesModalData").html('');
    $("#salesModalData").html('Sales Invoice Details');
    $(".salesInvoiceData").html(getSuuplierWithdrawalData);
    $("#salesModal").modal('show');  
   }
  });
  return false;
 });
 // Collect Sammury
 $(".showCollect").click(function(){
  var invId = $(this).val();
  $.ajax({
   url:'dist/php/Acc/salesInvoiceCollectSammury.php',
   type:"POST",
   data:{invRowNum:invId},
   success: function(getSuuplierWithdrawalData){
    $(".salesInvoiceData").html('');
    $("#salesModalData").html('');
    $("#salesModalData").html('Collecting Sammury');
    $(".salesInvoiceData").html(getSuuplierWithdrawalData);
    $("#salesModal").modal('show');  
   }
  });
  return false;
 });
 //Add Collect
 $(".addCollect").click(function(){
  var invId = $(this).val();
  $.ajax({
   url:'dist/php/Acc/newSalesInvoiceCollect.php',
   type:"POST",
   data:{invNum:invId},
   success: function(getCollectInvData){
    $(".salesInvoiceData").html('');
    $("#salesModalData").html('');
    $("#salesModalData").html('New Collect Invoice');
    $(".salesInvoiceData").html(getCollectInvData);
    $("#salesModal").modal('show');  
   }
  });
  return false;
 });
 // Withdrawal Tax
 $(".paiedTax").click(function(){
  var invId = $(this).val();
  $.ajax({
   url:'dist/php/Acc/withdrawalHoldingTax.php',
   type:"POST",
   data:{orderId:invId},
   success: function(getHoldingTaxInvoiceData){
    $(".salesInvoiceData").html('');
    $("#salesModalData").html('Subtraction Invoice Holding Tax');
    $(".salesInvoiceData").html(getHoldingTaxInvoiceData);
    $("#salesModal").modal('show');  
   }
  });
  return false;
 });

$(".printInv").click(function(){
  var invRowId = $(this).val();

window.open(`dist/php/Acc/printSalesInvoice.php?invNum=${invRowId}`, '_blank');


 });

 // Delete Invoice
 $(".deleteSalesInvoice").click(function(){
  var invId = $(this).val();
  var $row = $(this).closest('tr');
  $.ajax({
   url:'dist/php/Acc/getSalesInvoiceDeleteImpact.php',
   type:"POST",
   data:{invoiceId:invId},
   success: function(impactRes){
    var impact = (typeof impactRes === "string") ? JSON.parse(impactRes) : impactRes;
    if(!impact.success){
     alert(impact.message);
     return;
    }
    var html = "<div class='text-left'>";
    html += "<p><b>Invoice:</b> " + impact.invoiceNumber + "</p>";
    html += "<p><b>Total:</b> " + impact.total.toFixed(2) + " &nbsp; <b>Collected:</b> " + impact.collectAmount.toFixed(2) + "</p>";
    if(impact.collectAmount <= 0){
     html += "<p>No collection recorded on this invoice.</p>";
    }
    else if(impact.collectionReconciled){
     html += "<p>The linked collection (cash &amp; financial transactions) will also be reversed.</p>";
    }
    else{
     html += "<p style='color:#d9534f'>&#9888; " + impact.unreconciledAmount.toFixed(2) + " was collected through a shared/general collection run and cannot be traced to this invoice alone - it will NOT be reversed (money already came in).</p>";
    }
    html += "<p>The related job will be marked as not yet invoiced.</p>";
    html += "<button class='btn btn-danger confirmDeleteSalesInvoice' value='" + invId + "'>Yes, Delete This Invoice</button>";
    html += "</div>";
    $(".salesInvoiceData").html('');
    $("#salesModalData").html('');
    $("#salesModalData").html('Delete Sales Invoice');
    $(".salesInvoiceData").html(html);
    $("#salesModal").data('row', $row);
    $("#salesModal").modal('show');
   }
  });
  return false;
 });

 $(document).on('click', '.confirmDeleteSalesInvoice', function(){
  var invId = $(this).val();
  var $row = $("#salesModal").data('row');
  $.ajax({
   url:'dist/php/Acc/deleteSalesInvoice.php',
   type:"POST",
   data:{invoiceId:invId},
   success: function(delRes){
    var del = (typeof delRes === "string") ? JSON.parse(delRes) : delRes;
    if(del.success){
     $("#salesModal").modal('toggle');
     if($row){ $row.fadeOut(200, function(){ $(this).remove(); }); }
     alert("Invoice Deleted");
    }
    else{
     alert(del.message);
    }
   }
  });
  return false;
 });



});


</script>
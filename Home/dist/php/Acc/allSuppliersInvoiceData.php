<style>
 h1{font-size: 12px;}
</style>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $sDate=mysqli_real_escape_string($link, $_POST['startDate']);
 $eDate=mysqli_real_escape_string($link, $_POST['endDate']);
 echo"
  <input value='Purchaseing Invoice From $sDate To $eDate' class='reportTitel' hidden>
  <h3 class='text-center text-body'>Purchaseing Invoice From $sDate To $eDate</h3>
 ";
?>
<div class="table-responsive-lg">
 <table class='table table-sm table-bordered table-striped myTableSuppInvoA w-100'>
  <thead class='bg-info text-center'>
   <th class="col-1">SN</th>
   <th>Number</th>
   <th>Date</th>
   <th>Supplier</th>
   <th>Amount</th>
   <th>Value Added Tax</th>
   <th>Total</th>
   <th>Payment</th>
   <th>Show</th>
   <th>Accrued</th>
  </thead>
  <tbody>
  <?php
    // Get Supplier Invoice From Supplier Invoice
    $sqlSalesInoice="SELECT `suppliersInvoiceId`,`suppliersInvoiceNumber`,`supplierOrderNum`,`suppliersInvoiceDate`,`supplierCode`,`suppliersInvoiceType`,
    `suppliersInvoiceSupTotal`,`suppliersInvoiceDiscount`,`suppliersInvoiceVat`,`suppliersInvoiceTotal`,`paidAmount`,`paidType`,`paiedStuts` 
    FROM `supplierInvoice` WHERE `suppliersInvoiceDate` BETWEEN '$sDate' AND '$eDate'";
    $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 01");
    $sn = 0;
    While($salesInoiceData=mysqli_fetch_assoc($querySalesInoice)){
     $sn++;
     $orderRef=$salesInoiceData['supplierOrderNum'];
     $invId=$salesInoiceData['suppliersInvoiceId'];
     // Get Customer Data
     $sqlCustomer="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $salesInoiceData[supplierCode]";
     $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
     $customerData=mysqli_fetch_assoc($quaryCustomer);     
     $validAmount=($salesInoiceData['suppliersInvoiceTotal']-$salesInoiceData['paidAmount']);
     $detailes = "<button class='btn btn-link showCollect' value='$invId'>Show</button>";
     echo"<tr>
	  <td>$sn</td>
      <td>$salesInoiceData[suppliersInvoiceNumber]</td>
	  <td>$salesInoiceData[suppliersInvoiceDate]</td>
	  <td>$customerData[suppliername]</td>
	  <td>$salesInoiceData[suppliersInvoiceSupTotal]</td>
	  <td>$salesInoiceData[suppliersInvoiceVat]</td>
	  <td>$salesInoiceData[suppliersInvoiceTotal]</td>
	  <td>$salesInoiceData[paidAmount]</td>
	  <td>$detailes</td>
      <td>$validAmount</td>
	 </tr>";
    
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
  var table = $('.myTableSuppInvoA').DataTable({
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
      columns: [0,1,2,3,4,5,6,7,9]
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
      columns: [0,1,2,3,4,5,6,7,9]
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
      columns: [0,1,2,3,4,5,6,7,9]
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
            i.replace(/[\A]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };
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

         total = api
            .column( 9 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 9, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 9 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");       
            
        }

   });



 $(".close").click(function(){
  $("#salesModal").modal('toggle');
 });
 // Collect Sammury
 $(".showCollect").click(function(){
  var invId = $(this).val();
  $.ajax({
   url:'dist/php/Acc/supplierInvoicePaid.php',
   type:"POST",
   data:{invRowNum:invId},
   success: function(getSuuplierWithdrawalData){
    $(".salesInvoiceData").html('');
    $("#salesModalData").html('');
    $("#salesModalData").html('Paied Sammury');
    $(".salesInvoiceData").html(getSuuplierWithdrawalData);
    $("#salesModal").modal('show');  
   }
  });
  return false;
 });

});
</script>
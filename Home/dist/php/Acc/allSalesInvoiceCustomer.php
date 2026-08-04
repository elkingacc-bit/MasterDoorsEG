<style>
 h1{font-size: 12px;}
</style>
<div class="table-responsive-lg">
 <table class='table table-sm table-bordered table-striped myTableSalesInvoCust w-100'>
  <thead class='bg-info text-center'>
   <th class="col-1">SN</th>
   <th>Number</th>
   <th>Date</th>
   <th>Customer</th>
   <th>SupTotal</th>
   <th>Value Added Tax</th>
   <th>Total</th>
  </thead>
  <tbody>
   <?php
    include_once("../authCheck.php");
    date_default_timezone_set("Africa/Cairo");
    include_once("../connection.php");
    $customer=(int)$_POST['customer'];
    // Get Sales Invoice From Sales Invoice
    $sqlSalesInoice="SELECT `salesInvoiceId`,`salesInvoiceNumber`,`jopRef`,`salesInvoiceDate`,`customerCode`,`salesInvoiceType`,`salesInvoiceSupTotal`,`invoiceDiscount`,
    `salesInvoictVat`,`totalInvoice`,`invoiceCollectAmount` FROM `salesInvoice` WHERE `customerCode` = $customer";
    $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 01");
    $sn=0;
    While($salesInoiceData=mysqli_fetch_assoc($querySalesInoice)){
     $sn++;
     $invId=$salesInoiceData['jopRef'];
     $invRef=$salesInoiceData['salesInvoiceId'];
     // Get Customer Data
     $sqlCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $salesInoiceData[customerCode]";
     $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
     $customerData=mysqli_fetch_assoc($quaryCustomer);     
     $validAmount=($salesInoiceData['totalInvoice']-$salesInoiceData['invoiceCollectAmount']);
     echo"<tr>
	  <td>$sn</td>
      <td>$salesInoiceData[salesInvoiceNumber]</td>
	  <td>$salesInoiceData[salesInvoiceDate]</td>
	  <td>$customerData[customername]</td>
	  <td>".number_format(($salesInoiceData['salesInvoiceSupTotal']), 2)."</td>
	  <td>".number_format(($salesInoiceData['salesInvoictVat']), 2)."</td>
	  <td>".number_format(($salesInoiceData['totalInvoice']), 2)."</td>
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
   </tfoot>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();          
  var table = $('.myTableSalesInvoCust').DataTable( {
   fixedHeader: false,
             scrollY:'35vh',
             scrollX: true,
             scrollCollapse: true,
             paging: false, 
             order:[[0, "asc"]], 
         
   dom: 'Bfrtip',
       buttons: [
       
       {
            extend: 'excel',
            text: 'Excel',
            extension: '.xlsx',
            title:'Sales_Invoices_Customer '+datetime,
            filename: function () {
            return "Sales_Invoices_Customer" },
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [  0,1,2,3,4,5,6]
            },
            footer: false,
            
        },
        
        {
            extend: 'pdf',
            text: 'PDF',
            title:'Sales_Invoices_Customer '+datetime,
             filename: function () {
            return "Sales_Invoices_Customer" },
            extension: '.pdf',
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [  0,1,2,3,4,5,6]
            },
            footer: false,
            
        },
        
    {
      extend: 'print',
      text: 'Print',
      className: 'btn btn-secondary',
      title:'Sales_Invoices_Customer '+datetime,
      footer: true,
       exportOptions: {
           
                   columns: [ 0,1,2,3,4,5,6]
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.1; filter: alpha(opacity=15); width: 350px; height:400px" />');
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
            

         
            
        }

   });




  });
 



</script>
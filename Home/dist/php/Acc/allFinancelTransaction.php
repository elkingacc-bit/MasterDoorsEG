<style>
 h1{font-size: 12px;}
</style>
<?php
date_default_timezone_set("Africa/Cairo");
    include_once("../connection.php");
 echo"
  <input value='All FinancalTransaction' class='reportTitel' hidden>
  <h3 class='text-center text-body'>All FinancalTransaction</h3>
 ";
?>

<div class="table-responsive-lg">
 <table class='table table-bordered table-striped myTableFinancalTransactionAll w-100'>
  <thead class='bg-info text-center'>
  <th class="col-1">SN</th>
   <th class="col-1">Number</th>
   <th>Date</th>
   <th>debtor</th>
   <th>creditor</th>
   <th>description</th>
   <th>Account</th>
  </thead>
  <tbody>
   <?php
    // Get All Financal Transaction, with the account/supplier/customer name resolved
    // via a single set of LEFT JOINs instead of a per-row lookup query (was 1+3N queries,
    // now 1 query total regardless of row count)
    $sqlFinancalTransaction="SELECT ft.`transactionNumber`,ft.`transactionsDate`,ft.`debtor`,ft.`creditor`,ft.`description`,ft.`transactionCode`,
     COALESCE(ac.`accountName`, sup.`suppliername`, cus.`customername`) as account
     FROM `financialTransactions` ft
     LEFT JOIN `accountantcode` ac ON ac.`accountCode` = ft.`transactionCode`
     LEFT JOIN `allsuppliers` sup ON sup.`suppliercode` = ft.`transactionCode`
     LEFT JOIN `customers` cus ON cus.`customercode` = ft.`transactionCode`";
    $queryFinancalTransaction=mysqli_query($link,$sqlFinancalTransaction)or die("ERROR_SNSC : 01");
    $sn=0;
    While($financalTransactionData=mysqli_fetch_assoc($queryFinancalTransaction)){
     $account=$financalTransactionData['account'];
     $sn++;
     echo"<tr>
	  <td>$sn</td>
      <td>$financalTransactionData[transactionNumber]</td>
	  <td>$financalTransactionData[transactionsDate]</td>
	  <td>".number_format(($financalTransactionData['debtor']), 2)."</td>
	  <td>".number_format(($financalTransactionData['creditor']), 2)."</td>
	  <td>$financalTransactionData[description]</td>
	  <td>$account</td>
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
  var titleName = $(".reportTitel").val();
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
               + (currentdate.getMonth()+1)  + "/" 
               + currentdate.getFullYear() + " @ "  
               + currentdate.getHours() + ":"  
               + currentdate.getMinutes() + ":" 
               + currentdate.getSeconds();
  var table = $('.myTableFinancalTransactionAll').DataTable({
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
      columns: [0,1,2,3,4,5,6]
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
      columns: [0,1,2,3,4,5,6]
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
      columns: [0,1,2,3,4,5,6]
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
        total = api
            .column( 3 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 3, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 3 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
    
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
            
           

         
            
        }

   });



  });
 



</script>
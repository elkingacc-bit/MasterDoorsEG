<style>h1{font-size: 12px;}</style>
<div class="table-responsive-lg">
 <input type='text' id='table_Titel' value='All Customers Statment' style='display:none;'>
 <table class="table table-sm table-bordered table-striped myTableAllCustomerB w-100 text-center">
  <thead class="bg-primary text-center">
   <th>SN</th>
   <th>Customer</th>  
   <th>Po Amount</th>
   <th>Collect</th>
   <th>insurance</th>
   <th>Valid</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $sn=0;
    $invoices=0;
    $collectAmount=0;
    $insur=0;
    $balance=0;
    $sqlGetCustomer="SELECT `customercode`,`customername` FROM `customers` ORDER BY `customername` ASC";
    $queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S".mysqli_error($link));
    while($resGetCustomer = mysqli_fetch_assoc($queryGetCustomer)){
     $sn++;
     $tCode=$resGetCustomer['customercode'];
     $customerName=$resGetCustomer['customername'];

     $customerPoSql="SELECT (SUM(`poVal`) + SUM(`POVat`)) as account FROM `customerpo`, `job` 
     WHERE `customerpo`.`jobidref` = `job`.`jobId`  AND `job`.`jobref` = 3 AND `custCode` = $tCode";
     $customerPoQuery=mysqli_query($link,$customerPoSql);
     
     if(mysqli_num_rows($customerPoQuery) > 0){
      $statment=mysqli_fetch_assoc($customerPoQuery);
      $invoices=$statment['account'];
     }
     // Get Collect Data
     $sqlStatment="SELECT sum(`creditor`) as collect FROM `financialTransactions` WHERE `transactionCode` = $tCode";
     $queryStatment=mysqli_query($link,$sqlStatment);    
     if($customerCollect=mysqli_fetch_assoc($queryStatment)){
      $collectAmount=$customerCollect['collect'];
     }
     $balance=( $invoices - $collectAmount);
     $insur=($invoices * .10);
     
     $vaild=( $invoices - ($collectAmount) );
    if($invoices > 0){
     echo"<tr>
      <td>$sn</td>
      <td>$customerName</td>
      <td>".number_format(($invoices), 2)."</td>
      <td>".number_format(($collectAmount), 2)."</td>
      <td>".number_format(($insur), 2)."</td>
      <td>".number_format(($vaild), 2)."</td>
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
  </tfoot>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var tableTitel =$('#table_Titel').val(); 
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();         
   
  var table = $('.myTableAllCustomerB').DataTable( {
     
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
            title:tableTitel+datetime,
            filename: function () {
            return tableTitel },
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [  0,1,2,3,4,5]
            },
            footer: false,
            
        },
        
        {
            extend: 'pdf',
            text: 'PDF',
            title:tableTitel+datetime,
             filename: function () {
            return tableTitel },
            extension: '.pdf',
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [  0,1,2,3,4,5]
            },
            footer: false,
            
        },
        
    {
      extend: 'print',
      text: 'Print',
      className: 'btn btn-secondary',
      title:tableTitel+' '+datetime,
      footer: true,
       exportOptions: {
           
                   columns: [ 0,1,2,3,4,5]
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
            .column( 2 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 2, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 2 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
    
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
            
        }

   });




});
</script>
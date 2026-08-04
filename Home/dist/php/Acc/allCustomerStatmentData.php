<style>
 h1{font-size: 12px;}
</style>
<div class="table-responsive-lg">
 <?php
  include_once("../authCheck.php");
  date_default_timezone_set("Africa/Cairo");
  include_once("../connection.php");
  $tCode=(int)$_POST['accCode'];
  $invoices=0;
  $accountBalance=0;
  $sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $tCode";
  $queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S".mysqli_error($link));
  $resGetCustomer = mysqli_fetch_assoc($queryGetCustomer);
  echo "<input type='text' id='table_Titel' value='$resGetCustomer[customername] Statment' style='display:none;'>";
 ?>
 <table class="table table-sm table-bordered table-striped myTableCustomerB w-100 text-center">
  <thead class="bg-primary text-center">
   <th>Date</th>
   <th>Customer Po</th>  
   <th>Collect</th>
   <th>Description</th>
   <th>Notes</th>
  </thead>
  <tbody>
   <?php
    $customerPoSql="SELECT (SUM(`poVal`) + SUM(`POVat`)) as account,`jobidref`,`startDate`,`projectName` 
    FROM `customerpo`, `job` 
    WHERE `customerpo`.`jobidref` = `job`.`jobId`  AND `job`.`jobref` = 3 AND `customerpo`.`custCode`= `job`.`customer` AND `custCode` = $tCode GROUP BY `jobidref`";
    $customerPoQuery=mysqli_query($link,$customerPoSql)or die("ERROR :01-AU_AU_S".mysqli_error($link));
    if(mysqli_num_rows($customerPoQuery) > 0){
     while($statment=mysqli_fetch_assoc($customerPoQuery)){
      $invoices=$statment['account'];
      $accountBalance += $invoices;
      echo"<tr>
       <td>$statment[startDate]</td>
       <td>".number_format(($invoices), 2)."</td>
       <td></td>
       <td>$statment[projectName]</td>
       <td></td>
      </tr>";
     }
     $nots="";
     $sqlGroupDStatment="SELECT `transactionsDate`, `creditor`, `description` FROM `financialtransactions` WHERE  `transactionCode` = $tCode";  
     $queryGroupDStatment=mysqli_query($link,$sqlGroupDStatment)or die("ERROR :01-AU_AU_S".mysqli_error($link));
     while($groupDstatmentCollect=mysqli_fetch_assoc($queryGroupDStatment)){
     if($groupDstatmentCollect['creditor'] > 0){
      $accountBalance -= $groupDstatmentCollect['creditor'];
      echo"<tr>
       <td>$groupDstatmentCollect[transactionsDate]</td>
       <td></td>
       <td>".number_format(($groupDstatmentCollect['creditor']), 2)."</td>
       <td>$groupDstatmentCollect[description]</td>
       <td>$nots</td>
      </tr>";            
     } 

     
     }
    }





   ?>
  </tbody>
  <tfoot>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th><?php echo number_format(($accountBalance), 2); ?></th>
  </tfoot>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var tableTitel =$('#table_Titel').val(); 

$('.m-0').html('');
   $('.m-0').html(tableTitel);

  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();         
   
  var table = $('.myTableCustomerB').DataTable( {
     
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
                
              columns: [  0,1,2,3,4]
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
                
              columns: [  0,1,2,3,4]
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
           
                   columns: [ 0,1,2,3,4]
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
            .column( 1 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 1, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 1 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
        

            
        }

   });




});
</script>
<style>
 h1{font-size: 12px;}
</style>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped myTableCustomerA w-100">
  <thead  class="bg-primary text-center">
   <th class="col-1">SN</th>
   <th>Date</th>
   <th>Invoice</th>  
   <th>Collect</th>
   <th>Description</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $tCode=$_POST['accCode'];
    $accountBalance=0;
    $sqlChekBalance="SELECT (sum(`debtor`)-sum(`creditor`)) as remining FROM `financialTransactions` WHERE `transactionCode` = $tCode";
    $queryChekBalance=mysqli_query($link,$sqlChekBalance);
    if(mysqli_num_rows($queryChekBalance) > 0){


    $balance=mysqli_fetch_assoc($queryChekBalance);
    $customerBalance=$balance['remining'];
    if($customerBalance > 0){
     $sqlStatment="SELECT `transactionsDate`,`debtor`,`creditor`,`description`,`transactionCode` FROM `financialTransactions` WHERE `transactionCode` = $tCode";  
     $queryStatment=mysqli_query($link,$sqlStatment);
     $sn=0;
     $accountBalance=0;
     while($statment=mysqli_fetch_assoc($queryStatment)){
      $sn++;
      $accountBalance += $statment['debtor'];
      $accountBalance -= $statment['creditor'];
      echo"<tr>
       <td>$sn</td>
       <td>$statment[transactionsDate]</td>
       <td>".number_format(($statment['debtor']), 2)."</td>
       <td>".number_format(($statment['creditor']), 2)."</td>
       <td>$statment[description]</td>
      </tr>";      
     }
    }
    }
    else{

     echo"<tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
     </tr>";
    }
   ?>
  </tbody>
  <tfoot>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th><?php echo $accountBalance; ?></th>
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
   
  var table = $('.myTableCustomerA').DataTable( {
     
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
            title:'Customer Statment '+datetime,
            filename: function () {
            return "Customer Statment" },
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [  0,1,2,3,4]
            },
            footer: false,
            
        },
        
        {
            extend: 'pdf',
            text: 'PDF',
            title:'Customer Statment '+datetime,
             filename: function () {
            return "Customer Statment" },
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
      title:'Customer Statment '+datetime,
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
            
        

            
        }

   });




});
</script>
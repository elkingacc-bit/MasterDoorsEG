<style>
 h1{font-size: 12px;}
</style>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $tCode=$_POST['accCode'];
 $sqlFirstLevelData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $tCode";
 $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
 $firstLevel=mysqli_fetch_assoc($queryFirstLevelData);
 $bankName=$firstLevel['suppliername'];
 echo"
  <input value='$bankName Statement' class='reportTitel' hidden>
  <h3 class='text-center text-body'>$bankName Statement</h3>
 ";
?>
<div class="table-responsive-lg">
 <table class="table table-bordered table-striped myTableSupplierSA w-100">
  <thead  class="bg-primary text-center">
   <th class="col-1">SN</th>
   <th>Date</th>
   <th>buying</th>  
   <th>Paid</th>
   <th>Balance</th>
   <th>Description</th>
  </thead>
  <tbody>
   <?php
     $balance=0;
     $sqlBalanceSupplier="SELECT (sum(`creditor`) - sum(`debtor`)) as endBalance FROM `financialTransactions` WHERE`transactionCode` = $tCode";
     $queryBalanceSupplier=mysqli_query($link,$sqlBalanceSupplier);
     if(mysqli_num_rows($queryBalanceSupplier) > 0){
     $balanceSupplier=mysqli_fetch_assoc($queryBalanceSupplier);
     $supplierBalance=$balanceSupplier['endBalance'];
     if($supplierBalance > 0){
      $balance=0;
      $sqlStatment="SELECT `transactionsDate`,`debtor`,`creditor`,`description`,`transactionCode` 
      FROM `financialTransactions` 
      WHERE `transactionCode` = $tCode";  
      $queryStatment=mysqli_query($link,$sqlStatment);
      $sn=0;
      while($statment=mysqli_fetch_assoc($queryStatment)){
       $sn++;
       $balance += $statment['creditor'];
       $balance -= $statment['debtor'];
       echo"<tr>
        <td>$sn</td>
        <td>$statment[transactionsDate]</td>
        <td>".number_format(($statment['creditor']), 2)."</td>
        <td>".number_format(($statment['debtor']), 2)."</td>
        <td>".number_format(($balance), 2)."</td>
        <td>$statment[description]</td>
       </tr>";      
      }
     }
     else{
      echo"<tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
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
   <th><?php echo number_format(($balance), 2); ?></th>
   <th>Close Balance</th>
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
  var table = $('.myTableSupplierSA').DataTable({
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
      columns: [0,1,2,3,4,5]
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
      columns: [0,1,2,3,4,5]
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
      columns: [0,1,2,3,4,5]
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
            i.replace(/[\$]/g, '')*1:
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
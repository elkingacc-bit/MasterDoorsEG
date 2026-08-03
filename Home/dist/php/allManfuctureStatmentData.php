<style>
 h1{font-size: 12px;}
</style>
<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $tCode=(int)$_POST['accCode'];
 $sqlFirstLevelData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $tCode";
 $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
 $firstLevel=mysqli_fetch_assoc($queryFirstLevelData);
 $mySupplier=$firstLevel['suppliername'];
 echo"
  <input value='$mySupplier Statement' class='reportTitel' hidden>
 ";
?>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped myTableManufactur w-100">
  <thead  class="bg-primary text-center">
   <th>Date</th>
   <th>Project</th>
   <th>Order</th>  
   <th>Payment</th>
   <th>Description</th>
  </thead>
  <tbody>
   <?php
    $sqlStatment="SELECT `purchasesOrderDate` AS `date`, `jobref` AS jobId, `totalAmout` AS `amount_or_debtor`, 'invoice' AS `transaction_type`, `purchasesOrderNum` AS `disc`
    FROM `purchasesorder`
    WHERE supplierid = $tCode    
    UNION ALL
    SELECT `transactionDate` AS `date`, NULL AS `jobref` , `withdrawal` AS `amount_or_debtor`, 'transaction' AS `transaction_type`, `description` AS `disc`
    FROM `cash_transaction`
    WHERE `account` = $tCode AND description != 'Invoice Supplier'";
    $queryStatment=mysqli_query($link,$sqlStatment);
    $sn=0;
    $inv=0;
    $paid=0;
    $project='';
    while($statment=mysqli_fetch_assoc($queryStatment)){
     $jobId=$statment['jobId'];
     $project=$jobId;
     $sn++;
     if($statment['transaction_type'] == 'invoice'){
      $credit=$statment['amount_or_debtor'];
      $debet = 0;
      $disc="Invoice Num - ".$statment['disc'];
     }
     else if($statment['transaction_type'] == 'transaction'){
      $debet=$statment['amount_or_debtor'];
      $credit = 0;
      $disc=$statment['disc'];
     }
     if($credit > 0){
      $sqlProject="SELECT projectName  FROM job where jobId = $jobId ";
      $queryProject=mysqli_query($link,$sqlProject);
      if($resProject=mysqli_fetch_assoc($queryProject)){
       $project=$resProject['projectName'];    
      }
     }
     $inv+= $credit;
     $paid+= $debet;
     echo"<tr>
      <td>$statment[date]</td>
      <td>$project</td>
      <td>".number_format(($credit), 2)."</td>
      <td>".number_format(($debet), 2)."</td>
      <td>$disc</td>
     </tr>";
    }
    $balance=($inv - $paid);
   ?>
  </tbody>
  <tfoot>
   <th></th><th></th>
   <th></th>
   <th></th>
   <th><?php echo number_format(($balance), 2); ?></th>
  </tfoot>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var titleName = $(".reportTitel").val();
  $('.m-0').html('');
  $('.m-0').html(titleName);
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
               + (currentdate.getMonth()+1)  + "/" 
               + currentdate.getFullYear() + " @ "  
               + currentdate.getHours() + ":"  
               + currentdate.getMinutes() + ":" 
               + currentdate.getSeconds();
  var table = $('.myTableManufactur').DataTable({
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
      columns: [0,1,2,3,4]
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
      columns: [0,1,2,3,4]
     },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-info',
     title:titleName+datetime,
     footer: true,
     exportOptions: {
      columns: [0,1,2,3,4]
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
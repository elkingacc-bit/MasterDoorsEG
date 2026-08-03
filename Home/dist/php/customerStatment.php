<style>
 h1{font-size: 12px;}
</style>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $sDate=$_POST['startDate'];
 $eDate=$_POST['endDate'];
 $tCode=$_POST['accCode'];
 $startDate = date('Y-m-d', strtotime($sDate. ' - 1 days'));
 $sqlCheckCustN="SELECT `customername` FROM `customers` WHERE `customercode` = $tCode";
 $queryCheckCustN=mysqli_query($link,$sqlCheckCustN)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
 $resCheckCustN=mysqli_fetch_assoc($queryCheckCustN);
 $customerName=$resCheckCustN['customername'];
 echo"<input value='$customerName Statemnt From $sDate To $eDate' class='reportTitel' hidden>
  <h3 class='text-center text-body'>$customerName Statemnt From $sDate To $eDate</h3>
 ";
?>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped myTableCustomer w-100">
  <thead class="bg-primary text-center">
   <th class="col-1">SN</th>
   <th>Date</th>
   <th>Invoice</th>  
   <th>Collect</th>
   <th>Balance</th>
   <th>Description</th>
  </thead>
  <tbody>
   <?php
    //Get Start Work Date
    $sqlStatmentStart="SELECT `transactionsDate` FROM `financialTransactions` WHERE `transactionCode` = $tCode ORDER BY `transactionsDate` ASC LIMIT 1";  
    $queryStatmentStart=mysqli_query($link,$sqlStatmentStart);
    if($statmentStart=mysqli_fetch_assoc($queryStatmentStart)){
     $srartWorkDate=$statmentStart['transactionsDate'];  
     if($srartWorkDate > $sDate){
      $startBalance=0;
     }
     else{
      $sqlStatmentStartBalance="SELECT (sum(`debtor`) - sum(`creditor`)) as balance 
      FROM `financialTransactions` 
      WHERE `transactionsDate` < '$sDate' AND `transactionCode` = $tCode ";  
      $queryStatmentStartBalance=mysqli_query($link,$sqlStatmentStartBalance);
      if($statmentStartBalance=mysqli_fetch_assoc($queryStatmentStartBalance)){
       $startBalance=$statmentStartBalance['balance'];        
      }
      else{
       $startBalance=0;        
      }
     }
    }
    else{
     $startBalance=0;
    }
    echo"<tr>
     <td></td>
     <td>$startDate</td>
     <td></td>
     <td></td>
     <td>".number_format(($startBalance), 2)."</td>
     <td>Opening Balance</td>
    </tr>";
    $sqlStatment="SELECT `transactionsDate`,`debtor`,`creditor`,`description`,`transactionCode` FROM `financialTransactions` WHERE `transactionsDate` BETWEEN '$sDate' 
    AND '$eDate' AND `transactionCode` = $tCode";  
    $queryStatment=mysqli_query($link,$sqlStatment);
    $sn=0;
    $accountBalance=$startBalance;
    while($statment=mysqli_fetch_assoc($queryStatment)){
     $sn++;
     $accountBalance += $statment['debtor'];
     $accountBalance -= $statment['creditor'];
     $duedate = date("d/m/Y",strtotime($statment['transactionsDate']));
     echo"<tr>
      <td>$sn</td>
      <td>$duedate</td>
      <td>".number_format(($statment['debtor']), 2)."</td>
      <td>".number_format(($statment['creditor']), 2)."</td>
      <td>".number_format(($accountBalance), 2)."</td>
      <td>$statment[description]</td>
     </tr>";      
    }
   ?>
  </tbody>
  <tfoot>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th><?php echo number_format(($accountBalance), 2); ?></th>
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
  var table = $('.myTableCustomer').DataTable({
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
     i.replace(/[\$,]/g, '')*1:
     typeof i === 'number' ?
     i : 0;
    };
    //Col A
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
     Number((pageTotal).toFixed(1)).toLocaleString())
    .css("color","blue");   
    // Col B
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
     Number((pageTotal).toFixed(1)).toLocaleString())
    .css("color","blue");            
    }
   });
  });
 </script>
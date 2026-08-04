<style>
 h1{font-size: 12px;}
</style>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped myTableCash w-100">
  <thead  class="bg-primary text-center">
   <th class="col-1">SN</th>
   <th>Date</th>
   <th>Deposit</th>  
   <th>Withdraw</th>
   <th>Balance</th>
   <th>Description</th>
   <th class="col-2">Transaction By</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("../connection.php");   
    # Custdy Balance
    $sqlCustodyData="SELECT sum(`amount`) as cash,sum(`cashBack`) as comback FROM `custody`  WHERE  `custodyRef` = 1";
    $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_SNSC : 02");
    $custodyGetData=mysqli_fetch_assoc($queryCustodyData);
    $custody=($custodyGetData['cash'] - $custodyGetData['comback']);
    //Cash Balance
    $sqlStatment="SELECT `transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode` 
    FROM `cash_transaction`
    WHERE `statmentRef` LIKE '116100%' ORDER BY `transactionDate` ASC";  
    $queryStatment=mysqli_query($link,$sqlStatment);
    $sn=0;
     $accountBalance=0;
    while($statment=mysqli_fetch_assoc($queryStatment)){
     $sn++;
     $accountBalance += $statment['income'];
     $accountBalance -= $statment['withdrawal'];
     $sqlAccountantCodeData="SELECT `fullname` FROM `users` WHERE `userid` = $statment[empCode]";  
     $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
     $accData=mysqli_fetch_assoc($queryAccountantCodeData);
     echo"<tr>
      <td>$sn</td>
      <td>$statment[transactionDate]</td>
      <td>".number_format(($statment['income']), 2)."</td>
      <td>".number_format(($statment['withdrawal']), 2)."</td>
      <td>".number_format(($accountBalance), 2)."</td>
      <td>$statment[description]</td>
      <td>$accData[fullname]</td>
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
    <th>Account Balance</th>
    <th><?php echo number_format(($accountBalance), 2); ?></th>
  </tfoot>
 </table>
 <center>
  <table class="table table-sm table-bordered w-50">
   <thead>
    <th>Date</th>
    <th>Cash Balance</th>
    <th>Custdy</th>
    <th>Avilable</th>
   </thead>
   <tbody>
    <td><?php echo date("Y-m-d") ;?> </td>
    <td><?php echo number_format(($accountBalance), 2); ?></td>
    <td><?php echo number_format(($custody), 2); ?></td>
    <td><?php echo number_format(($accountBalance - $custody), 2); ?></td>
   </tbody>
  </table>
 </center>
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
  var table = $('.myTableCash').DataTable({
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
      title:'All Cash Transaction '+datetime,
      filename: function () {
      return "All Cash Transaction"},
      className: 'btn btn-secondary',
      exportOptions:{
       columns:[0,1,2,3,4,5,6]
      },
      footer: false,
     },
     {
      extend: 'pdf',
      text: 'PDF',
      title:'All Cash Transaction '+datetime,
      filename: function () {
      return "All Cash Transaction" },
      extension: '.pdf',
      className: 'btn btn-secondary',
      exportOptions: {
       columns:[0,1,2,3,4,5,6]
      },
      footer: false,
     },
     {
      extend: 'print',
      text: 'Print',
      className: 'btn btn-secondary',
      title:'All Cash Transaction '+datetime,
      footer: true,
       exportOptions:{
        columns: [0,1,2,3,4,5,6]
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
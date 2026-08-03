<style>
 h1{font-size: 12px;}
</style>
 <h3 class='text-center text-body'>All Bank Balance</h3>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped myTableBank w-100">
  <thead  class="bg-primary text-center">
   <th class="col-1">SN</th>
   <th>Bank</th>
   <th>Deposit</th>  
   <th>Withdrawal</th>
   <th>Balance</th>
   </thead>
   <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    echo"<input value='All Bank Balance' class='reportTitel' hidden>";
    $sqlBankData="SELECT `accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 12 AND `accountCode` LIKE '11620%'";
    $queryBankData=mysqli_query($link,$sqlBankData)or die("ERROR LOA_S:01");
    $sn = 0;
    $banksBalance = 0 ;
    while($bankData=mysqli_fetch_assoc($queryBankData)){
     $sn++;
     $myBank=$bankData['accountCode'];
     $bankName=$bankData['accountName'];
     $sqlStatment="SELECT sum(`debtor`) as incom, sum(`creditor`) as withdrwal FROM `financialTransactions` WHERE `transactionCode` = $myBank ";  
     $queryStatment=mysqli_query($link,$sqlStatment);
     $statment=mysqli_fetch_assoc($queryStatment);
     $accountBalance = ( $statment['incom'] - $statment['withdrwal'] );
     $banksBalance += $accountBalance;
     echo"<tr>
      <td>$sn</td>
      <td>$bankName</td>
      <td>".number_format(($statment['incom']), 2) ."</td>
      <td>".number_format(($statment['withdrwal']), 2) ."</td>
      <td>".number_format(($accountBalance), 2) ."</td>
     </tr>"; 
    }
   ?>
  </tbody>
  <tfoot>
    <th>All Balance</th>
    <th></th>
    <th></th>
    <th></th>
    <th><?php echo number_format(($banksBalance), 2) ; ?></th>
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
  var table = $('.myTableBank').DataTable({
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
     className: 'btn btn-secondary',
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
  });
 });
</script>
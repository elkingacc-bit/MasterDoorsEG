<style>
 h1{font-size: 12px;}
</style>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $myBank=$_POST['bank'];
 $sqlFirstLevelData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $myBank";
 $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
 $firstLevel=mysqli_fetch_assoc($queryFirstLevelData);
 $bankName=$firstLevel['accountName'];
 echo"
  <input value='$bankName Statement' class='reportTitel' hidden>
  <h3 class='text-center text-body'>$bankName Statement</h3>
 ";
?>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped myTableBankB w-100">
  <thead  class="bg-primary text-center">
   <th class="col-1">SN</th>
   <th>Date</th>
   <th>Income</th>  
   <th>Withdrawal</th>
   <th>Balance</th>
   <th>Description</th>
   <th>Account</th>
   <th>Number</th>
  </thead>
  <tbody>
   <?php
    $sqlStatment="SELECT `transactionDate`, `income`, `withdrawal`, `description`,`account`,`chequNumber`,`valideDate` 
    FROM `cash_transaction` WHERE `statmentRef` = $myBank";  
    $queryStatment=mysqli_query($link,$sqlStatment);
    $sn=0;
    $accountBalance=0;
    while($statment=mysqli_fetch_assoc($queryStatment)){
     $sn++;
     $getCode=$statment['account'];
     if($statment['chequNumber'] == 1){ 
      $accountNumber = "Transfer";
     }
     else if($statment['chequNumber'] == 0){ 
      $accountNumber = "Transfer";
     }
     else{
      $accountNumber = $statment['chequNumber'];
     }


$sqlCheckAccount="SELECT `customername` FROM `customers`WHERE `customercode` = $getCode";
$queryCheckAccount=mysqli_query($link,$sqlCheckAccount)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
if(mysqli_num_rows($queryCheckAccount) > 0){
 $resCheckAccount=mysqli_fetch_assoc($queryCheckAccount);
 $account=$resCheckAccount['customername'];
}
else{

$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $getCode";
$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :01-AU_AU_S".mysqli_error($link));
if(mysqli_num_rows($queryGetSupplier) > 0){
 $resGetSupplier = mysqli_fetch_assoc($queryGetSupplier);
 $account=$resGetSupplier['suppliername'];
}
else{
$sqlFirstLevelData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $getCode";
$queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
if(mysqli_num_rows($queryFirstLevelData) > 0){
 $firstLevel=mysqli_fetch_assoc($queryFirstLevelData);    
  $account=$firstLevel['accountName'];
}


}


}


     $accountBalance += $statment['income'];
     $accountBalance -= $statment['withdrawal'];
    echo"<tr>
      <td>$sn</td>
      <td>$statment[transactionDate]</td>
      <td>$statment[income]</td>
      <td>$statment[withdrawal]</td>
      <td>$accountBalance</td>
      <td>$statment[description]</td>
      <td>$account</td>
      <td>$accountNumber</td>
     </tr>";      
    }
   ?>
  </tbody>
  <tfoot>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th><?php echo number_format(($accountBalance), 2) ; ?></th>
    <th>Close Balance</th>
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
  var table = $('.myTableBankB').DataTable({
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
            i.replace(/[\A]/g, '')*1:
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
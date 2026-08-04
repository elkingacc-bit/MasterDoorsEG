<style>
 h1{font-size: 12px;}
</style>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped  myTableAllCustdy w-100">
  <thead class="text-center">
   <th class="col-1">SN</th>
   <th class="col-2">Employee</th>
   <th class="col-1">Date</th>
   <th class="col-2">Withdrawal</th>
   <th class="col-2">Cashback</th>
   <th class="col-4">Description</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("../connection.php");
    $sqlAllCustodyData="SELECT c.`custodyTransactionDate`, c.`poNum`, c.`discription`, c.`empCode`, c.`amount`, c.`cashBack`, c.`closedDate`, u.`fullname`
     FROM `custody` c
     LEFT JOIN `users` u ON u.`userid` = c.`empCode`
     ORDER BY c.`empCode`";
    $queryAllCustodyData=mysqli_query($link,$sqlAllCustodyData)or die("ERROR_SNSC : 02");
    $snCustdy=0;
    $balance=0;
    $allCustodyCount=mysqli_num_rows($queryAllCustodyData);
    if($allCustodyCount > 0)
    {
     while($allCustodytDataResult=mysqli_fetch_assoc($queryAllCustodyData))
     {
      $snCustdy++;
      $balance += $allCustodytDataResult['amount'];
      $balance -= $allCustodytDataResult['cashBack'];
      if($allCustodytDataResult['amount'] > 0){$tr="<tr class='bg-info'>";}
      else if($allCustodytDataResult['closedDate'] == $allCustodytDataResult['custodyTransactionDate']){$tr="<tr class='bg-success'>";}
      else{$tr="<tr>";}
      echo"$tr
       <td>$snCustdy</td>
       <td>$allCustodytDataResult[fullname]</td>
       <td>$allCustodytDataResult[custodyTransactionDate]</td>
       <td>".number_format(($allCustodytDataResult['amount']), 2)."</td>
       <td>".number_format(($allCustodytDataResult['cashBack']), 2)."</td>
       <td>$allCustodytDataResult[discription]</td>
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
   <th><?php echo number_format(($balance), 2); ?></th>  
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
  var table = $('.myTableAllCustdy').DataTable({
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
     title:'Custdy Statment '+datetime,
     filename: function (){return "Custdy Statment" },
     className: 'btn btn-secondary',
     exportOptions: {
      columns: [0,1,2,3,4,5]
     },
     footer: false,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:'Custdy Statment '+datetime,
     filename: function (){return "Custdy Statment" },
     extension: '.pdf',
     className: 'btn btn-secondary',
     exportOptions: {
      columns: [0,1,2,3,4,5]
     },
     footer: false,
    },    
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:'Custdy Statment '+datetime,
     footer: true,
     exportOptions:{
      columns: [ 0,1,2,3,4,5]
     } ,          
     customize: function ( win ) {
      $(win.document.body)
      .css( {'font-size':'8pt',  'text-align': 'left'} )
      .prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.1; filter: alpha(opacity=15); width: 350px; height:400px" />');
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
   //Col 1
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
    //Col 2
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
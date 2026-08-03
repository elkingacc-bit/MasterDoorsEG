<div class="table-responsive">
 <table class="table table-bordered table-striped myTableCommissionData w-100 text-center">
  <thead class="bg-info text-center">
   <th>#</th>
   <th>Customer</th>
   <th>Project</th>
   <th>Po Value</th>
   <th>Commission</th>
   <th>Paied</th>
   <th>Valid</th>
  </thead>
  <tbody>
   <?php
    include_once("authCheck.php");
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $salesId=(int)$_POST['orderId'];
    $num=0;
    $valid=0;
    $sqlGetSales="SELECT `codeid`,`fullname` FROM `users` WHERE `userid` = $salesId";
    $queryGetSales=mysqli_query($link,$sqlGetSales)or die("ERROR :01-AU_AU_S");
    $resGetSales= mysqli_fetch_assoc($queryGetSales); 
    echo "<input class='reportTitel2' value='$resGetSales[fullname]  Sales & Commission ' hidden>";
    if( $salesId == 22){
     $sqlBasicData="SELECT `InstallationComsion`,`jobidref` FROM `customerpo` WHERE  `InstallationComsion` > 0";
     $queryBasicData=mysqli_query($link,$sqlBasicData)or die("ERROR :01-AU_AU_S".mysqli_error($link));
     while($resBasicData= mysqli_fetch_assoc($queryBasicData)){
      $sqlGetAllPO="SELECT `jobId`,`projectName`,`customer`,`offerValue` FROM `job` WHERE  `jobId` = $resBasicData[jobidref]";
      $queryGetAllPO=mysqli_query($link,$sqlGetAllPO)or die("ERROR :01-AU_AU_S".mysqli_error($link));
      $resGetAllPO= mysqli_fetch_assoc($queryGetAllPO);
      $sqlCheckCustN="SELECT `customername` FROM `customers` WHERE `customercode` =$resGetAllPO[customer]";
      $queryCheckCustN=mysqli_query($link,$sqlCheckCustN)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
      $resCheckCustN=mysqli_fetch_assoc($queryCheckCustN);
      $sqlStatment="SELECT sum(`withdrawal`) as payment  FROM `cash_transaction` WHERE `empCode` = $salesId  AND `account`= 312105100001 AND `poNum` = $resGetAllPO[jobId] ";  
      $queryStatment=mysqli_query($link,$sqlStatment);
      $statment=mysqli_fetch_assoc($queryStatment);
      $customerName=$resCheckCustN['customername'];
      $comission = ($resGetAllPO['offerValue'] * $resBasicData['InstallationComsion']);
      $paide =$statment['payment'];
      $valid=($comission - $paide);
      echo"<tr>
       <td>$num</td>
       <td>$resCheckCustN[customername]</td>
       <td>$resGetAllPO[projectName]</td>
       <td>".number_format(($resGetAllPO['offerValue']), 2)."</td>
       <td>".number_format(($comission), 2)."</td>
       <td>".number_format(($paide), 2)."</td>
       <td>".number_format(($valid), 2)."</td>
      </tr>"; 
     }
    }
    else{
     $sqlGetAllPO="SELECT `jobId`,`projectName`,`customer`,`Commotion`,`offerValue`,`salesman` FROM `job` 
     WHERE `jobref` = 3 AND `offerStatus` = 'Won' AND `salesman` = $resGetSales[codeid]";
     $queryGetAllPO=mysqli_query($link,$sqlGetAllPO)or die("ERROR :01-AU_AU_S".mysqli_error($link));
     while($resGetAllPO= mysqli_fetch_assoc($queryGetAllPO)){
      $sqlCheckCustN="SELECT `customername` FROM `customers` WHERE `customercode` =$resGetAllPO[customer]";
      $queryCheckCustN=mysqli_query($link,$sqlCheckCustN)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
      $resCheckCustN=mysqli_fetch_assoc($queryCheckCustN);
      $customerName=$resCheckCustN['customername'];
      $comission = ($resGetAllPO['offerValue'] * $resGetAllPO['Commotion']);
      $sqlStatment="SELECT sum(`withdrawal`) as payment  FROM `cash_transaction` WHERE `empCode` = $salesId  AND `account`= 312105100001 AND `poNum` = $resGetAllPO[jobId] ";  
      $queryStatment=mysqli_query($link,$sqlStatment);
      $statment=mysqli_fetch_assoc($queryStatment);
      $paide =$statment['payment'];
      $num++;
      $valid=($comission - $paide);
      echo"<tr>
       <td>$num</td>
       <td>$resCheckCustN[customername]</td>
       <td>$resGetAllPO[projectName]</td>
       <td>".number_format(($resGetAllPO['offerValue']), 2)."</td>
       <td>".number_format(($comission), 2)."</td>
       <td>".number_format(($paide), 2)."</td>
       <td>".number_format(($valid), 2)."</td>
      </tr>";
     }
    }
   ?>
  </tbody>
  <tfoot  class="bg-secondary text-white text-center"> 
   <th colspan="3">Total Progress</th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
  </tfoot>
 </table>
</div>
<script>
 $(document).ready(function(){
  var titleName = $(".reportTitel2").val();
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
               + (currentdate.getMonth()+1)  + "/" 
               + currentdate.getFullYear() + " @ "  
               + currentdate.getHours() + ":"  
               + currentdate.getMinutes() + ":" 
               + currentdate.getSeconds();
  var table = $('.myTableCommissionData').DataTable({
   fixedHeader: true,
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
      columns: [0,1,2,3,4,5,6]
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
      columns: [0,1,2,3,4,5,6]
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
      columns: [0,1,2,3,4,5,6]
     },          
     customize: function ( win ) {
      $(win.document.body)
      .css( {'font-size':'10pt',  'text-align': 'left'} )
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
     Number((pageTotal).toFixed(1)).toLocaleString());   
    // Col B
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
     Number((pageTotal).toFixed(1)).toLocaleString()); 
// Col C
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
     Number((pageTotal).toFixed(1)).toLocaleString()); 

// Col D
    total = api
    .column( 6 )
    .data()
    .reduce(function(a, b){
     return intval(a) + intval(b);
    }, 0 );
     pageTotal = api
    .column( 6, {page: 'current'} )
    .data()
    .reduce(function(a, b){
     return intval(a) + intval(b);
    }, 0 );
    $(api.column( 6 ).footer() ).html(
     Number((pageTotal).toFixed(1)).toLocaleString()); 
    }
   });
  });
</script>
<style>
 h1{font-size: 12px;}
</style>
<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $sn=0;
 echo"=<input value='Payroll For Staff' class='reportTitel' hidden><h3 class='text-center text-body'>Payroll For Staff</h3>";
?>
<div class="table-responsive-lg">
 <table class='table table-sm table-bordered table-striped w-100 myTableSallary'>
  <thead class='bg-info text-center'>
   <th>SN</th>
   <th>Name</th>
   <th>Position</th>
   <th>Works Day</th>
   <th>Day Value</th>
   <th>Day Amount</th>
   <th>Reword</th>
   <th>pelanty</th>
   <th>Net</th>
   <th></th>
  </thead>
  <tbody>
   <?php
    $sqlSalesInoice="SELECT `staffRId`,count(`attendDate`) as dayWork, sum(`penalty`) as nag, sum(`Reward`) as plu 
    FROM `outsidemanpower` WHERE `paymentRef` = 0  group by `staffRId`";
    $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 01");
    if(mysqli_num_rows($querySalesInoice) > 0){
     While($salesInoiceData=mysqli_fetch_assoc($querySalesInoice)){
      $sn++;
      $empId = $salesInoiceData['staffRId'];
      $sqlStaff="SELECT `staffname`,`staffposition`,`dayval` FROM `allstaff` WHERE `id` = $salesInoiceData[staffRId]";
      $queryStaff=mysqli_query($link,$sqlStaff)or die("ERROR_SNSC : 01");
      $staffData=mysqli_fetch_assoc($queryStaff);
      $workAmount=($salesInoiceData['dayWork'] * $staffData['dayval'] );
      $rewordAmount=($salesInoiceData['plu'] * $staffData['dayval'] );
      $pelantyAmount=($salesInoiceData['nag'] * $staffData['dayval'] );
      $net=(($workAmount + $rewordAmount ) - $pelantyAmount);
      
      
      
      echo"<tr>
       <td>$sn</td>
       <td>$staffData[staffname]</td>
       <td>$staffData[staffposition]</td>
       <td>$salesInoiceData[dayWork]</td>
       <td>$staffData[dayval]</td>
       <td>".number_format(($workAmount), 2)."</td> 
       <td>".number_format(($rewordAmount), 2)."</td>
       <td>".number_format(($pelantyAmount), 2)."</td>
       <td>".number_format(($net), 2)."</td>
       <td><button class='btn btn-info btn-xs w-100 vaildSallary' value='$salesInoiceData[staffRId]'>Pay</button></td>        
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
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
  </tfoot>
 </table>
</div>
<!-- Modal -->
<div class="modal fade" id="sallaryModal" aria-hidden="true" aria-labelledby="sallaryModalData" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title" id="sallaryModalData"></h5>
    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   </div>
   <div class="modal-body sallaryData"></div>
  </div>
 </div>
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
  var table = $('.myTableSallary').DataTable({
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
      columns: [0,1,2,3,4,5,6,7,8]
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
      columns: [0,1,2,3,4,5,6,7,8]
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
      columns: [0,1,2,3,4,5,6,7,8]
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
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
    
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
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
                total = api
            .column( 7 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 7, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 7 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   

            
                total = api
            .column( 8 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 8, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 8 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   

        }

   });


 $(".close").click(function(){
  $("#sallaryModal").modal('toggle');
 });
 $(".vaildSallary").click(function(){
  var invId = $(this).val();
  $.ajax({
   url:'dist/php/sallaryDetils.php',
   type:"POST",
   data:{stafId:invId},
   success: function(getSuuplierWithdrawalData){
    $(".sallaryData").html('');
    $("#sallaryModalData").html('');
    $(".sallaryData").html(getSuuplierWithdrawalData);
    $("#sallaryModal").modal('show');  
   }
  });
  return false;
 });
 }); 
</script>

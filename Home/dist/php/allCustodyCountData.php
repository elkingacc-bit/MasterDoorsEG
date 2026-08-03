<style>
 h1{font-size: 12px;}
</style>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped  myTableCustdy w-100">
  <thead class="bg-primary text-center">
   <th class="col-1">SN</th>
   <th>Employee</th>
   <th>Withdrawal</th>
   <th>Cashback</th>
   <th>Remaining</th>
   <th>Show</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $sqlCustodyData="SELECT `empCode` , sum(`amount`) as cash,sum(`cashBack`) as comback FROM `custody` WHERE  `custodyRef` = 1 GROUP BY `empCode`";
    $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_SNSC : 02");
    $sn=0;
    $custodyCount=mysqli_num_rows($queryCustodyData);
    if($custodyCount > 0){
    while($custodyGetData=mysqli_fetch_assoc($queryCustodyData)){
     $sn++;
     $empCode=$custodyGetData['empCode'];
     $sqlCustodyEmp="SELECT `fullname` FROM `users` WHERE `userid` = $empCode";
     $queryEmpData=mysqli_query($link,$sqlCustodyEmp)or die("ERROR_SNSC : 01");
     $empGetData=mysqli_fetch_assoc($queryEmpData);
     $custody=($custodyGetData['cash'] - $custodyGetData['comback']);
     echo"<tr>
      <td>$sn</td>
      <td>$empGetData[fullname]</td>
      <td>".number_format(($custodyGetData['cash']), 2)."</td>
      <td>".number_format(($custodyGetData['comback']), 2)."</td>
      <td>".number_format(($custody), 2)."</td>
      <td><button class='btn btn-link getEmpCustody' value='$empCode'>Valid</button></td>
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
  </tfoot>
 </table>
</div>
<!-- Modal -->
<div class="modal fade" id="custodyModal" aria-hidden="true" aria-labelledby="custodyModalData" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title" id="custodyModalData"></h5>
    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   </div>
   <div class="modal-body custodyData"></div>
  </div>
 </div>
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
   
  var table = $('.myTableCustdy').DataTable( {
     
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
            title:'Custdy Statment '+datetime,
            filename: function () {
            return "Custdy Statment" },
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [  0,1,2,3,4,5]
            },
            footer: false,
            
        },
        
        {
            extend: 'pdf',
            text: 'PDF',
            title:'Custdy Statment '+datetime,
             filename: function () {
            return "Custdy Statment" },
            extension: '.pdf',
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [  0,1,2,3,4,5]
            },
            footer: false,
            
        },
        
    {
      extend: 'print',
      text: 'Print',
      className: 'btn btn-secondary',
      title:'Custdy Statment '+datetime,
      footer: true,
       exportOptions: {
           
                   columns: [ 0,1,2,3,4,5]
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







  $(".close").click(function(){
   $("#custodyModal").modal('toggle');
  });
  $(".getEmpCustody").click(function(){
   var empName =$(this).val();
   $.ajax({
    url:'dist/php/cheackWithdrawCustody.php',
    type:"POST",
    data:{fName:empName},
    success: function(custodyCheack){
     $(".custodyData").html('');
     $("#custodyModalData").html('');
     $("#custodyModalData").html('Open Custody Details');
     $(".custodyData").html(custodyCheack);
     $("#custodyModal").modal('show');
    }
   });
  });
 }); 
</script>

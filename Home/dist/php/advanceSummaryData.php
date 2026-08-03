<style>
 h1{font-size: 12px;}
</style>
<div class="table-responsive-lg">
 <table class='table table-sm table-bordered table-striped myTableAdvanceEmp w-100'>
  <thead class='bg-primary text-center' style="width: 100%;">
   <th>Employee</th>
   <th>Date</th>
   <th>Payment</th>
   <th>Repayment</th>
   <th>Balance</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $empId=$_POST['empId'];
    $sqlEmployee="SELECT `fullname` FROM `users` WHERE `userid` = $empId";
    $queryEmployee=mysqli_query($link,$sqlEmployee)or die("ERROR LOA_S:01");
    $employeeData=mysqli_fetch_assoc($queryEmployee);
    $empName=$employeeData['fullname'];
    $advanceBalance=0;
    $sqlAdvanceStatment="SELECT `advanceDate`, `recived`, `cashback`, `installment` FROM `advance` WHERE `recevedRef` = 1 AND `empId` = $empId";  
    $queryAdvanceStatment=mysqli_query($link,$sqlAdvanceStatment);
    WHILE($advanceStatment=mysqli_fetch_assoc($queryAdvanceStatment)){
     $advanceBalance += $advanceStatment['recived'];
     $advanceBalance -= $advanceStatment['cashback'];
     echo"<tr>
      <td>$empName</td>
      <td>$advanceStatment[advanceDate]</td>
      <td>".number_format(($advanceStatment['recived']), 2) ."</td>
      <td>".number_format(($advanceStatment['cashback']), 2) ."</td>
      <td>".number_format(($advanceBalance), 2) ."</td>
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
  var table = $('.myTableAdvanceEmp').DataTable({
   fixedHeader: false,
   //scrollY:'35vh',
   //scrollX: true,
   //scrollCollapse: true,
   //paging: false, 
   order:[[0, "asc"]],
   dom: 'Bfrtip',
    buttons:[
     {
      extend: 'excel',
      text: 'Excel',
      extension: '.xlsx',
      title:'Cash Statment '+datetime,
      filename: function () {
      return "Advance For Employee"},
      className: 'btn btn-secondary',
      exportOptions:{
       columns:[0,1,2,3,4]
      },
      footer: false,
     },
     {
      extend: 'pdf',
      text: 'PDF',
      title:'Advance For Employee '+datetime,
      filename: function () {
      return "Advance For Employee" },
      extension: '.pdf',
      className: 'btn btn-secondary',
      exportOptions: {
       columns:[0,1,2,3,4]
      },
      footer: false,
     },
     {
      extend: 'print',
      text: 'Print',
      className: 'btn btn-secondary',
      title:'{Master Doors EG} | Advance For Employee '+datetime,
      footer: true,
       exportOptions:{
        columns: [0,1,2,3,4]
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
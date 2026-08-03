<style>
 h1{font-size: 12px;}


</style>
<div class="table-responsive-lg">
 <input type="text" class="reportTitel" value="All Income Statement" style="display: none;">
 <table class="table table-sm w-100 table-bordered table-striped myProfitTable text-center">
  <thead class="bg-primary">
   <th>Year</th>
   <th>Revenue</th>
   <th>Expenses</th>  
   <th>Net Income</th>
  </thead>
  <tbody>
   <?php
    include_once("connection.php");
    $netIncomeTotal = 0;
    $netOutcomeTotal = 0;
    $totalIncome = 0;
    $thisYear=date('Y');
    for($incomYear = 2022; $incomYear <= $thisYear ; $incomYear++){

     // FIX: يجب تصفير هذه القيم في بداية كل تكرار للسنة،
     // وإلا فإن أي سنة بلا بيانات (COUNT = 0) ستعرض بالخطأ
     // بيانات السنة السابقة لأن المتغير لم يُعاد ضبطه.
     $netIncome = 0;
     $netOutcome = 0;

     $sqlAccountantIncom="SELECT `transactionsYear`,sum(`debtor`) as debt,sum(`creditor`) as cridt FROM `financialTransactions` 
     WHERE `transactionCode` LIKE '4%' AND `transactionsYear` = $incomYear";  
     $queryAccountantIncome=mysqli_query($link,$sqlAccountantIncom) or die("ERROR_INC:01 - " . mysqli_error($link));
     $tIncomeCount=mysqli_num_rows($queryAccountantIncome);
     if($tIncomeCount > 0){
      $tIncome=mysqli_fetch_assoc($queryAccountantIncome);
      // FIX: SUM() قد ترجع NULL إذا لم توجد صفوف مطابقة رغم أن العدّ سطر واحد
      $netIncome=(($tIncome['cridt'] ?? 0) - ($tIncome['debt'] ?? 0));
      $netIncomeTotal += $netIncome; 
     }
     
     $sqlAccountantOutcom="SELECT `transactionsYear`,sum(`debtor`) as debt,sum(`creditor`) as cridt FROM `financialTransactions` 
     WHERE `transactionCode` LIKE '3%' AND `transactionsYear` = $incomYear"; 
     $queryAccountantOutcome=mysqli_query($link,$sqlAccountantOutcom) or die("ERROR_INC:02 - " . mysqli_error($link));
     $tOucomeCount=mysqli_num_rows($queryAccountantOutcome);
     if($tOucomeCount > 0){
      $tOucome=mysqli_fetch_assoc($queryAccountantOutcome);
      $netOutcome=(($tOucome['debt'] ?? 0) - ($tOucome['cridt'] ?? 0));      
      $netOutcomeTotal += $netOutcome; 
     }
     $netIncomeProfit=($netIncome - $netOutcome);
     $totalIncome += $netIncomeProfit;
     echo"<tr>
      <td>$incomYear</td>
      <td>". number_format( ( $netIncome ), 2) ."</td>
      <td>". number_format( ( $netOutcome ), 2) ."</td>
      <td>". number_format( ( $netIncomeProfit ), 2) ."</td>
     </tr>";
    }
   ?>
  </tbody>
  <tfoot>
    <th>Total</th>
    <th><?php echo number_format( ( $netIncomeTotal ), 2) ?></th>
    <th><?php echo number_format( ( $netOutcomeTotal ), 2) ?></th>
    <th><?php echo number_format( ( $totalIncome ), 2) ?></th>
  </tfoot>
 </table>
 <hr>
 <h4>Total Owners Equity </h4>
 <br>
 <center>
  <table class="table table-sm table-bordered table-striped text-center w-75">
   <thead  class="bg-success">
    <th>Partner</th>
    <th>Percentage</th>
    <th>Earnings</th>        
    <th>Drawings</th>
    <th>Actual</th>
   </thead>
   <tbody>
    <?php
     $sqlAccountantCodeData="SELECT `accountCode`,`accountName` FROM `accountantcode` WHERE `accountCode` LIKE '5%' AND `codeLen` = 12";  
     $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData) or die("ERROR_INC:03 - " . mysqli_error($link));
     while($accData=mysqli_fetch_assoc($queryAccountantCodeData)){
      // FIX: يجب تصفير هذه القيم داخل كل تكرار للشريك،
      // وإلا فإن الشريك الذي لا يملك صفًا في partnershipRatio أو cash_transaction
      // سيظهر بنسبة/سحوبات الشريك السابق في القائمة بالخطأ.
      $percentage = 0;
      $partnerWithdrawal = 0;

      $patrnerCode=$accData['accountCode'];
      $patrnerName=$accData['accountName'];
      $sqlPartnerData="SELECT `partnerPercentage` FROM `partnershipRatio` WHERE `partnersName` = '$patrnerCode' ";  
      $queryPartnerData=mysqli_query($link,$sqlPartnerData) or die("ERROR_INC:04 - " . mysqli_error($link));
      if(mysqli_num_rows($queryPartnerData) > 0){
       $partnerData=mysqli_fetch_assoc($queryPartnerData);
       $percentage=$partnerData['partnerPercentage'];
      }
      $sqlPartnerWithdrawal="SELECT sum(`withdrawal`) as partnerwithdrawal FROM `cash_transaction` WHERE `account` = $patrnerCode ";
      $queryPartnerWithdrawal=mysqli_query($link,$sqlPartnerWithdrawal)or die("ERROR_SNSC : 01 - " . mysqli_error($link));
      if(mysqli_num_rows($queryPartnerWithdrawal) > 0){
       $artnerWithdrawaltData=mysqli_fetch_assoc($queryPartnerWithdrawal);
       // FIX: SUM() ترجع NULL إن لم توجد سحوبات، فيجب تعويضها بصفر
       $partnerWithdrawal=$artnerWithdrawaltData['partnerwithdrawal'] ?? 0; 
      }
      $amount = ( ($totalIncome * $percentage ) / 100 );
      $validAmount = ( $amount - $partnerWithdrawal );
      echo"<tr>
       <td><button class='btn btn-link ownersData' value='$patrnerCode'>".htmlspecialchars($patrnerName)."</button></td>
       <td>$percentage %</td>
       <td>". number_format( ( $amount ), 2)."</td>
       <td>". number_format( ( $partnerWithdrawal ), 2)."</td>
       <td>". number_format( ( $validAmount ), 2)."</td>
      </tr>";
     }
    ?>
   </tbody>
  </table>
 </center>
</div>


<!-- Modal -->
<div class="modal fade" id="ownersEquityModal" tabindex="-1" role="dialog" aria-labelledby="ownersEquityModalData" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ownersEquityModalData">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ownersEquityData">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
 $(document).ready(function(){
   $(".close").click(function(){
  $("#ownersEquityModal").modal('toggle');
 });
   
   $(".ownersData").click(function(){
  var ownerId = $(this).val();
  $.ajax({
   url:'dist/php/ownersEquityDetils.php',
   type:"POST",
   data:{ownerCode:ownerId},
   success: function(getOwnersEquityData){
    $("#ownersEquityModalData").html('');
    $("#ownersEquityModalData").html('Owners Statement Account');
    $(".ownersEquityData").html('');
    $(".ownersEquityData").html(getOwnersEquityData);
    $("#ownersEquityModal").modal('show');  
   }
  });
  return false;
 });

  var titleName = $(".reportTitel").val();
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
   + (currentdate.getMonth()+1)  + "/" 
   + currentdate.getFullYear() + " @ "  
   + currentdate.getHours() + ":"  
   + currentdate.getMinutes() + ":" 
   + currentdate.getSeconds();
  var table = $('.myProfitTable').DataTable({
   fixedHeader: false,
   scrollY:'20vh',
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
      columns: [0,1,2,3]
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
      columns: [0,1,2,3]
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
      columns: [0,1,2,3]
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
    // Col 1
    total = api
    .column( 1 )
    .data()
    .reduce(function(a, b){ return intval(a) + intval(b); }, 0 );
    pageTotal = api
    .column( 1, {page: 'current'} )
    .data()
    .reduce(function(a, b){ return intval(a) + intval(b); }, 0 );
    $(api.column( 1 ).footer() ).html(
    Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
    // Col 2
    total = api
    .column( 2 )
    .data()
    .reduce(function(a, b){ return intval(a) + intval(b); }, 0 );
    pageTotal = api
    .column( 2, {page: 'current'} )
    .data()
    .reduce(function(a, b){ return intval(a) + intval(b); }, 0 );
    $(api.column( 2 ).footer() ).html(
    Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
    // Col 3
    total = api
    .column( 3 )
    .data()
    .reduce(function(a, b){ return intval(a) + intval(b); }, 0 );
    pageTotal = api
    .column( 3, {page: 'current'} )
    .data()
    .reduce(function(a, b){ return intval(a) + intval(b); }, 0 );
    $(api.column( 3 ).footer() ).html(
    Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
   }
  });
 return false;
 });
</script>
<style>
 h1{font-size: 12px;}
</style>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");

 $thisYear = (int)date('Y');
 $years = [];
 $revenueByYear = [];
 $expenseByYear = [];
 $netIncomeByYear = [];

 for ($y = 2022; $y <= $thisYear; $y++) {
  $sqlIncome = "SELECT SUM(`debtor`) as debt, SUM(`creditor`) as cridt FROM `financialTransactions`
   WHERE `transactionCode` LIKE '4%' AND `transactionsYear` = $y";
  $qIncome = mysqli_query($link, $sqlIncome) or die("ERROR_YOY:01 - " . mysqli_error($link));
  $rIncome = mysqli_fetch_assoc($qIncome);
  $revenue = (($rIncome['cridt'] ?? 0) - ($rIncome['debt'] ?? 0));

  $sqlExpense = "SELECT SUM(`debtor`) as debt, SUM(`creditor`) as cridt FROM `financialTransactions`
   WHERE `transactionCode` LIKE '3%' AND `transactionsYear` = $y";
  $qExpense = mysqli_query($link, $sqlExpense) or die("ERROR_YOY:02 - " . mysqli_error($link));
  $rExpense = mysqli_fetch_assoc($qExpense);
  $expense = (($rExpense['debt'] ?? 0) - ($rExpense['cridt'] ?? 0));

  $years[] = $y;
  $revenueByYear[] = (float)$revenue;
  $expenseByYear[] = (float)$expense;
  $netIncomeByYear[] = (float)($revenue - $expense);
 }

 echo "<input value='Year Over Year Comparison' class='reportTitel' hidden>";
?>
<div class="table-responsive-lg">
 <h3 class="text-center text-body">مقارنة الأداء عبر السنين (Year-over-Year Comparison)</h3>
 <table class="table table-sm table-bordered table-striped myTableYoY w-100 text-center">
  <thead class="bg-primary text-center">
   <th>السنة (Year)</th>
   <th>الإيرادات (Revenue)</th>
   <th>المصروفات (Expenses)</th>
   <th>صافي الدخل (Net Income)</th>
   <th>هامش صافي الدخل % (Net Margin %)</th>
  </thead>
  <tbody>
   <?php foreach ($years as $i => $y): ?>
    <?php $margin = ($revenueByYear[$i] != 0) ? ($netIncomeByYear[$i] / $revenueByYear[$i]) * 100 : 0; ?>
    <tr>
     <td><?php echo htmlspecialchars((string)$y); ?></td>
     <td><?php echo number_format($revenueByYear[$i], 2); ?></td>
     <td><?php echo number_format($expenseByYear[$i], 2); ?></td>
     <td class="<?php echo $netIncomeByYear[$i] < 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold'; ?>"><?php echo number_format($netIncomeByYear[$i], 2); ?></td>
     <td><?php echo number_format($margin, 2); ?>%</td>
    </tr>
   <?php endforeach; ?>
  </tbody>
 </table>
 <div class="row justify-content-center mt-4">
  <div class="col-md-8" style="height:40vh;">
   <canvas id="yoyChart"></canvas>
  </div>
 </div>
</div>
<script src="plugins/chart.js/Chart.min.js"></script>
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
  var table = $('.myTableYoY').DataTable({
   fixedHeader: false,
   scrollY:'30vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false,
   searching: false,
   ordering: false,
   dom: 'Bfrtip',
   buttons:[
    {
     extend: 'excel',
     text: 'Excel',
     extension: '.xlsx',
     title:titleName+datetime,
     filename: function () { return titleName },
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1,2,3,4] },
     footer: false,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:titleName+datetime,
     filename: function () { return titleName },
     extension: '.pdf',
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1,2,3,4] },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
     exportOptions: { columns: [0,1,2,3,4] },
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

  var yoyLabels = <?php echo json_encode($years); ?>;
  var yoyRevenue = <?php echo json_encode($revenueByYear); ?>;
  var yoyExpense = <?php echo json_encode($expenseByYear); ?>;
  var yoyNetIncome = <?php echo json_encode($netIncomeByYear); ?>;

  var ctxYoY = document.getElementById("yoyChart").getContext('2d');
  new Chart(ctxYoY, {
   type: 'bar',
   data: {
    labels: yoyLabels,
    datasets: [
     { label: 'Revenue', backgroundColor: '#28a745', data: yoyRevenue },
     { label: 'Expenses', backgroundColor: '#dc3545', data: yoyExpense },
     { label: 'Net Income', backgroundColor: '#007bff', data: yoyNetIncome }
    ]
   },
   options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: { position: 'bottom' },
    scales: {
     xAxes: [{ stacked: false }],
     yAxes: [{ stacked: false, ticks: { beginAtZero: true } }]
    }
   }
  });
 });
</script>

<style>
 h1{font-size: 12px;}
 .dashboardAlertRow{border-bottom:1px solid #eee;padding:8px 4px;}
 .dashboardAlertRow:last-child{border-bottom:none;}
 .dashboardAlertCount{font-weight:bold;}
 .dashboardAlertCount.hasAlert{color:#dc3545;}
 .dashboardAlertCount.noAlert{color:#28a745;}
</style>
<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $thisYear = date('Y');

 // 1) Total Income (this year)
 $sqlIncome = "SELECT SUM(`creditor`) - SUM(`debtor`) as total FROM `financialTransactions` WHERE `transactionCode` LIKE '4%' AND `transactionsYear` = $thisYear";
 $resIncome = mysqli_query($link, $sqlIncome) or die("ERROR_DASH:01 - " . mysqli_error($link));
 $rowIncome = mysqli_fetch_assoc($resIncome);
 $totalIncome = (float)($rowIncome['total'] ?? 0);

 // 2) Total Expenses (this year)
 $sqlExpense = "SELECT SUM(`debtor`) - SUM(`creditor`) as total FROM `financialTransactions` WHERE `transactionCode` LIKE '3%' AND `transactionsYear` = $thisYear";
 $resExpense = mysqli_query($link, $sqlExpense) or die("ERROR_DASH:02 - " . mysqli_error($link));
 $rowExpense = mysqli_fetch_assoc($resExpense);
 $totalExpense = (float)($rowExpense['total'] ?? 0);

 // 3) Net Profit
 $netProfit = $totalIncome - $totalExpense;

 // 4) Cash Balance (all-time to date)
 $sqlCash = "SELECT SUM(`income`) - SUM(`withdrawal`) as total FROM `cash_transaction` WHERE `statmentRef` LIKE '116100%'";
 $resCash = mysqli_query($link, $sqlCash) or die("ERROR_DASH:03 - " . mysqli_error($link));
 $rowCash = mysqli_fetch_assoc($resCash);
 $cashBalance = (float)($rowCash['total'] ?? 0);

 // 5) Bank Balance (all-time to date)
 $sqlBank = "SELECT SUM(`debtor`) - SUM(`creditor`) as total FROM `financialTransactions` WHERE `transactionCode` LIKE '11620%'";
 $resBank = mysqli_query($link, $sqlBank) or die("ERROR_DASH:04 - " . mysqli_error($link));
 $rowBank = mysqli_fetch_assoc($resBank);
 $bankBalance = (float)($rowBank['total'] ?? 0);

 // 6) Open Custody Balance (today)
 $sqlCustody = "SELECT SUM(`amount`) - SUM(`cashBack`) as total FROM `custody` WHERE `custodyRef` = 1";
 $resCustody = mysqli_query($link, $sqlCustody) or die("ERROR_DASH:05 - " . mysqli_error($link));
 $rowCustody = mysqli_fetch_assoc($resCustody);
 $custodyBalance = (float)($rowCustody['total'] ?? 0);

 // Chart 1 data: Monthly Income vs Expense (this year)
 $monthlyIncome = array_fill(1, 12, 0);
 $monthlyExpense = array_fill(1, 12, 0);

 $sqlMonthlyIncome = "SELECT MONTH(`transactionsDate`) as m, SUM(`creditor`) - SUM(`debtor`) as total
  FROM `financialTransactions` WHERE `transactionCode` LIKE '4%' AND `transactionsYear` = $thisYear GROUP BY MONTH(`transactionsDate`)";
 $resMonthlyIncome = mysqli_query($link, $sqlMonthlyIncome) or die("ERROR_DASH:06 - " . mysqli_error($link));
 while($row = mysqli_fetch_assoc($resMonthlyIncome)){
  $monthlyIncome[(int)$row['m']] = (float)$row['total'];
 }

 $sqlMonthlyExpense = "SELECT MONTH(`transactionsDate`) as m, SUM(`debtor`) - SUM(`creditor`) as total
  FROM `financialTransactions` WHERE `transactionCode` LIKE '3%' AND `transactionsYear` = $thisYear GROUP BY MONTH(`transactionsDate`)";
 $resMonthlyExpense = mysqli_query($link, $sqlMonthlyExpense) or die("ERROR_DASH:07 - " . mysqli_error($link));
 while($row = mysqli_fetch_assoc($resMonthlyExpense)){
  $monthlyExpense[(int)$row['m']] = (float)$row['total'];
 }

 $monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

 // Chart 2 data: Expense Breakdown by Category (this year, top 8)
 $sqlExpenseBreakdown = "SELECT ac.`accountName` as name, SUM(ft.`debtor`) - SUM(ft.`creditor`) as total
  FROM `financialTransactions` ft
  INNER JOIN `accountantcode` ac ON ac.`accountCode` = ft.`transactionCode`
  WHERE ft.`transactionCode` LIKE '3%' AND ft.`transactionsYear` = $thisYear
  GROUP BY ac.`accountName`
  ORDER BY total DESC
  LIMIT 8";
 $resExpenseBreakdown = mysqli_query($link, $sqlExpenseBreakdown) or die("ERROR_DASH:08 - " . mysqli_error($link));
 $expenseLabels = [];
 $expenseData = [];
 while($row = mysqli_fetch_assoc($resExpenseBreakdown)){
  $expenseLabels[] = $row['name'];
  $expenseData[] = (float)$row['total'];
 }

 // Alerts - same 5 counts as notificationEngine.php's Accountant block
 // (COUNT(DISTINCT ...) used instead of notificationEngine.php's SELECT col ... GROUP BY,
 // since that form errors under ONLY_FULL_GROUP_BY sql_mode - only the count is needed here anyway)
 $sqlAlert1 = "SELECT COUNT(DISTINCT `SOIdRef`) as cnt FROM `supporderitems` WHERE `status` = 5";
 $resAlert1 = mysqli_query($link, $sqlAlert1) or die("ERROR_DASH:09 - " . mysqli_error($link));
 $rowAlert1 = mysqli_fetch_assoc($resAlert1);
 $alertPaidInvoice = (int)($rowAlert1['cnt'] ?? 0);

 $sqlAlert2 = "SELECT COUNT(DISTINCT `SOIdRef`) as cnt FROM `supporderitems` WHERE `status` = 1";
 $resAlert2 = mysqli_query($link, $sqlAlert2) or die("ERROR_DASH:10 - " . mysqli_error($link));
 $rowAlert2 = mysqli_fetch_assoc($resAlert2);
 $alertReceivedInvoice = (int)($rowAlert2['cnt'] ?? 0);

 $sqlAlert3 = "SELECT count(`empCode`) as cnt FROM `custody` WHERE `custodyRef` = 1";
 $resAlert3 = mysqli_query($link, $sqlAlert3) or die("ERROR_DASH:11 - " . mysqli_error($link));
 $rowAlert3 = mysqli_fetch_assoc($resAlert3);
 $alertOpenCustody = (int)($rowAlert3['cnt'] ?? 0);

 $sqlAlert4 = "SELECT `salesInvoiceId` FROM `salesInvoice` WHERE `totalInvoice` != `invoiceCollectAmount`";
 $resAlert4 = mysqli_query($link, $sqlAlert4) or die("ERROR_DASH:12 - " . mysqli_error($link));
 $alertUnpaidSales = mysqli_num_rows($resAlert4);

 $sqlAlert5 = "SELECT `jobId` FROM `job` WHERE `offerStatus` = 'Won' AND `invoice` = 'No'";
 $resAlert5 = mysqli_query($link, $sqlAlert5) or die("ERROR_DASH:13 - " . mysqli_error($link));
 $alertUnbilledJobs = mysqli_num_rows($resAlert5);
?>
<div class="container-fluid">
 <div class="row">
  <div class="col-lg-2 col-md-4 col-6">
   <div class="small-box bg-success">
    <div class="inner">
     <h3><?php echo number_format($totalIncome, 2); ?></h3>
     <p>Total Income (<?php echo $thisYear; ?>)</p>
    </div>
    <div class="icon"><i class="fas fa-arrow-up"></i></div>
   </div>
  </div>
  <div class="col-lg-2 col-md-4 col-6">
   <div class="small-box bg-danger">
    <div class="inner">
     <h3><?php echo number_format($totalExpense, 2); ?></h3>
     <p>Total Expenses (<?php echo $thisYear; ?>)</p>
    </div>
    <div class="icon"><i class="fas fa-arrow-down"></i></div>
   </div>
  </div>
  <div class="col-lg-2 col-md-4 col-6">
   <div class="small-box bg-info">
    <div class="inner">
     <h3><?php echo number_format($netProfit, 2); ?></h3>
     <p>Net Profit (<?php echo $thisYear; ?>)</p>
    </div>
    <div class="icon"><i class="fas fa-chart-line"></i></div>
   </div>
  </div>
  <div class="col-lg-2 col-md-4 col-6">
   <div class="small-box bg-primary">
    <div class="inner">
     <h3><?php echo number_format($cashBalance, 2); ?></h3>
     <p>Cash Balance</p>
    </div>
    <div class="icon"><i class="far fa-money-bill-alt"></i></div>
   </div>
  </div>
  <div class="col-lg-2 col-md-4 col-6">
   <div class="small-box bg-secondary">
    <div class="inner">
     <h3><?php echo number_format($bankBalance, 2); ?></h3>
     <p>Bank Balance</p>
    </div>
    <div class="icon"><i class="fas fa-landmark"></i></div>
   </div>
  </div>
  <div class="col-lg-2 col-md-4 col-6">
   <div class="small-box bg-warning">
    <div class="inner">
     <h3><?php echo number_format($custodyBalance, 2); ?></h3>
     <p>Open Custody Balance</p>
    </div>
    <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
   </div>
  </div>
 </div>

 <div class="row">
  <div class="col-md-7">
   <div class="card card-info">
    <div class="card-header"><h3 class="card-title">Monthly Income vs Expense (<?php echo $thisYear; ?>)</h3></div>
    <div class="card-body">
     <canvas id="monthlyIncomeExpenseChart" style="min-height: 300px; height: 300px;"></canvas>
    </div>
   </div>
  </div>
  <div class="col-md-5">
   <div class="card card-info">
    <div class="card-header"><h3 class="card-title">Expense Breakdown (<?php echo $thisYear; ?>)</h3></div>
    <div class="card-body">
     <canvas id="expenseBreakdownChart" style="min-height: 300px; height: 300px;"></canvas>
    </div>
   </div>
  </div>
 </div>

 <div class="row">
  <div class="col-md-12">
   <div class="card card-warning">
    <div class="card-header"><h3 class="card-title">Alerts</h3></div>
    <div class="card-body">
     <div class="row">
      <div class="col-md-4 dashboardAlertRow">
       <span class="dashboardAlertCount <?php echo $alertPaidInvoice > 0 ? 'hasAlert' : 'noAlert'; ?>"><?php echo $alertPaidInvoice; ?></span>
       Paid Invoice
      </div>
      <div class="col-md-4 dashboardAlertRow">
       <span class="dashboardAlertCount <?php echo $alertReceivedInvoice > 0 ? 'hasAlert' : 'noAlert'; ?>"><?php echo $alertReceivedInvoice; ?></span>
       Received Invoice
      </div>
      <div class="col-md-4 dashboardAlertRow">
       <span class="dashboardAlertCount <?php echo $alertOpenCustody > 0 ? 'hasAlert' : 'noAlert'; ?>"><?php echo $alertOpenCustody; ?></span>
       Open Custody
      </div>
      <div class="col-md-4 dashboardAlertRow">
       <span class="dashboardAlertCount <?php echo $alertUnpaidSales > 0 ? 'hasAlert' : 'noAlert'; ?>"><?php echo $alertUnpaidSales; ?></span>
       Collect Invoice
      </div>
      <div class="col-md-4 dashboardAlertRow">
       <span class="dashboardAlertCount <?php echo $alertUnbilledJobs > 0 ? 'hasAlert' : 'noAlert'; ?>"><?php echo $alertUnbilledJobs; ?></span>
       Billing in Process
      </div>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>

<script src="plugins/chart.js/Chart.min.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
  var monthLabels = <?php echo json_encode($monthLabels); ?>;
  var monthlyIncomeData = <?php echo json_encode(array_values($monthlyIncome)); ?>;
  var monthlyExpenseData = <?php echo json_encode(array_values($monthlyExpense)); ?>;

  var ctxMonthly = document.getElementById("monthlyIncomeExpenseChart").getContext('2d');
  new Chart(ctxMonthly, {
   type: 'bar',
   data: {
    labels: monthLabels,
    datasets: [
     { label: 'Income', backgroundColor: '#28a745', data: monthlyIncomeData },
     { label: 'Expense', backgroundColor: '#dc3545', data: monthlyExpenseData }
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

  var expenseLabels = <?php echo json_encode($expenseLabels); ?>;
  var expenseData = <?php echo json_encode($expenseData); ?>;
  var expenseColors = ['#007bff','#dc3545','#28a745','#ffc107','#17a2b8','#6f42c1','#fd7e14','#20c997'];

  var ctxExpense = document.getElementById("expenseBreakdownChart").getContext('2d');
  new Chart(ctxExpense, {
   type: 'doughnut',
   data: {
    labels: expenseLabels,
    datasets: [{ backgroundColor: expenseColors, data: expenseData }]
   },
   options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: { position: 'bottom' }
   }
  });
 });
</script>

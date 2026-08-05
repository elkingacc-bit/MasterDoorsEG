<style>
 h1{font-size: 12px;}
 .frGood{color:#155724;background-color:#d4edda;}
 .frBad{color:#721c24;background-color:#f8d7da;}
</style>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $thisYear = (int)date('Y');
 $year = isset($_POST['year']) && $_POST['year'] !== '' ? (int)$_POST['year'] : $thisYear;
 $asOfDate = $year . '-12-31';

 $stmt = mysqli_prepare($link, "SELECT ft.`transactionCode`,
   SUM(ft.`debtor`) as totalDebtor, SUM(ft.`creditor`) as totalCreditor,
   MIN(ac.`level2Code`) as level2Code
  FROM `financialTransactions` ft
  LEFT JOIN `accountantcode` ac ON ac.`accountCode` = ft.`transactionCode`
  LEFT JOIN `customers` cus ON cus.`customercode` = ft.`transactionCode`
  LEFT JOIN `allsuppliers` sup ON sup.`suppliercode` = ft.`transactionCode`
  WHERE ft.`transactionsDate` <= ?
  GROUP BY ft.`transactionCode`");
 mysqli_stmt_bind_param($stmt, "s", $asOfDate);
 mysqli_stmt_execute($stmt);
 $result = mysqli_stmt_get_result($stmt);

 $currentAssets = 0;   // 112 + 114 + 116
 $fixedAssets = 0;     // 110
 $cash = 0;             // 116
 $quickAssets = 0;      // 116 + 114
 $currentLiabilities = 0; // 212 (+ suppliers)
 $longTermLiabilities = 0; // 214
 $totalEquity = 0;      // 510
 $totalRevenue = 0;
 $totalExpense = 0;

 while ($row = mysqli_fetch_assoc($result)) {
  $code = (string)$row['transactionCode'];
  $category = $code[0] ?? '';
  $debtor = (float)$row['totalDebtor'];
  $creditor = (float)$row['totalCreditor'];
  $level2Code = $row['level2Code'];

  if ($category === '1') {
   $net = $debtor - $creditor;
   $sub = $level2Code !== null ? (string)$level2Code : '14'; // العملاء بدون كود شجرة حسابات يتبعوا المدينون
   if ($sub === '10') { $fixedAssets += $net; }
   else { $currentAssets += $net; if ($sub === '16') { $cash += $net; $quickAssets += $net; } if ($sub === '14') { $quickAssets += $net; } }
  } elseif ($category === '2') {
   $net = $creditor - $debtor;
   $sub = $level2Code !== null ? (string)$level2Code : '12'; // الموردين بدون كود شجرة حسابات يتبعوا الخصوم المتداولة
   if ($sub === '14') { $longTermLiabilities += $net; }
   else { $currentLiabilities += $net; }
  } elseif ($category === '5') {
   $totalEquity += ($creditor - $debtor);
  } elseif ($category === '4') {
   $totalRevenue += ($creditor - $debtor);
  } elseif ($category === '3') {
   $totalExpense += ($debtor - $creditor);
  }
 }
 mysqli_stmt_close($stmt);

 $netIncome = $totalRevenue - $totalExpense;
 $totalEquity += $netIncome;
 $totalAssets = $currentAssets + $fixedAssets;
 $totalLiabilities = $currentLiabilities + $longTermLiabilities;

 $currentRatio = ($currentLiabilities != 0) ? $currentAssets / $currentLiabilities : 0;
 $quickRatio = ($currentLiabilities != 0) ? $quickAssets / $currentLiabilities : 0;
 $debtRatio = ($totalAssets != 0) ? $totalLiabilities / $totalAssets : 0;
 $debtToEquity = ($totalEquity != 0) ? $totalLiabilities / $totalEquity : 0;
 $equityRatio = ($totalAssets != 0) ? $totalEquity / $totalAssets : 0;

 $ratios = [
  ['name' => 'Current Ratio نسبة التداول', 'value' => $currentRatio, 'good' => ($currentRatio >= 1), 'note' => 'الأصول المتداولة / الخصوم المتداولة - الأفضل أن تكون 1 أو أكثر'],
  ['name' => 'Quick Ratio نسبة السيولة السريعة', 'value' => $quickRatio, 'good' => ($quickRatio >= 1), 'note' => '(النقدية + المدينون) / الخصوم المتداولة - الأفضل أن تكون 1 أو أكثر'],
  ['name' => 'Debt Ratio نسبة المديونية', 'value' => $debtRatio, 'good' => ($debtRatio <= 0.5), 'note' => 'إجمالي الخصوم / إجمالي الأصول - كلما قلّت كان أفضل'],
  ['name' => 'Debt-to-Equity نسبة الدين لحقوق الملكية', 'value' => $debtToEquity, 'good' => ($debtToEquity <= 1), 'note' => 'إجمالي الخصوم / إجمالي حقوق الملكية - كلما قلّت كان أفضل'],
  ['name' => 'Equity Ratio نسبة حقوق الملكية', 'value' => $equityRatio, 'good' => ($equityRatio >= 0.5), 'note' => 'حقوق الملكية / إجمالي الأصول - كلما زادت كان أفضل'],
 ];

 echo "<input value='Financial Ratios - Year $year' class='reportTitel' hidden>";
?>
<div class="table-responsive-lg">
 <center>
  <div class="row justify-content-center mb-3">
   <div class="col-md-3">
    <label>السنة (Year)</label>
    <select id="frYear" class="form-control">
     <?php for ($y = $thisYear; $y >= 2022; $y--): ?>
      <option value="<?php echo $y; ?>" <?php echo ($y === $year) ? 'selected' : ''; ?>><?php echo $y; ?></option>
     <?php endfor; ?>
    </select>
   </div>
   <div class="col-md-2 d-flex align-items-end">
    <button class="btn btn-info w-100" id="frRefresh">تحديث</button>
   </div>
  </div>
  <h3 class="text-center text-body">النسب المالية (Financial Ratios) - <?php echo htmlspecialchars((string)$year); ?></h3>
 </center>
 <table class="table table-sm table-bordered table-striped myTableFinancialRatios w-100 text-center">
  <thead class="bg-primary text-center">
   <th>النسبة (Ratio)</th>
   <th>القيمة (Value)</th>
   <th>الحالة (Status)</th>
   <th>التفسير (Interpretation)</th>
  </thead>
  <tbody>
   <?php foreach ($ratios as $r): ?>
    <tr>
     <td><?php echo htmlspecialchars($r['name']); ?></td>
     <td><?php echo number_format($r['value'], 2); ?></td>
     <td class="<?php echo $r['good'] ? 'frGood' : 'frBad'; ?>"><?php echo $r['good'] ? 'جيد (Healthy)' : 'يحتاج مراجعة (Needs Review)'; ?></td>
     <td><?php echo htmlspecialchars($r['note']); ?></td>
    </tr>
   <?php endforeach; ?>
  </tbody>
 </table>
 <h5 class="text-center mt-3">بيانات مصدر الحساب (Source Figures)</h5>
 <table class="table table-sm table-bordered w-75 mx-auto text-center">
  <thead class="bg-secondary text-white">
   <th>البند</th>
   <th>القيمة</th>
  </thead>
  <tbody>
   <tr><td>الأصول المتداولة (Current Assets)</td><td><?php echo number_format($currentAssets, 2); ?></td></tr>
   <tr><td>الأصول الثابتة (Fixed Assets)</td><td><?php echo number_format($fixedAssets, 2); ?></td></tr>
   <tr><td>إجمالي الأصول (Total Assets)</td><td><?php echo number_format($totalAssets, 2); ?></td></tr>
   <tr><td>النقدية (Cash)</td><td><?php echo number_format($cash, 2); ?></td></tr>
   <tr><td>الأصول السريعة (Quick Assets)</td><td><?php echo number_format($quickAssets, 2); ?></td></tr>
   <tr><td>الخصوم المتداولة (Current Liabilities)</td><td><?php echo number_format($currentLiabilities, 2); ?></td></tr>
   <tr><td>الخصوم طويلة الأجل (Long Term Liabilities)</td><td><?php echo number_format($longTermLiabilities, 2); ?></td></tr>
   <tr><td>إجمالي الخصوم (Total Liabilities)</td><td><?php echo number_format($totalLiabilities, 2); ?></td></tr>
   <tr><td>إجمالي حقوق الملكية (Total Equity)</td><td><?php echo number_format($totalEquity, 2); ?></td></tr>
  </tbody>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  $("#frRefresh").click(function(){
   var newYear = $("#frYear").val();
   $(".data_display").load("dist/php/Acc/financialRatiosAnalysis.php", {year: newYear});
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
  var table = $('.myTableFinancialRatios').DataTable({
   fixedHeader: false,
   scrollY:'40vh',
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
     exportOptions:{ columns: [0,1,2,3] },
     footer: false,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:titleName+datetime,
     filename: function () { return titleName },
     extension: '.pdf',
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1,2,3] },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
     exportOptions: { columns: [0,1,2,3] },
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

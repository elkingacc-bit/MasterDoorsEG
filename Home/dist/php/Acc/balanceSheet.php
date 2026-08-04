<style>
 h1{font-size: 12px;}
 .bsSectionBar{background-color:#17a2b8;color:#fff;font-weight:bold;}
 .bsSubHeader{font-weight:bold;}
 .bsSubtotal{background-color:#e9ecef;font-weight:bold;}
 .bsGrandTotal{background-color:#343a40;color:#fff;font-weight:bold;font-size:1.1em;}
 .bsTitleBar{background-color:#ffd400;text-align:center;font-weight:bold;font-size:1.4em;padding:10px;}
</style>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $thisYear = (int)date('Y');
 $year = isset($_POST['year']) && $_POST['year'] !== '' ? (int)$_POST['year'] : $thisYear;
 $asOfDate = $year . '-12-31';

 // اسماء المجموعات الفرعية (level1-level2) بالإنجليزي والعربي - قائمة ثابتة ومعروفة بالكامل من شجرة الحسابات
 // level2Code وحده مش كافي كمفتاح (مثلاً "10" تعني أصول ثابته تحت الأصول، وتعني جارى شركاء تحت حقوق الملكية)
 $level2Labels = [
  '1-10' => ['en' => 'Fixed Assets', 'ar' => 'اصول ثابته'],
  '1-12' => ['en' => 'Current Assets', 'ar' => 'أصول متداوله'],
  '1-14' => ['en' => 'Accounts Receivable', 'ar' => 'مدينون'],
  '1-16' => ['en' => 'Cash & Banks', 'ar' => 'نقديه وبنوك'],
  '2-12' => ['en' => 'Current Liabilities', 'ar' => 'خصوم متداوله'],
  '2-14' => ['en' => 'Long Term Liabilities', 'ar' => 'خصوم ثابته'],
  '5-10' => ['en' => 'Equity', 'ar' => 'جارى شركاء'],
 ];
 function bsLabel($map, $key, $fallback) {
  if (isset($map[$key])) { return $map[$key]['en'] . ' ' . $map[$key]['ar']; }
  return $fallback;
 }

 $stmt = mysqli_prepare($link, "SELECT ft.`transactionCode`,
   SUM(ft.`debtor`) as totalDebtor, SUM(ft.`creditor`) as totalCreditor,
   ANY_VALUE(COALESCE(ac.`accountName`, cus.`customername`, sup.`suppliername`)) as accountName,
   ANY_VALUE(ac.`level2Code`) as level2Code
  FROM `financialTransactions` ft
  LEFT JOIN `accountantcode` ac ON ac.`accountCode` = ft.`transactionCode`
  LEFT JOIN `customers` cus ON cus.`customercode` = ft.`transactionCode`
  LEFT JOIN `allsuppliers` sup ON sup.`suppliercode` = ft.`transactionCode`
  WHERE ft.`transactionsDate` <= ?
  GROUP BY ft.`transactionCode`
  ORDER BY ft.`transactionCode` ASC");
 mysqli_stmt_bind_param($stmt, "s", $asOfDate);
 mysqli_stmt_execute($stmt);
 $result = mysqli_stmt_get_result($stmt);

 // $groups['1']['114'] = ['label'=>..., 'rows'=>[...], 'total'=>0]
 $groups = ['1' => [], '2' => [], '5' => []];
 $totalAssets = 0;
 $totalLiabilities = 0;
 $totalEquity = 0;
 $totalRevenue = 0;
 $totalExpense = 0;

 while ($row = mysqli_fetch_assoc($result)) {
  $code = (string)$row['transactionCode'];
  $category = $code[0] ?? '';
  $accountName = $row['accountName'] ?? ('غير معروف / ' . $code);
  $debtor = (float)$row['totalDebtor'];
  $creditor = (float)$row['totalCreditor'];
  $level2Code = $row['level2Code'];

  if ($category === '1') {
   $net = $debtor - $creditor;
   if (abs($net) < 0.01) { continue; }
   $sub = $level2Code !== null ? ('1-' . $level2Code) : '1-14'; // العملاء بدون كود شجرة حسابات - يتبعوا مجموعة المدينون
   if (!isset($groups['1'][$sub])) { $groups['1'][$sub] = ['label' => bsLabel($level2Labels, $sub, 'Other أخرى'), 'rows' => [], 'total' => 0]; }
   $groups['1'][$sub]['rows'][] = ['name' => $accountName, 'balance' => $net];
   $groups['1'][$sub]['total'] += $net;
   $totalAssets += $net;
  } elseif ($category === '2') {
   $net = $creditor - $debtor;
   if (abs($net) < 0.01) { continue; }
   $sub = $level2Code !== null ? ('2-' . $level2Code) : '2-12'; // الموردين بدون كود شجرة حسابات - يتبعوا مجموعة الخصوم المتداولة
   if (!isset($groups['2'][$sub])) { $groups['2'][$sub] = ['label' => bsLabel($level2Labels, $sub, 'Other أخرى'), 'rows' => [], 'total' => 0]; }
   $groups['2'][$sub]['rows'][] = ['name' => $accountName, 'balance' => $net];
   $groups['2'][$sub]['total'] += $net;
   $totalLiabilities += $net;
  } elseif ($category === '5') {
   $net = $creditor - $debtor;
   if (abs($net) < 0.01) { continue; }
   $sub = $level2Code !== null ? ('5-' . $level2Code) : '5-10';
   if (!isset($groups['5'][$sub])) { $groups['5'][$sub] = ['label' => bsLabel($level2Labels, $sub, 'Other أخرى'), 'rows' => [], 'total' => 0]; }
   $groups['5'][$sub]['rows'][] = ['name' => $accountName, 'balance' => $net];
   $groups['5'][$sub]['total'] += $net;
   $totalEquity += $net;
  } elseif ($category === '4') {
   $totalRevenue += ($creditor - $debtor);
  } elseif ($category === '3') {
   $totalExpense += ($debtor - $creditor);
  }
 }
 mysqli_stmt_close($stmt);

 $netIncome = $totalRevenue - $totalExpense;
 $totalEquity += $netIncome;

 $totalLiabAndEquity = $totalLiabilities + $totalEquity;
 $diff = round($totalAssets - $totalLiabAndEquity, 2);
 $isBalanced = (abs($diff) < 0.01);

 echo "<input value='Balance Sheet - Year $year' class='reportTitel' hidden>";
?>
<div class="table-responsive-lg">
 <center>
  <div class="row justify-content-center mb-3">
   <div class="col-md-3">
    <label>السنة (Year)</label>
    <select id="bsYear" class="form-control">
     <?php for ($y = $thisYear; $y >= 2022; $y--): ?>
      <option value="<?php echo $y; ?>" <?php echo ($y === $year) ? 'selected' : ''; ?>><?php echo $y; ?></option>
     <?php endfor; ?>
    </select>
   </div>
   <div class="col-md-2 d-flex align-items-end">
    <button class="btn btn-info w-100" id="bsRefresh">تحديث</button>
   </div>
  </div>
 </center>
 <div class="bsTitleBar">Balance Sheet ميزانية عمومية</div>
 <table class="table table-sm table-bordered myTableBalanceSheet w-100">
  <thead>
   <tr><th>Account الحساب</th><th>Amount المبلغ</th></tr>
  </thead>
  <tbody>
   <tr><td class="text-center font-weight-bold">Year العام</td><td class="text-center font-weight-bold"><?php echo htmlspecialchars((string)$year); ?></td></tr>

   <tr class="bsSectionBar"><td>Assets الأصول</td><td></td></tr>
   <?php foreach ($groups['1'] as $sub): ?>
    <tr class="bsSubHeader"><td><?php echo htmlspecialchars($sub['label']); ?></td><td></td></tr>
    <?php foreach ($sub['rows'] as $r): ?>
     <tr>
      <td><?php echo htmlspecialchars($r['name']); ?></td>
      <td>$<?php echo number_format($r['balance'], 2); ?></td>
     </tr>
    <?php endforeach; ?>
    <tr class="bsSubtotal">
     <td>Total <?php echo htmlspecialchars($sub['label']); ?> إجمالي</td>
     <td>$<?php echo number_format($sub['total'], 2); ?></td>
    </tr>
   <?php endforeach; ?>
   <tr class="bsGrandTotal">
    <td>Total Assets إجمالي الأصول</td>
    <td>$<?php echo number_format($totalAssets, 2); ?></td>
   </tr>

   <tr class="bsSectionBar"><td>Liabilities &amp; Equity الخصوم &amp; حقوق الملكية</td><td></td></tr>
   <?php foreach ($groups['2'] as $sub): ?>
    <tr class="bsSubHeader"><td><?php echo htmlspecialchars($sub['label']); ?></td><td></td></tr>
    <?php foreach ($sub['rows'] as $r): ?>
     <tr>
      <td><?php echo htmlspecialchars($r['name']); ?></td>
      <td>$<?php echo number_format($r['balance'], 2); ?></td>
     </tr>
    <?php endforeach; ?>
    <tr class="bsSubtotal">
     <td>Total <?php echo htmlspecialchars($sub['label']); ?> إجمالي</td>
     <td>$<?php echo number_format($sub['total'], 2); ?></td>
    </tr>
   <?php endforeach; ?>
   <tr class="bsSubtotal">
    <td>Total Liabilities إجمالي الخصوم</td>
    <td>$<?php echo number_format($totalLiabilities, 2); ?></td>
   </tr>

   <tr class="bsSubHeader"><td>Equity حقوق الملكية</td><td></td></tr>
   <?php foreach ($groups['5'] as $sub): ?>
    <?php foreach ($sub['rows'] as $r): ?>
     <tr>
      <td><?php echo htmlspecialchars($r['name']); ?></td>
      <td>$<?php echo number_format($r['balance'], 2); ?></td>
     </tr>
    <?php endforeach; ?>
   <?php endforeach; ?>
   <tr>
    <td>Current Year Earnings أرباح العام الحالي</td>
    <td>$<?php echo number_format($netIncome, 2); ?></td>
   </tr>
   <tr class="bsSubtotal">
    <td>Total Equity إجمالي حقوق الملكية</td>
    <td>$<?php echo number_format($totalEquity, 2); ?></td>
   </tr>
   <tr class="bsGrandTotal">
    <td>Total Liabilities &amp; Equity إجمالي الخصوم &amp; حقوق الملكية</td>
    <td>$<?php echo number_format($totalLiabAndEquity, 2); ?></td>
   </tr>
  </tbody>
 </table>
 <center>
  <?php if ($isBalanced): ?>
   <h4><span class="badge badge-success">متوازن (Balanced)</span></h4>
  <?php else: ?>
   <h4><span class="badge badge-danger">غير متوازن (Not Balanced) - الفرق: <?php echo number_format(abs($diff), 2); ?></span></h4>
  <?php endif; ?>
 </center>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  $("#bsRefresh").click(function(){
   var newYear = $("#bsYear").val();
   $(".data_display").load("dist/php/Acc/balanceSheet.php", {year: newYear});
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
  var table = $('.myTableBalanceSheet').DataTable({
   fixedHeader: false,
   scrollY:'50vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false,
   searching: false,
   ordering: false,
   info: false,
   dom: 'Bfrtip',
   buttons:[
    {
     extend: 'excel',
     text: 'Excel',
     extension: '.xlsx',
     title:titleName+datetime,
     filename: function () { return titleName },
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1] },
     footer: false,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:titleName+datetime,
     filename: function () { return titleName },
     extension: '.pdf',
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1] },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
     exportOptions: { columns: [0,1] },
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

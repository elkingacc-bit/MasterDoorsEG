<style>
 h1{font-size: 12px;}
 .bsTitleBar{background-color:#ffd400;text-align:center;font-weight:bold;font-size:1.4em;padding:10px;}
</style>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");

 $thisYear = (int)date('Y');
 $year = isset($_POST['year']) && $_POST['year'] !== '' ? (int)$_POST['year'] : $thisYear;
 $monthNames = ["","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

 $budget = [];
 $stmt = mysqli_prepare($link, "SELECT `accountCode`,`budgetMonth`,`budgetAmount` FROM `budgetPlan` WHERE `budgetYear` = ? ORDER BY `accountCode`, `budgetMonth`");
 mysqli_stmt_bind_param($stmt, "i", $year);
 mysqli_stmt_execute($stmt);
 $res = mysqli_stmt_get_result($stmt);
 while ($row = mysqli_fetch_assoc($res)) {
  $budget[$row['accountCode']][(int)$row['budgetMonth']] = (float)$row['budgetAmount'];
 }
 mysqli_stmt_close($stmt);

 $accountCodes = array_keys($budget);

 $accountMeta = [];
 $actual = [];
 if (!empty($accountCodes)) {
  $escapedCodes = array_map(function($c) use ($link) { return "'" . mysqli_real_escape_string($link, $c) . "'"; }, $accountCodes);
  $codesIn = implode(',', $escapedCodes);

  $resMeta = mysqli_query($link, "SELECT `accountCode`,`accountName`,`level1Code` FROM `accountantcode` WHERE `accountCode` IN ($codesIn)") or die("ERROR_BVA:01 - " . mysqli_error($link));
  while ($row = mysqli_fetch_assoc($resMeta)) {
   $accountMeta[$row['accountCode']] = ['name' => $row['accountName'], 'level1Code' => $row['level1Code']];
  }

  $stmtA = mysqli_prepare($link, "SELECT `transactionCode`,`transactionsMonth`, SUM(`debtor`) as d, SUM(`creditor`) as c
   FROM `financialTransactions` WHERE `transactionsYear` = ? AND `transactionCode` IN ($codesIn)
   GROUP BY `transactionCode`,`transactionsMonth`");
  mysqli_stmt_bind_param($stmtA, "i", $year);
  mysqli_stmt_execute($stmtA);
  $resA = mysqli_stmt_get_result($stmtA);
  while ($row = mysqli_fetch_assoc($resA)) {
   $level1 = isset($accountMeta[$row['transactionCode']]['level1Code']) ? (string)$accountMeta[$row['transactionCode']]['level1Code'] : null;
   $d = (float)$row['d'];
   $c = (float)$row['c'];
   $net = ($level1 === '4') ? ($c - $d) : ($d - $c); // إيرادات = دائن-مدين، أى فئة تانية (غالبًا مصروفات) = مدين-دائن
   $actual[$row['transactionCode']][(int)$row['transactionsMonth']] = $net;
  }
  mysqli_stmt_close($stmtA);
 }

 echo "<input value='Budget vs Actual - Year $year' class='reportTitel' hidden>";
?>
<div class="table-responsive-lg">
 <center>
  <div class="row justify-content-center mb-3">
   <div class="col-md-3">
    <label>السنة (Year)</label>
    <select id="bvaYear" class="form-control">
     <?php for ($y = $thisYear + 1; $y >= $thisYear - 2; $y--): ?>
      <option value="<?php echo $y; ?>" <?php echo ($y === $year) ? 'selected' : ''; ?>><?php echo $y; ?></option>
     <?php endfor; ?>
    </select>
   </div>
   <div class="col-md-2 d-flex align-items-end">
    <button class="btn btn-info w-100" id="bvaRefresh">تحديث</button>
   </div>
  </div>
 </center>
 <div class="bsTitleBar">Budget vs Actual الموازنة التقديرية مقابل الفعلي</div>
 <table class="table table-sm table-bordered table-striped myTableBudgetVsActual w-100 text-center">
  <thead class="bg-primary">
   <tr>
    <th rowspan="2">Account الحساب</th>
    <?php foreach ($monthNames as $m => $name): if ($m === 0) continue; ?>
     <th colspan="2"><?php echo $name; ?></th>
    <?php endforeach; ?>
    <th colspan="2">Total</th>
    <th rowspan="2">Variance %</th>
    <th rowspan="2">Usage %</th>
   </tr>
   <tr>
    <?php for ($m = 1; $m <= 12; $m++): ?>
     <th>B</th><th>A</th>
    <?php endfor; ?>
    <th>B</th><th>A</th>
   </tr>
  </thead>
  <tbody>
   <?php if (empty($accountCodes)): ?>
    <tr><td colspan="28">No budget lines for this year - أضف بنود موازنة من صفحة "Add Budget"</td></tr>
   <?php endif; ?>
   <?php foreach ($accountCodes as $code):
    $name = $accountMeta[$code]['name'] ?? $code;
    $totalBudget = 0;
    $totalActual = 0;
   ?>
    <tr>
     <td class="text-left"><?php echo htmlspecialchars($name); ?></td>
     <?php for ($m = 1; $m <= 12; $m++):
      $b = $budget[$code][$m] ?? 0;
      $a = $actual[$code][$m] ?? 0;
      $totalBudget += $b;
      $totalActual += $a;
     ?>
      <td><?php echo $b > 0 ? number_format($b, 0) : ''; ?></td>
      <td><?php echo number_format($a, 0); ?></td>
     <?php endfor; ?>
     <td class="font-weight-bold"><?php echo number_format($totalBudget, 2); ?></td>
     <td class="font-weight-bold"><?php echo number_format($totalActual, 2); ?></td>
     <?php
      $variance = $totalActual - $totalBudget;
      $variancePct = $totalBudget != 0 ? ($variance / $totalBudget * 100) : null;
      $usagePct = $totalBudget != 0 ? ($totalActual / $totalBudget * 100) : 0;
      $usagePctClamped = max(0, $usagePct);
     ?>
     <td class="<?php echo $variance > 0 ? 'text-danger' : 'text-success'; ?>"><?php echo $variancePct === null ? '-' : number_format($variancePct, 1) . '%'; ?></td>
     <td>
      <?php if ($usagePctClamped == 0): ?>
       -
      <?php elseif ($usagePctClamped <= 25): ?>
       <div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-success' style='width: <?php echo min($usagePctClamped,100); ?>%'></div></div>
      <?php elseif ($usagePctClamped <= 50): ?>
       <div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-info' style='width: <?php echo min($usagePctClamped,100); ?>%'></div></div>
      <?php elseif ($usagePctClamped <= 75): ?>
       <div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-warning' style='width: <?php echo min($usagePctClamped,100); ?>%'></div></div>
      <?php else: ?>
       <div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-danger' style='width: <?php echo min($usagePctClamped,100); ?>%'></div></div>
      <?php endif; ?>
     </td>
    </tr>
   <?php endforeach; ?>
  </tbody>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  $("#bvaRefresh").click(function(){
   var newYear = $("#bvaYear").val();
   $(".data_display").load("dist/php/Acc/budgetVsActual.php", {year: newYear});
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
  $('.myTableBudgetVsActual').DataTable({
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
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
     customize: function ( win ) {
      $(win.document.body).css( {'font-size':'7pt',  'text-align': 'left'} );
      $(win.document.body).find( 'table' ).addClass( 'compact' ).css( {'font-size' :'inherit',  'text-align': 'left'} );
     },
    }
   ],
  });
 });
</script>

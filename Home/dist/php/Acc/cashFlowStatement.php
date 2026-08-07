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
 $fromDate = isset($_POST['fromDate']) && $_POST['fromDate'] !== '' ? $_POST['fromDate'] : "$thisYear-01-01";
 $toDate = isset($_POST['toDate']) && $_POST['toDate'] !== '' ? $_POST['toDate'] : date('Y-m-d');

 // طريقة مباشرة (Direct Method): لكل حركة نقدية/بنكية بنلاقي "الطرف المقابل" المسيطر
 // (نفس tableName+tableRowId، مش حساب نقدية/بنك) وبنصنّف حسب فئة الحساب المقابل -
 // نفس أسلوب التصنيف المستخدم فعليًا في balanceSheet.php (level1Code/level2Code).
 // لو معندوش طرف مقابل غير نقدي (تحويل بين الخزينة والبنك مثلاً) بيترصد فى "تحويلات داخلية"
 // وبيتلغي تلقائيًا لأن الطرفين بيدخلوا كحركتين متعاكستين.
 $stmt = mysqli_prepare($link, "SELECT ftc.`financialTransactionsId`, ftc.`debtor` as cashDebtor, ftc.`creditor` as cashCreditor,
   COALESCE(sac.`accountName`, cus.`customername`, sup.`suppliername`) as counterName,
   sac.`level1Code`, sac.`level2Code`, sib.`transactionCode` as counterCode
  FROM `financialTransactions` ftc
  LEFT JOIN `financialTransactions` sib
   ON sib.`tableName` = ftc.`tableName` AND sib.`tableRowId` = ftc.`tableRowId`
   AND sib.`transactionCode` NOT LIKE '116%'
   AND sib.`financialTransactionsId` = (
    SELECT s2.`financialTransactionsId` FROM `financialTransactions` s2
    WHERE s2.`tableName` = ftc.`tableName` AND s2.`tableRowId` = ftc.`tableRowId` AND s2.`transactionCode` NOT LIKE '116%'
    ORDER BY ABS(s2.`debtor` - s2.`creditor`) DESC, s2.`financialTransactionsId` ASC LIMIT 1
   )
  LEFT JOIN `accountantcode` sac ON sac.`accountCode` = sib.`transactionCode`
  LEFT JOIN `customers` cus ON cus.`customercode` = sib.`transactionCode`
  LEFT JOIN `allsuppliers` sup ON sup.`suppliercode` = sib.`transactionCode`
  WHERE ftc.`transactionCode` LIKE '116%' AND ftc.`transactionsDate` BETWEEN ? AND ?");
 mysqli_stmt_bind_param($stmt, "ss", $fromDate, $toDate);
 mysqli_stmt_execute($stmt);
 $result = mysqli_stmt_get_result($stmt);

 $sections = [
  'operating' => ['title' => 'أنشطة تشغيلية (Operating Activities)', 'rows' => [], 'total' => 0],
  'investing' => ['title' => 'أنشطة استثمارية (Investing Activities)', 'rows' => [], 'total' => 0],
  'financing' => ['title' => 'أنشطة تمويلية (Financing Activities)', 'rows' => [], 'total' => 0],
  'other' => ['title' => 'تحويلات داخلية / غير مصنفة (Internal Transfers / Unclassified)', 'rows' => [], 'total' => 0],
 ];

 while ($row = mysqli_fetch_assoc($result)) {
  $net = (float)$row['cashDebtor'] - (float)$row['cashCreditor'];
  if (abs($net) < 0.01) { continue; }

  // level1Code/level2Code أعمدة INT فى الداتابيز - لازم نحولهم لسترينج قبل المقارنة،
  // وlevel2Code مخزّن مجرد (زى '12') من غير بادئة level1 (نفس أسلوب balanceSheet.php).
  $level1 = $row['level1Code'] !== null ? (string)$row['level1Code'] : null;
  $level2 = $row['level2Code'] !== null ? (string)$row['level2Code'] : null;
  $hasTradeMatch = $row['counterCode'] !== null && $level1 === null && $row['counterName'] !== null;

  if ($row['counterCode'] === null) {
   $bucket = 'other';
  } elseif ($level1 === '3' || $level1 === '4') {
   $bucket = 'operating';
  } elseif ($level1 === '1' && in_array($level2, ['12', '14'], true)) {
   $bucket = 'operating';
  } elseif ($level1 === '2' && $level2 === '12') {
   $bucket = 'operating';
  } elseif ($hasTradeMatch) {
   $bucket = 'operating'; // تحصيل من عميل / سداد لمورد بدون كود شجرة حسابات
  } elseif ($level1 === '1') {
   $bucket = 'investing'; // أصول ثابتة أو أي أصل غير متداول آخر
  } elseif ($level1 === '2' || $level1 === '5') {
   $bucket = 'financing'; // خصوم طويلة الأجل / حقوق ملكية
  } else {
   $bucket = 'other';
  }

  $name = $row['counterName'] ?? 'غير مصنف';
  if (!isset($sections[$bucket]['rows'][$name])) { $sections[$bucket]['rows'][$name] = 0; }
  $sections[$bucket]['rows'][$name] += $net;
  $sections[$bucket]['total'] += $net;
 }
 mysqli_stmt_close($stmt);

 $netChange = $sections['operating']['total'] + $sections['investing']['total'] + $sections['financing']['total'] + $sections['other']['total'];

 $stmtBeg = mysqli_prepare($link, "SELECT SUM(`debtor`-`creditor`) as bal FROM `financialTransactions` WHERE `transactionCode` LIKE '116%' AND `transactionsDate` < ?");
 mysqli_stmt_bind_param($stmtBeg, "s", $fromDate);
 mysqli_stmt_execute($stmtBeg);
 $beginningBalance = (float)(mysqli_fetch_assoc(mysqli_stmt_get_result($stmtBeg))['bal'] ?? 0);
 mysqli_stmt_close($stmtBeg);

 $stmtEnd = mysqli_prepare($link, "SELECT SUM(`debtor`-`creditor`) as bal FROM `financialTransactions` WHERE `transactionCode` LIKE '116%' AND `transactionsDate` <= ?");
 mysqli_stmt_bind_param($stmtEnd, "s", $toDate);
 mysqli_stmt_execute($stmtEnd);
 $endingBalanceActual = (float)(mysqli_fetch_assoc(mysqli_stmt_get_result($stmtEnd))['bal'] ?? 0);
 mysqli_stmt_close($stmtEnd);

 $endingBalanceComputed = $beginningBalance + $netChange;
 $diff = round($endingBalanceActual - $endingBalanceComputed, 2);
 $isBalanced = (abs($diff) < 0.01);

 echo "<input value='Cash Flow Statement $fromDate to $toDate' class='reportTitel' hidden>";
?>
<div class="table-responsive-lg">
 <center>
  <div class="row justify-content-center mb-3">
   <div class="col-md-3">
    <label>من تاريخ (From)</label>
    <input type="date" id="cfFromDate" class="form-control" value="<?php echo htmlspecialchars($fromDate); ?>">
   </div>
   <div class="col-md-3">
    <label>إلى تاريخ (To)</label>
    <input type="date" id="cfToDate" class="form-control" value="<?php echo htmlspecialchars($toDate); ?>">
   </div>
   <div class="col-md-2 d-flex align-items-end">
    <button class="btn btn-info w-100" id="cfRefresh">تحديث</button>
   </div>
  </div>
 </center>
 <div class="bsTitleBar">Cash Flow Statement قائمة التدفقات النقدية</div>
 <table class="table table-sm table-bordered myTableCashFlow w-100">
  <thead>
   <tr><th>Account الحساب</th><th>Amount المبلغ</th></tr>
  </thead>
  <tbody>
   <tr><td class="text-center font-weight-bold">Beginning Cash Balance رصيد أول المدة</td><td class="text-center font-weight-bold">$<?php echo number_format($beginningBalance, 2); ?></td></tr>

   <?php foreach (['operating', 'investing', 'financing'] as $key): $sec = $sections[$key]; ?>
    <tr class="bsSectionBar"><td><?php echo htmlspecialchars($sec['title']); ?></td><td></td></tr>
    <?php foreach ($sec['rows'] as $name => $amount): ?>
     <tr>
      <td><?php echo htmlspecialchars($name); ?></td>
      <td>$<?php echo number_format($amount, 2); ?></td>
     </tr>
    <?php endforeach; ?>
    <tr class="bsSubtotal">
     <td>Net Cash from <?php echo htmlspecialchars($sec['title']); ?> صافي</td>
     <td>$<?php echo number_format($sec['total'], 2); ?></td>
    </tr>
   <?php endforeach; ?>

   <?php if (!empty($sections['other']['rows'])): ?>
    <tr class="bsSubHeader"><td><?php echo htmlspecialchars($sections['other']['title']); ?></td><td></td></tr>
    <?php foreach ($sections['other']['rows'] as $name => $amount): ?>
     <tr>
      <td><?php echo htmlspecialchars($name); ?></td>
      <td>$<?php echo number_format($amount, 2); ?></td>
     </tr>
    <?php endforeach; ?>
   <?php endif; ?>

   <tr class="bsGrandTotal">
    <td>Net Change in Cash صافي التغير في النقدية</td>
    <td>$<?php echo number_format($netChange, 2); ?></td>
   </tr>
   <tr class="bsGrandTotal">
    <td>Ending Cash Balance رصيد آخر المدة</td>
    <td>$<?php echo number_format($endingBalanceActual, 2); ?></td>
   </tr>
  </tbody>
 </table>
 <center>
  <?php if ($isBalanced): ?>
   <h4><span class="badge badge-success">متوازن (Balanced)</span></h4>
  <?php else: ?>
   <h4><span class="badge badge-danger">غير متوازن (Mismatch) - الفرق: <?php echo number_format(abs($diff), 2); ?></span></h4>
  <?php endif; ?>
 </center>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  $("#cfRefresh").click(function(){
   var newFrom = $("#cfFromDate").val();
   var newTo = $("#cfToDate").val();
   $(".data_display").load("dist/php/Acc/cashFlowStatement.php", {fromDate: newFrom, toDate: newTo});
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
  var table = $('.myTableCashFlow').DataTable({
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

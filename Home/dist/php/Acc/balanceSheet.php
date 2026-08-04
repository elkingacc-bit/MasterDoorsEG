<style>
 h1{font-size: 12px;}
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
   ANY_VALUE(COALESCE(ac.`accountName`, cus.`customername`, sup.`suppliername`)) as accountName
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

 $assets = [];
 $liabilities = [];
 $equity = [];
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

  if ($category === '1') {
   $net = $debtor - $creditor;
   if (abs($net) >= 0.01) {
    $assets[] = ['code' => $code, 'name' => $accountName, 'balance' => $net];
    $totalAssets += $net;
   }
  } elseif ($category === '2') {
   $net = $creditor - $debtor;
   if (abs($net) >= 0.01) {
    $liabilities[] = ['code' => $code, 'name' => $accountName, 'balance' => $net];
    $totalLiabilities += $net;
   }
  } elseif ($category === '5') {
   $net = $creditor - $debtor;
   if (abs($net) >= 0.01) {
    $equity[] = ['code' => $code, 'name' => $accountName, 'balance' => $net];
    $totalEquity += $net;
   }
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
  <h3 class="text-center text-body">الميزانية العمومية لسنة <?php echo htmlspecialchars((string)$year); ?></h3>
 </center>
 <table class="table table-sm table-bordered table-striped myTableBalanceSheet w-100 text-center">
  <thead class="bg-primary text-center">
   <th>الفئة</th>
   <th>الحساب</th>
   <th>الرصيد</th>
  </thead>
  <tbody>
   <?php foreach ($assets as $r): ?>
    <tr>
     <td>الأصول (Assets)</td>
     <td><?php echo htmlspecialchars($r['name']); ?></td>
     <td><?php echo number_format($r['balance'], 2); ?></td>
    </tr>
   <?php endforeach; ?>
   <tr class="bg-secondary text-white font-weight-bold">
    <td>الأصول (Assets)</td>
    <td>إجمالي الأصول (Total Assets)</td>
    <td><?php echo number_format($totalAssets, 2); ?></td>
   </tr>
   <?php foreach ($liabilities as $r): ?>
    <tr>
     <td>الخصوم (Liabilities)</td>
     <td><?php echo htmlspecialchars($r['name']); ?></td>
     <td><?php echo number_format($r['balance'], 2); ?></td>
    </tr>
   <?php endforeach; ?>
   <tr class="bg-secondary text-white font-weight-bold">
    <td>الخصوم (Liabilities)</td>
    <td>إجمالي الخصوم (Total Liabilities)</td>
    <td><?php echo number_format($totalLiabilities, 2); ?></td>
   </tr>
   <?php foreach ($equity as $r): ?>
    <tr>
     <td>حقوق الملكية (Equity)</td>
     <td><?php echo htmlspecialchars($r['name']); ?></td>
     <td><?php echo number_format($r['balance'], 2); ?></td>
    </tr>
   <?php endforeach; ?>
   <tr>
    <td>حقوق الملكية (Equity)</td>
    <td>صافي الدخل الحالي (Net Income - Current)</td>
    <td><?php echo number_format($netIncome, 2); ?></td>
   </tr>
   <tr class="bg-secondary text-white font-weight-bold">
    <td>حقوق الملكية (Equity)</td>
    <td>إجمالي حقوق الملكية (Total Equity)</td>
    <td><?php echo number_format($totalEquity, 2); ?></td>
   </tr>
  </tbody>
 </table>
 <center>
  <h4 class="mt-3">إجمالي الخصوم وحقوق الملكية (Total Liabilities + Equity): <?php echo number_format($totalLiabAndEquity, 2); ?></h4>
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
     exportOptions:{ columns: [0,1,2] },
     footer: false,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:titleName+datetime,
     filename: function () { return titleName },
     extension: '.pdf',
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1,2] },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
     exportOptions: { columns: [0,1,2] },
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

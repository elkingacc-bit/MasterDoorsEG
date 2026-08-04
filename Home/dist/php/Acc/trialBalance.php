<style>
 h1{font-size: 12px;}
</style>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $asOfDate = isset($_POST['asOfDate']) && $_POST['asOfDate'] !== '' ? $_POST['asOfDate'] : date('Y-m-d');

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

 $sections = [
  '1' => ['title' => 'الأصول (Assets)', 'rows' => []],
  '2' => ['title' => 'الخصوم (Liabilities)', 'rows' => []],
  '3' => ['title' => 'المصروفات (Expenses)', 'rows' => []],
  '4' => ['title' => 'الإيرادات (Revenue)', 'rows' => []],
  '5' => ['title' => 'حقوق الملكية (Equity)', 'rows' => []],
  '9' => ['title' => 'غير مصنف (Unclassified)', 'rows' => []],
 ];
 $grandDebtor = 0;
 $grandCreditor = 0;

 while ($row = mysqli_fetch_assoc($result)) {
  $code = (string)$row['transactionCode'];
  $category = in_array($code[0] ?? '', ['1','2','3','4','5'], true) ? $code[0] : '9';
  $accountName = $row['accountName'] ?? ('غير معروف / ' . $code);
  $debtor = (float)$row['totalDebtor'];
  $creditor = (float)$row['totalCreditor'];
  $grandDebtor += $debtor;
  $grandCreditor += $creditor;
  $sections[$category]['rows'][] = [
   'code' => $code,
   'name' => $accountName,
   'debtor' => $debtor,
   'creditor' => $creditor,
  ];
 }
 mysqli_stmt_close($stmt);

 $diff = round($grandDebtor - $grandCreditor, 2);
 $isBalanced = (abs($diff) < 0.01);

 echo "<input value='Trial Balance as of $asOfDate' class='reportTitel' hidden>";
?>
<div class="table-responsive-lg">
 <center>
  <div class="row justify-content-center mb-3">
   <div class="col-md-3">
    <label>حتى تاريخ (As Of)</label>
    <input type="date" id="tbAsOfDate" class="form-control" value="<?php echo htmlspecialchars($asOfDate); ?>">
   </div>
   <div class="col-md-2 d-flex align-items-end">
    <button class="btn btn-info w-100" id="tbRefresh">تحديث</button>
   </div>
  </div>
  <h3 class="text-center text-body">ميزان المراجعة حتى تاريخ <?php echo htmlspecialchars($asOfDate); ?></h3>
 </center>
 <table class="table table-sm table-bordered table-striped myTableTrialBalance w-100 text-center">
  <thead class="bg-primary text-center">
   <th>الفئة</th>
   <th>كود الحساب</th>
   <th>اسم الحساب</th>
   <th>مدين (Debit)</th>
   <th>دائن (Credit)</th>
  </thead>
  <tbody>
   <?php
    $sectionSubtotals = [];
    foreach ($sections as $key => $section) {
     if (empty($section['rows'])) { continue; }
     $sectionDebtor = 0;
     $sectionCreditor = 0;
     foreach ($section['rows'] as $r) {
      $sectionDebtor += $r['debtor'];
      $sectionCreditor += $r['creditor'];
      echo "<tr>
       <td>" . htmlspecialchars($section['title']) . "</td>
       <td>" . htmlspecialchars($r['code']) . "</td>
       <td>" . htmlspecialchars($r['name']) . "</td>
       <td>" . number_format($r['debtor'], 2) . "</td>
       <td>" . number_format($r['creditor'], 2) . "</td>
      </tr>";
     }
     $sectionSubtotals[] = ['title' => $section['title'], 'debtor' => $sectionDebtor, 'creditor' => $sectionCreditor];
    }
   ?>
  </tbody>
  <tfoot>
   <th></th>
   <th></th>
   <th>الإجمالي العام (Grand Total)</th>
   <th><?php echo number_format($grandDebtor, 2); ?></th>
   <th><?php echo number_format($grandCreditor, 2); ?></th>
  </tfoot>
 </table>
 <h5 class="text-center mt-3">إجمالي كل فئة (Subtotals by Category)</h5>
 <table class="table table-sm table-bordered w-75 mx-auto text-center">
  <thead class="bg-secondary text-white">
   <th>الفئة</th>
   <th>مدين (Debit)</th>
   <th>دائن (Credit)</th>
  </thead>
  <tbody>
   <?php foreach ($sectionSubtotals as $s): ?>
    <tr>
     <td><?php echo htmlspecialchars($s['title']); ?></td>
     <td><?php echo number_format($s['debtor'], 2); ?></td>
     <td><?php echo number_format($s['creditor'], 2); ?></td>
    </tr>
   <?php endforeach; ?>
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
  $("#tbRefresh").click(function(){
   var newDate = $("#tbAsOfDate").val();
   $(".data_display").load("dist/php/Acc/trialBalance.php", {asOfDate: newDate});
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
  var table = $('.myTableTrialBalance').DataTable({
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
 });
</script>

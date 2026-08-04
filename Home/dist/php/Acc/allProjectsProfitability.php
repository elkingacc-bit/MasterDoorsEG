<style>
 h1{font-size: 12px;}
</style>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");

 // ---------- 1) كل المشاريع (customerpo + بيانات job/customer) ----------
 $sqlProjects = "SELECT cp.`poId`, cp.`PoNum`, cp.`poVal`, cp.`jobidref`,
   j.`projectName`, cus.`customername`
  FROM `customerpo` cp
  INNER JOIN `job` j ON j.`jobId` = cp.`jobidref`
  LEFT JOIN `customers` cus ON cus.`customercode` = j.`customer`";
 $queryProjects = mysqli_query($link, $sqlProjects) or die("ERROR_APP:01 - " . mysqli_error($link));

 // ---------- 2) تحصيل العميل لكل مشروع (income) ----------
 $collectMap = [];
 $sqlCollect = "SELECT `poNum`, SUM(`income`) as totalCollect FROM `cash_transaction` WHERE `poNum` IS NOT NULL GROUP BY `poNum`";
 $queryCollect = mysqli_query($link, $sqlCollect) or die("ERROR_APP:02 - " . mysqli_error($link));
 while ($r = mysqli_fetch_assoc($queryCollect)) { $collectMap[$r['poNum']] = (float)$r['totalCollect']; }

 // ---------- 3) مدفوعات المشروع (withdrawal) - بنفس شرط العرض الفردي: الحساب لازم يكون كود شجرة حسابات حقيقي ----------
 $paymentMap = [];
 $sqlPayment = "SELECT `poNum`, SUM(`withdrawal`) as totalPayment FROM `cash_transaction`
  WHERE `poNum` IS NOT NULL AND `account` IN (SELECT `accountCode` FROM `accountantcode`)
  GROUP BY `poNum`";
 $queryPayment = mysqli_query($link, $sqlPayment) or die("ERROR_APP:03 - " . mysqli_error($link));
 while ($r = mysqli_fetch_assoc($queryPayment)) { $paymentMap[$r['poNum']] = (float)$r['totalPayment']; }

 // ---------- 4) تكلفة التصنيع (purchasesorder) لكل مشروع ----------
 $manufactureMap = [];
 $sqlManufacture = "SELECT `jobref`, SUM(`totalAmout`) as totalManufacture FROM `purchasesorder` GROUP BY `jobref`";
 $queryManufacture = mysqli_query($link, $sqlManufacture) or die("ERROR_APP:04 - " . mysqli_error($link));
 while ($r = mysqli_fetch_assoc($queryManufacture)) { $manufactureMap[$r['jobref']] = (float)$r['totalManufacture']; }

 // ---------- 5) تكلفة الهاردوير: استعلامين بس لكل المشاريع بدل استعلام متداخل لكل مشروع (N+1) ----------
 $hardwareQtyByJob = []; // [jobref][descripcode] = qty
 $sqlHardwareQty = "SELECT io.`jobref`, op.`descripcode`, SUM(op.`descripquantity`) as qty
  FROM `itemoffer` io
  INNER JOIN `offerproperties` op ON op.`ioidref` = io.`id`
  GROUP BY io.`jobref`, op.`descripcode`";
 $queryHardwareQty = mysqli_query($link, $sqlHardwareQty) or die("ERROR_APP:05 - " . mysqli_error($link));
 while ($r = mysqli_fetch_assoc($queryHardwareQty)) {
  $hardwareQtyByJob[$r['jobref']][$r['descripcode']] = (float)$r['qty'];
 }

 // نفس منطق الشاشة الفردية: أول سعر متاح لكل descripcode من warehouse (الجدول سجل حركات مش جدول أسعار موحد)
 $warehousePriceMap = [];
 $sqlWarehousePrice = "SELECT `description`, ANY_VALUE(`amount`) as amount FROM `warehouse` GROUP BY `description`";
 $queryWarehousePrice = mysqli_query($link, $sqlWarehousePrice) or die("ERROR_APP:06 - " . mysqli_error($link));
 while ($r = mysqli_fetch_assoc($queryWarehousePrice)) { $warehousePriceMap[$r['description']] = (float)$r['amount']; }

 $hardwareCostByJob = [];
 foreach ($hardwareQtyByJob as $jobref => $codes) {
  $total = 0;
  foreach ($codes as $descripcode => $qty) {
   $total += $qty * ($warehousePriceMap[$descripcode] ?? 0);
  }
  $hardwareCostByJob[$jobref] = $total;
 }

 // ---------- تجميع بيانات كل مشروع ----------
 $projects = [];
 while ($p = mysqli_fetch_assoc($queryProjects)) {
  $jobId = (int)$p['jobidref'];
  $poValue = (float)$p['poVal'];
  $totalCollect = $collectMap[$jobId] ?? 0;
  $totalPayment = $paymentMap[$jobId] ?? 0;
  $totalManufacture = $manufactureMap[$jobId] ?? 0;
  $totalHardware = $hardwareCostByJob[$jobId] ?? 0;
  $totalExpenses = $totalPayment + $totalManufacture + $totalHardware;
  $profitAmount = $poValue - $totalExpenses;
  $profitPercent = ($poValue != 0) ? ($profitAmount / $poValue) * 100 : 0;
  $collectedProfitPercent = ($poValue != 0) ? (($totalCollect - $totalExpenses) / $poValue) * 100 : 0;

  $projects[] = [
   'projectName' => $p['projectName'] ?: ('#' . $jobId),
   'customerName' => $p['customername'] ?: 'غير معروف',
   'poNum' => $p['PoNum'],
   'poValue' => $poValue,
   'totalCollect' => $totalCollect,
   'totalExpenses' => $totalExpenses,
   'profitAmount' => $profitAmount,
   'profitPercent' => $profitPercent,
   'collectedProfitPercent' => $collectedProfitPercent,
  ];
 }

 echo "<input value='Projects Profitability Report' class='reportTitel' hidden>";
?>
<div class="table-responsive-lg">
 <h3 class="text-center text-body">تقرير هامش الربح لكل مشروع (Projects Profitability)</h3>
 <table class="table table-sm table-bordered table-striped myTableProjectsProfitability w-100 text-center">
  <thead class="bg-primary text-center">
   <th>المشروع (Project)</th>
   <th>العميل (Customer)</th>
   <th>رقم الأمر (PO Number)</th>
   <th>قيمة الأمر (Order Value)</th>
   <th>تحصيل العميل (Collected)</th>
   <th>إجمالي المصروفات (Expenses)</th>
   <th>هامش الربح (Profit Amount)</th>
   <th>% الربح لقيمة الأمر (Profit % / Order Value)</th>
   <th>% الربح للمُحصَّل (Profit % / Collected)</th>
  </thead>
  <tbody>
   <?php foreach ($projects as $p): ?>
    <tr>
     <td><?php echo htmlspecialchars($p['projectName']); ?></td>
     <td><?php echo htmlspecialchars($p['customerName']); ?></td>
     <td><?php echo htmlspecialchars($p['poNum']); ?></td>
     <td><?php echo number_format($p['poValue'], 2); ?></td>
     <td><?php echo number_format($p['totalCollect'], 2); ?></td>
     <td><?php echo number_format($p['totalExpenses'], 2); ?></td>
     <td class="<?php echo $p['profitAmount'] < 0 ? 'text-danger font-weight-bold' : ''; ?>"><?php echo number_format($p['profitAmount'], 2); ?></td>
     <td class="<?php echo $p['profitPercent'] < 0 ? 'text-danger font-weight-bold' : 'text-success'; ?>"><?php echo number_format($p['profitPercent'], 2); ?>%</td>
     <td class="<?php echo $p['collectedProfitPercent'] < 0 ? 'text-danger font-weight-bold' : 'text-success'; ?>"><?php echo number_format($p['collectedProfitPercent'], 2); ?>%</td>
    </tr>
   <?php endforeach; ?>
  </tbody>
 </table>
</div>
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
  var table = $('.myTableProjectsProfitability').DataTable({
   fixedHeader: false,
   scrollY:'50vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false,
   order:[[7, "desc"]],
   dom: 'Bfrtip',
   buttons:[
    {
     extend: 'excel',
     text: 'Excel',
     extension: '.xlsx',
     title:titleName+datetime,
     filename: function () { return titleName },
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1,2,3,4,5,6,7,8] },
     footer: false,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:titleName+datetime,
     filename: function () { return titleName },
     extension: '.pdf',
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1,2,3,4,5,6,7,8] },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
     exportOptions: { columns: [0,1,2,3,4,5,6,7,8] },
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

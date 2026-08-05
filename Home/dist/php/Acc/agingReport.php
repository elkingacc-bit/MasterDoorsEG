<style>
 h1{font-size: 12px;}
</style>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");

 // ---------- خرائط أسماء العملاء والموردين (استعلام واحد لكل جدول بدل استعلام لكل صف) ----------
 $customerNames = [];
 $res = mysqli_query($link, "SELECT `customercode`,`customername` FROM `customers`") or die("ERROR_AGE:01 - " . mysqli_error($link));
 while ($r = mysqli_fetch_assoc($res)) { $customerNames[$r['customercode']] = $r['customername']; }

 $supplierNames = [];
 $res = mysqli_query($link, "SELECT `suppliercode`,`suppliername` FROM `allsuppliers`") or die("ERROR_AGE:02 - " . mysqli_error($link));
 while ($r = mysqli_fetch_assoc($res)) { $supplierNames[$r['suppliercode']] = $r['suppliername']; }

 // ---------- أعمار المديونية (العملاء) ----------
 $sqlAR = "SELECT `customerCode`,
   SUM(CASE WHEN DATEDIFF(CURDATE(),`salesInvoiceDate`) <= 30 THEN (`totalInvoice`-`invoiceCollectAmount`) ELSE 0 END) as b0_30,
   SUM(CASE WHEN DATEDIFF(CURDATE(),`salesInvoiceDate`) BETWEEN 31 AND 60 THEN (`totalInvoice`-`invoiceCollectAmount`) ELSE 0 END) as b31_60,
   SUM(CASE WHEN DATEDIFF(CURDATE(),`salesInvoiceDate`) BETWEEN 61 AND 90 THEN (`totalInvoice`-`invoiceCollectAmount`) ELSE 0 END) as b61_90,
   SUM(CASE WHEN DATEDIFF(CURDATE(),`salesInvoiceDate`) > 90 THEN (`totalInvoice`-`invoiceCollectAmount`) ELSE 0 END) as b90plus
  FROM `salesInvoice`
  WHERE `totalInvoice` != `invoiceCollectAmount` AND `salesInvoiceDate` IS NOT NULL
  GROUP BY `customerCode`
  ORDER BY `customerCode`";
 $queryAR = mysqli_query($link, $sqlAR) or die("ERROR_AGE:03 - " . mysqli_error($link));
 $arRows = [];
 $arTotals = ['b0_30' => 0, 'b31_60' => 0, 'b61_90' => 0, 'b90plus' => 0];
 while ($r = mysqli_fetch_assoc($queryAR)) {
  $row = [
   'name' => $customerNames[$r['customerCode']] ?? ('غير معروف / ' . $r['customerCode']),
   'b0_30' => (float)$r['b0_30'], 'b31_60' => (float)$r['b31_60'],
   'b61_90' => (float)$r['b61_90'], 'b90plus' => (float)$r['b90plus'],
  ];
  $row['total'] = $row['b0_30'] + $row['b31_60'] + $row['b61_90'] + $row['b90plus'];
  if (abs($row['total']) < 0.01) { continue; }
  $arRows[] = $row;
  foreach (['b0_30','b31_60','b61_90','b90plus'] as $k) { $arTotals[$k] += $row[$k]; }
 }

 // ---------- أعمار الدائنية (الموردين) ----------
 $sqlAP = "SELECT `supplierCode`,
   SUM(CASE WHEN DATEDIFF(CURDATE(),`suppliersInvoiceDate`) <= 30 THEN (`suppliersInvoiceTotal`-`paidAmount`) ELSE 0 END) as b0_30,
   SUM(CASE WHEN DATEDIFF(CURDATE(),`suppliersInvoiceDate`) BETWEEN 31 AND 60 THEN (`suppliersInvoiceTotal`-`paidAmount`) ELSE 0 END) as b31_60,
   SUM(CASE WHEN DATEDIFF(CURDATE(),`suppliersInvoiceDate`) BETWEEN 61 AND 90 THEN (`suppliersInvoiceTotal`-`paidAmount`) ELSE 0 END) as b61_90,
   SUM(CASE WHEN DATEDIFF(CURDATE(),`suppliersInvoiceDate`) > 90 THEN (`suppliersInvoiceTotal`-`paidAmount`) ELSE 0 END) as b90plus
  FROM `supplierInvoice`
  WHERE `suppliersInvoiceTotal` != `paidAmount` AND `suppliersInvoiceDate` IS NOT NULL
  GROUP BY `supplierCode`
  ORDER BY `supplierCode`";
 $queryAP = mysqli_query($link, $sqlAP) or die("ERROR_AGE:04 - " . mysqli_error($link));
 $apRows = [];
 $apTotals = ['b0_30' => 0, 'b31_60' => 0, 'b61_90' => 0, 'b90plus' => 0];
 while ($r = mysqli_fetch_assoc($queryAP)) {
  $row = [
   'name' => $supplierNames[$r['supplierCode']] ?? ('غير معروف / ' . $r['supplierCode']),
   'b0_30' => (float)$r['b0_30'], 'b31_60' => (float)$r['b31_60'],
   'b61_90' => (float)$r['b61_90'], 'b90plus' => (float)$r['b90plus'],
  ];
  $row['total'] = $row['b0_30'] + $row['b31_60'] + $row['b61_90'] + $row['b90plus'];
  if (abs($row['total']) < 0.01) { continue; }
  $apRows[] = $row;
  foreach (['b0_30','b31_60','b61_90','b90plus'] as $k) { $apTotals[$k] += $row[$k]; }
 }

 echo "<input value='AR AP Aging Report' class='reportTitel' hidden>";
?>
<div class="table-responsive-lg">
 <h3 class="text-center text-body">تحليل أعمار الديون (AR / AP Aging)</h3>

 <h4 class="text-center mt-4">مديونية العملاء (Accounts Receivable)</h4>
 <table class="table table-sm table-bordered table-striped myTableAging w-100 text-center">
  <thead class="bg-primary text-center">
   <th>العميل (Customer)</th>
   <th>0-30 يوم</th>
   <th>31-60 يوم</th>
   <th>61-90 يوم</th>
   <th>+90 يوم</th>
   <th>الإجمالي (Total)</th>
  </thead>
  <tbody>
   <?php foreach ($arRows as $r): ?>
    <tr>
     <td><?php echo htmlspecialchars($r['name']); ?></td>
     <td><?php echo number_format($r['b0_30'], 2); ?></td>
     <td><?php echo number_format($r['b31_60'], 2); ?></td>
     <td class="<?php echo $r['b61_90'] > 0 ? 'text-warning font-weight-bold' : ''; ?>"><?php echo number_format($r['b61_90'], 2); ?></td>
     <td class="<?php echo $r['b90plus'] > 0 ? 'text-danger font-weight-bold' : ''; ?>"><?php echo number_format($r['b90plus'], 2); ?></td>
     <td class="font-weight-bold"><?php echo number_format($r['total'], 2); ?></td>
    </tr>
   <?php endforeach; ?>
  </tbody>
  <tfoot>
   <th>الإجمالي (Total)</th>
   <th><?php echo number_format($arTotals['b0_30'], 2); ?></th>
   <th><?php echo number_format($arTotals['b31_60'], 2); ?></th>
   <th><?php echo number_format($arTotals['b61_90'], 2); ?></th>
   <th><?php echo number_format($arTotals['b90plus'], 2); ?></th>
   <th><?php echo number_format(array_sum($arTotals), 2); ?></th>
  </tfoot>
 </table>

 <h4 class="text-center mt-4">دائنية الموردين (Accounts Payable)</h4>
 <table class="table table-sm table-bordered table-striped myTableAgingAP w-100 text-center">
  <thead class="bg-warning text-center">
   <th>المورد (Supplier)</th>
   <th>0-30 يوم</th>
   <th>31-60 يوم</th>
   <th>61-90 يوم</th>
   <th>+90 يوم</th>
   <th>الإجمالي (Total)</th>
  </thead>
  <tbody>
   <?php foreach ($apRows as $r): ?>
    <tr>
     <td><?php echo htmlspecialchars($r['name']); ?></td>
     <td><?php echo number_format($r['b0_30'], 2); ?></td>
     <td><?php echo number_format($r['b31_60'], 2); ?></td>
     <td class="<?php echo $r['b61_90'] > 0 ? 'text-warning font-weight-bold' : ''; ?>"><?php echo number_format($r['b61_90'], 2); ?></td>
     <td class="<?php echo $r['b90plus'] > 0 ? 'text-danger font-weight-bold' : ''; ?>"><?php echo number_format($r['b90plus'], 2); ?></td>
     <td class="font-weight-bold"><?php echo number_format($r['total'], 2); ?></td>
    </tr>
   <?php endforeach; ?>
  </tbody>
  <tfoot>
   <th>الإجمالي (Total)</th>
   <th><?php echo number_format($apTotals['b0_30'], 2); ?></th>
   <th><?php echo number_format($apTotals['b31_60'], 2); ?></th>
   <th><?php echo number_format($apTotals['b61_90'], 2); ?></th>
   <th><?php echo number_format($apTotals['b90plus'], 2); ?></th>
   <th><?php echo number_format(array_sum($apTotals), 2); ?></th>
  </tfoot>
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
  function initAgingTable(selector) {
   return $(selector).DataTable({
    fixedHeader: false,
    scrollY:'30vh',
    scrollX: true,
    scrollCollapse: true,
    paging: false,
    order:[[5, "desc"]],
    dom: 'Bfrtip',
    buttons:[
     {
      extend: 'excel',
      text: 'Excel',
      extension: '.xlsx',
      title:titleName+datetime,
      filename: function () { return titleName },
      className: 'btn btn-secondary',
      exportOptions:{ columns: [0,1,2,3,4,5] },
      footer: false,
     },
     {
      extend: 'pdf',
      text: 'PDF',
      title:titleName+datetime,
      filename: function () { return titleName },
      extension: '.pdf',
      className: 'btn btn-secondary',
      exportOptions:{ columns: [0,1,2,3,4,5] },
      footer: false,
     },
     {
      extend: 'print',
      text: 'Print',
      className: 'btn btn-secondary',
      title:titleName+datetime,
      footer: true,
      exportOptions: { columns: [0,1,2,3,4,5] },
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
  }
  initAgingTable('.myTableAging');
  initAgingTable('.myTableAgingAP');
 });
</script>

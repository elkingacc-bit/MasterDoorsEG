<?php
include_once("connection.php");

/**
 * -----------------------------------------------------------------
 *  الإصلاحات المطبقة في هذا الملف:
 *  1) تنفيذ استعلام customerpo مرة واحدة فقط بدل تكراره 3 مرات (A/B/C).
 *  2) الحماية من القسمة على صفر (poAmount = 0).
 *  3) تعقيم كل المخرجات بـ htmlspecialchars لمنع XSS.
 *  4) استخدام Prepared Statements لمنع SQL Injection.
 *  5) إغلاق تنسيق الجداول بشكل صحيح (حذف </tfoot> اليتيم).
 *  6) معالجة أخطاء الاستعلامات (or die) بدل الفشل الصامت.
 * -----------------------------------------------------------------
 */

/**
 * دالة مساعدة: تجيب اسم العميل + مبلغ التحصيل بأمان (Prepared Statement)
 */
function getCustomerName($link, $custCode)
{
    $stmt = mysqli_prepare($link, "SELECT `customername` FROM `customers` WHERE `customercode` = ?");
    mysqli_stmt_bind_param($stmt, "i", $custCode);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['customername'] ?? 'غير معروف';
}

function getCollectedAmount($link, $custCode)
{
    $stmt = mysqli_prepare($link, "SELECT sum(`creditor`) as amountCollect FROM `financialtransactions` WHERE `transactionCode` = ?");
    mysqli_stmt_bind_param($stmt, "i", $custCode);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    return $row['amountCollect'] ?? 0;
}

/**
 * دالة مساعدة: تحسب نسبة التقدم بأمان (بدون قسمة على صفر)
 */
function calcProgress($poAmount, $collected)
{
    if ($poAmount == 0) {
        return ['valid' => 0, 'progress' => 0];
    }
    $valid    = $poAmount - $collected;
    $progress = ($valid / $poAmount) * 100;
    return ['valid' => $valid, 'progress' => $progress];
}

/**
 * دالة مساعدة: تبني صف واحد من الجدول (مع تعقيم HTML)
 */
function buildRow($custCode, $customerName, $poAmount, $collected, $valid, $progress)
{
    return "<tr>
     <td><button type='button' class='btn btn-link showCustomerStatment' value='" . htmlspecialchars($custCode, ENT_QUOTES) . "'>" . htmlspecialchars($customerName, ENT_QUOTES) . "</button></td>
     <td>" . number_format($poAmount, 2) . "</td>
     <td>" . number_format($collected, 2) . "</td>
     <td>" . number_format($valid, 2) . "</td>
     <td>" . number_format($progress, 1) . " %</td>
    </tr>";
}

/**
 * -----------------------------------------------------------------
 * استعلام واحد فقط لكل بيانات customerpo، ثم تصنيف كل عميل
 * إلى المجموعة الصحيحة (A/B/C) حسب نسبة التقدم بدل تكرار الاستعلام.
 * -----------------------------------------------------------------
 */
$groupRowsABC = ['A' => '', 'B' => '', 'C' => ''];

$customerPoSql = "SELECT `custCode`, sum(`poVal` + `POVat`) as poAmount FROM `customerpo` GROUP BY `custCode`";
$customerPoQuery = mysqli_query($link, $customerPoSql) or die("ERROR :01-AU_AU_S" . mysqli_error($link));

while ($custPoRes = mysqli_fetch_assoc($customerPoQuery)) {
    $custCode      = (int) $custPoRes['custCode'];
    $poAmount      = (float) $custPoRes['poAmount'];
    $customerName  = getCustomerName($link, $custCode);
    $collected     = getCollectedAmount($link, $custCode);

    $calc     = calcProgress($poAmount, $collected);
    $valid    = $calc['valid'];
    $progress = $calc['progress'];

    $row = buildRow($custCode, $customerName, $poAmount, $collected, $valid, $progress);

    if ($poAmount > 0 && $progress >= 0 && $progress <= 15) {
        $groupRowsABC['A'] .= $row;
    } elseif ($poAmount > 0 && $progress > 15 && $progress <= 50) {
        $groupRowsABC['B'] .= $row;
    } elseif ($poAmount > 0 && $progress > 50 && $progress <= 75) {
        $groupRowsABC['C'] .= $row;
    }
}

/**
 * -----------------------------------------------------------------
 * مجموعة D: منطقها مختلف عمدًا (مرتبطة بـ job.jobref = 3)،
 * لذلك أبقيناها باستعلام منفصل، لكن مع نفس إصلاحات الأمان
 * والقسمة على صفر.
 * -----------------------------------------------------------------
 */
$groupRowD = '';
$groupDSql = "SELECT `customerpo`.`custCode`, (SUM(`poVal`) + SUM(`POVat`)) as poAmount
              FROM `customerpo`
              INNER JOIN `job` ON `customerpo`.`jobidref` = `job`.`jobId`
              WHERE `job`.`jobref` = 3 AND `customerpo`.`custCode` = `job`.`customer`
              GROUP BY `custCode`";
$groupDQuery = mysqli_query($link, $groupDSql) or die("ERROR :01-AU_AU_S" . mysqli_error($link));

while ($groupDRes = mysqli_fetch_assoc($groupDQuery)) {
    $custCode     = (int) $groupDRes['custCode'];
    $poAmount     = (float) $groupDRes['poAmount'];
    $customerName = getCustomerName($link, $custCode);
    $collected    = getCollectedAmount($link, $custCode);

    $calc     = calcProgress($poAmount, $collected);
    $valid    = $calc['valid'];
    $progress = $calc['progress'];

    if ($poAmount > 0 && $progress > 75 && $progress <= 100) {
        $groupRowD .= buildRow($custCode, $customerName, $poAmount, $collected, $valid, $progress);
    }
}
?>
<input type="text" id="customerName" class="form-control">

<div class="row">
 <!-- 1 -->
 <div class='col-md-6'>
  <div class='card mb-4'>
   <div class='card-header'> <h6 class='text-center'> Customer A From 0 % To 15 %</h6></div>
   <div class='card-body'>
    <table class='table table-sm table-bordered table-striped myTableAllData w-100 text-center'>
     <thead class='bg-primary text-center'>
      <th>Customer</th>
      <th>Po Amount</th>
      <th>Collect</th>
      <th>Valid</th>
      <th>Progress</th>
     </thead>
     <tbody>
      <?php echo $groupRowsABC['A']; ?>
     </tbody>
    </table>
   </div>
  </div>
 </div>

 <!-- 2 -->
 <div class='col-md-6'>
  <div class='card mb-4'>
   <div class='card-header'> <h6 class='text-center'>Customer B From 16 % To 49 %</h6></div>
   <div class='card-body'>
    <table class='table table-sm table-bordered table-striped myTableAllData w-100 text-center'>
     <thead class='bg-primary text-center'>
      <th>Customer</th>
      <th>Po Amount</th>
      <th>Collect</th>
      <th>Valid</th>
      <th>Progress</th>
     </thead>
     <tbody>
      <?php echo $groupRowsABC['B']; ?>
     </tbody>
    </table>
   </div>
  </div>
 </div>

 <!-- 3 -->
 <div class='col-md-6'>
  <div class='card mb-4'>
   <div class='card-header'> <h6 class='text-center'>Customer C From 50 % To 74 %</h6></div>
   <div class='card-body'>
    <table class='table table-sm table-bordered table-striped myTableAllData w-100 text-center'>
     <thead class='bg-primary text-center'>
      <th>Customer</th>
      <th>Po Amount</th>
      <th>Collect</th>
      <th>Valid</th>
      <th>Progress</th>
     </thead>
     <tbody>
      <?php echo $groupRowsABC['C']; ?>
     </tbody>
    </table>
   </div>
  </div>
 </div>

 <!-- 4 -->
 <div class='col-md-6'>
  <div class='card mb-4'>
   <div class='card-header'> <h6 class='text-center'>Customer D</h6></div>
   <div class='card-body'>
    <table class='table table-sm table-bordered table-striped myTableAllData w-100 text-center'>
     <thead class='bg-primary text-center'>
      <th>Customer</th>
      <th>Po Amount</th>
      <th>Collect</th>
      <th>Valid</th>
      <th>Progress</th>
     </thead>
     <tbody>
      <?php echo $groupRowD; ?>
     </tbody>
    </table>
   </div>
  </div>
 </div>
</div>

<script>
 $(document).ready(function () {
  $(".showCustomerStatment").click(function () {
   var acountCode = $(this).val();
   $.ajax({
    url: 'dist/php/allCustomerStatmentData.php',
    type: "POST",
    data: { accCode: acountCode },
    success: function (customerAllStatmentData) {
     $("#customerReportData").html('');
     $("#customerReportData").html(customerAllStatmentData);
    }
   });
   return false;
  });

  var table = $('.myTableAllData').DataTable({
   fixedHeader: true,
   scrollY: '25vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false,
   order: [[4, 'desc']]
  });

  $('#customerName').on('keyup', function () {
   table.search(this.value).draw();
  });
 });
</script>
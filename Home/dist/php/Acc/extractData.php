<center>
 <div class="table-responsive-lg">
  <table class="table table-sm table-sm w-75 text-center table-bordered table-striped">
   <thead class="text-center">
    <th>Customer Name</th>
    <th>Po Number</th>
    <th>Po Date</th>
    <th>Po Value</th>
   </thead>
   <tbody>
    <?php
     include_once("../authCheck.php");
     date_default_timezone_set("Africa/Cairo");
     include_once("../connection.php");

     // ---------- التحقق من مدخلات POST ----------
     $jopIdA = filter_input(INPUT_POST, 'jopId', FILTER_VALIDATE_INT);
     if ($jopIdA === false || $jopIdA === null) {
         echo "<tr><td colspan='4'>رقم العمل (jopId) غير صحيح</td></tr></tbody></table></div></center>";
         exit;
     }

     // ---------- جلب بيانات أمر الشراء عبر prepared statement ----------
     $stmtPo = mysqli_prepare(
         $link,
         "SELECT `PoNum`,`poDate`,`custCode`,`poVal`,`dwonpay` FROM `customerpo` WHERE `jobidref` = ?"
     );
     mysqli_stmt_bind_param($stmtPo, "i", $jopIdA);
     mysqli_stmt_execute($stmtPo);
     $poResult = mysqli_stmt_get_result($stmtPo);
     $poData = mysqli_fetch_assoc($poResult);
     mysqli_stmt_close($stmtPo);

     if (!$poData) {
         echo "<tr><td colspan='4'>لا يوجد أمر شراء مرتبط بهذا العمل</td></tr></tbody></table></div></center>";
         exit;
     }

     $custCode = $poData['custCode'];

     // ---------- بيانات العميل ----------
     $stmtCustomer = mysqli_prepare($link, "SELECT `customername` FROM `customers` WHERE `customercode` = ?");
     mysqli_stmt_bind_param($stmtCustomer, "s", $custCode);
     mysqli_stmt_execute($stmtCustomer);
     $customerResult = mysqli_stmt_get_result($stmtCustomer);
     $customerData = mysqli_fetch_assoc($customerResult);
     mysqli_stmt_close($stmtCustomer);

     $customerName = $customerData ? htmlspecialchars($customerData['customername']) : '-';
     $payment = $poData['dwonpay'];

     echo "
      <td>{$customerName}</td>
      <td>" . htmlspecialchars($poData['PoNum']) . "</td>
      <td>" . htmlspecialchars($poData['poDate']) . "</td>
      <td>" . htmlspecialchars($poData['poVal']) . "</td>
     ";
    ?>
   </tbody>
  </table>
  <hr>
  <?php
   // ---------- التحقق مما إذا كانت الدفعة المقدمة محصّلة ----------
   $stmtDwon = mysqli_prepare(
       $link,
       "SELECT `income` FROM `cash_transaction`
        WHERE `account` = ? AND `invNumber` = ? AND `description` LIKE 'Collect Dwonpaymen%'"
   );
   mysqli_stmt_bind_param($stmtDwon, "si", $custCode, $jopIdA);
   mysqli_stmt_execute($stmtDwon);
   $dwonResult = mysqli_stmt_get_result($stmtDwon);
   $dwonpaymentCount = mysqli_num_rows($dwonResult);
   mysqli_stmt_close($stmtDwon);

   if ($dwonpaymentCount == 0) {
    echo "<h3>Po Dwonpayment</h3>
     <table class='table table-sm w-75 text-center table-bordered table-striped'>
      <thead class='text-center'>
       <th>Dwonpayment Value</th>
       <th>Collect By</th>
       <th>Collect Date</th>
       <th>Collect</th>
      </thead>
      <tbody>
      <tr>
      <td class='col-3'><input type='number' id='paymentValue' value='" . htmlspecialchars($payment) . "' class='form-control border-0'></td>
      <td class='col-3'><select class='form-control' id='bankName'></select></td>
      <td class='col-3'><input type='date' id='dateCollect' class='form-control border-0'></td>
      <td class='col-3'><button class='btn btn-info collectdwonpayment' value='" . (int)$jopIdA . "'>Collected Now</button></td>
      </tr>
      </tbody>
     </table>
    ";
   } else {
    echo"
     <h3>Billing in Process</h3>
     <table class='table table-sm w-75 text-center table-bordered table-striped'>
     <thead class='text-center'>
      <th>Items</th>
      <th>quantity</th>
      <th>deliver Date</th>
      <th>Deliver Amount</th>
      <th>Collect By</th>
      <th>Collect</th>
     </thead>
     <tbody>
    ";

    // ---------- بنود التسليم غير المحصّلة ----------
    // ملاحظة: count(itemquantity) يحسب عدد صفوف التسليم وليس بالضرورة
    // مجموع الكمية. تحقق من هذا مع منطق العمل الفعلي قبل الاعتماد على
    // الرقم في تقارير مالية. لم أغيّره تلقائياً تفادياً لتعديل رقم مالي
    // دون تأكيد من صاحب النظام.
    $stmtInv = mysqli_prepare(
        $link,
        "SELECT MIN(`itemRowId`) as itemRowId, count(`itemquantity`) as itemquantity, MIN(`deliverdate`) as deliverdate,
                sum(`deliveramount`) as deliveramount, `jobRowId`
         FROM `custorderdeliver`
         WHERE `jobRowId` = ? AND `ref` = '0'
         GROUP BY `jobRowId`"
    );
    mysqli_stmt_bind_param($stmtInv, "i", $jopIdA);
    mysqli_stmt_execute($stmtInv);
    $invResult = mysqli_stmt_get_result($stmtInv);

    $rowIndex = 0;
    while ($salesInoiceData = mysqli_fetch_assoc($invResult)) {
     $rowIndex++;
     $jopId = $salesInoiceData['jobRowId'];

     $stmtJob = mysqli_prepare($link, "SELECT `jobtype` FROM `job` WHERE `jobId` = ?");
     mysqli_stmt_bind_param($stmtJob, "i", $jopId);
     mysqli_stmt_execute($stmtJob);
     $jobResult = mysqli_stmt_get_result($stmtJob);
     $jopData = mysqli_fetch_assoc($jobResult);
     mysqli_stmt_close($stmtJob);

     $orderType = $jopData ? $jopData['jobtype'] : null;
     $itemsType = '-';

     if ($orderType == "Automatic") {
      $stmtItems = mysqli_prepare($link, "SELECT `doortype` FROM `autodoorsoffer` WHERE `jobid` = ?");
      mysqli_stmt_bind_param($stmtItems, "i", $jopId);
      mysqli_stmt_execute($stmtItems);
      $itemsResult = mysqli_stmt_get_result($stmtItems);
      $itemsData = mysqli_fetch_assoc($itemsResult);
      mysqli_stmt_close($stmtItems);
      $itemsType = $itemsData ? $itemsData['doortype'] : '-';
     } elseif ($orderType == "Doors") {
      $stmtItems = mysqli_prepare($link, "SELECT `itemtype` FROM `itemoffer` WHERE `jobref` = ?");
      mysqli_stmt_bind_param($stmtItems, "i", $jopId);
      mysqli_stmt_execute($stmtItems);
      $itemsResult = mysqli_stmt_get_result($stmtItems);
      $itemsData = mysqli_fetch_assoc($itemsResult);
      mysqli_stmt_close($stmtItems);
      $itemsType = $itemsData ? $itemsData['itemtype'] : '-';
     } elseif ($orderType == "Stock") {
      $stmtItems = mysqli_prepare(
          $link,
          "SELECT `id`, `descripcode`,`descripqty`,`descripprice`,`totalprice`,`jobref`,`ref`,`whref`
           FROM `stockoffers` WHERE `jobref` = ?"
      );
      mysqli_stmt_bind_param($stmtItems, "i", $jopId);
      mysqli_stmt_execute($stmtItems);
      $itemsResult = mysqli_stmt_get_result($stmtItems);
      $itemsData = mysqli_fetch_assoc($itemsResult);
      mysqli_stmt_close($stmtItems);
      $itemsType = $itemsData ? $itemsData['descripcode'] : '-';
     }

     // ---------- IDs فريدة لكل صف بدل التكرار (fix للـ bug الأصلي) ----------
     echo "<tr data-row='{$rowIndex}' data-jopid='" . (int)$jopId . "'>
      <td>" . htmlspecialchars($itemsType) . "</td>
      <td>" . htmlspecialchars($salesInoiceData['itemquantity']) . "</td>
      <td><input type='date' class='extractDate form-control border-0' value='" . htmlspecialchars($salesInoiceData['deliverdate']) . "'></td>
      <td class='col-3'><input type='number' class='extractValue form-control border-0' value='" . htmlspecialchars($salesInoiceData['deliveramount']) . "'></td>
      <td class='col-3'><select class='bankName form-control'></select></td>
      <td><button class='btn btn-info collect' value='" . (int)$jopId . "'>Collect</button></td>
     </tr>";
    }
    mysqli_stmt_close($stmtInv);
    echo"</tbody></table>";
   }
  ?>
 </div>
</center>
<script type="text/javascript">
 $(document).ready(function(){
  // تحميل قائمة الخزائن/البنوك في كل عنصر select له كلاس bankName
  // (بدل id مكرر) — يعمل بشكل صحيح مهما كان عدد الصفوف.
  $(".bankName, #bankName").each(function(){
   $(this).load("dist/php/Acc/cashCode.php");
  });

  $(".collectdwonpayment").click(function(){
   var $row = $(this).closest('tr');
   var invId = $(this).val();
   var buyAmount = $row.find("#paymentValue").val();
   var collectDate = $row.find("#dateCollect").val();
   var buyBy = $row.find("#bankName, .bankName").val();

   $.ajax({
    url: 'dist/php/Acc/saveDwonpaymentInvoiceCollect.php',
    type: "POST",
    data: { invNum: invId, amountVal: buyAmount, cashType: buyBy, extractDate: collectDate },
    dataType: 'json',
    success: function(response){
     if (response.status === 'success') {
      alert(response.message);
      $(".data_display").load("dist/html/Acc/newProjectExtract.html");
     } else {
      alert('خطأ: ' + response.message);
     }
    },
    error: function(xhr){
     var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'حدث خطأ أثناء الاتصال بالخادم';
     alert(msg);
    }
   });
   return false;
  });

  // إصلاح البَگ الأصلي: كل صف يقرأ بياناته الخاصة عبر closest('tr')
  // بدل الاعتماد على IDs مكررة كانت تُرجع دائماً بيانات أول صف فقط.
  $(document).on('click', '.collect', function(){
   var $row = $(this).closest('tr');
   var invId = $(this).val();
   var buyDate = $row.find(".extractDate").val();
   var buyAmount = $row.find(".extractValue").val();
   var buyBy = $row.find(".bankName").val();

   $.ajax({
    url: 'dist/php/Acc/newExtractSalesInvoiceCollect.php',
    type: "POST",
    data: { invNum: invId, amountVal: buyAmount, cashType: buyBy, extractDate: buyDate },
    dataType: 'json',
    success: function(response){
     if (response.status === 'success') {
      alert(response.message);
      $(".data_display").load("dist/html/Acc/newProjectExtract.html");
     } else {
      alert('خطأ: ' + response.message);
     }
    },
    error: function(xhr){
     var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'حدث خطأ أثناء الاتصال بالخادم';
     alert(msg);
    }
   });
   return false;
  });
 });
</script>
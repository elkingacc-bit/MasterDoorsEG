<?php
 include_once("../connection.php");

 // رقم الحساب المطلوب التقرير عنه
 // لو الصفحة هتستقبل الحساب من فورم/بحث تاني، تقدر تبعته عن طريق POST['accountCode']
 // وهنا حطينا القيمة اللي طلبتها كافتراضي لو مفيش POST
 $accountCode = isset($_POST['accountCode']) && $_POST['accountCode'] !== ''
   ? mysqli_real_escape_string($link, $_POST['accountCode'])
   : '212100100001';

 // اسم الحساب (اختياري لو موجود في جدول accountantcode)
 $accountName = '-';
 $sqlAccName = "SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = '$accountCode'";
 $queryAccName = mysqli_query($link, $sqlAccName) or die("ERROR_TREASURY:01 - " . mysqli_error($link));
 if (mysqli_num_rows($queryAccName) > 0) {
  $accNameData = mysqli_fetch_assoc($queryAccName);
  $accountName = $accNameData['accountName'] ?? '-';
 }

 // FIX: استخراج اسم الموظف/الجهة من خانة الوصف بدل الاعتماد على عمود empcode
 // بيدور على الاسم بعد كلمة "تمويل من" أو "سلفه خارجيه" (بكل صيغها الممكنة: سلفة/سلفه، خارجية/خارجيه)
 function extractNameFromDescription($description) {
  $description = trim((string)$description);
  if ($description === '') {
   return 'غير مصنف';
  }

  // تمويل من [الاسم]
  if (preg_match('/تمويل\s*من\s*(.+)/u', $description, $m)) {
   $name = trim($m[1]);
   return $name !== '' ? $name : 'غير مصنف';
  }

  // سلفه/سلفة خارجيه/خارجية [الاسم]
  if (preg_match('/سلف[ةه]\s*خارجي[ةه]\s*(.+)/u', $description, $m)) {
   $name = trim($m[1]);
   return $name !== '' ? $name : 'غير مصنف';
  }

  // لو الوصف مطابقش أي من الصيغتين، يترصّف تحت "غير مصنف"
  return 'غير مصنف';
 }

 // جلب حركات الخزينة الخاصة بالحساب مرتبة بالتاريخ (التصنيف حسب الاسم هيتم في PHP بعد الاستخراج)
 $sqlTreasury = "SELECT `transactionDate`,`income`,`withdrawal`,`description`
                 FROM `cash_transaction`
                 WHERE `account` = '$accountCode'
                 ORDER BY `transactionDate` ASC";
 $queryTreasury = mysqli_query($link, $sqlTreasury) or die("ERROR_TREASURY:02 - " . mysqli_error($link));

 $totalIncome    = 0;
 $totalWithdrawal = 0;
 // FIX: تقسيم الصفوف حسب الاسم المستخرج من description بدل قائمة واحدة مسطّحة
 $groups = []; // ['اسم الموظف' => ['rows' => [...], 'income'=>x, 'withdrawal'=>y, 'balance'=>z], ...]

 if (mysqli_num_rows($queryTreasury) > 0) {
  while ($row = mysqli_fetch_assoc($queryTreasury)) {
   $extractedName = extractNameFromDescription($row['description'] ?? '');
   $income     = (float)($row['income'] ?? 0);
   $withdrawal = (float)($row['withdrawal'] ?? 0);

   if (!isset($groups[$extractedName])) {
    $groups[$extractedName] = [
     'rows'       => [],
     'income'     => 0,
     'withdrawal' => 0,
     'balance'    => 0,
    ];
   }

   $groups[$extractedName]['balance'] += ($income - $withdrawal);
   $groups[$extractedName]['income']     += $income;
   $groups[$extractedName]['withdrawal'] += $withdrawal;

   $groups[$extractedName]['rows'][] = [
    'date'        => date("d/m/Y", strtotime($row['transactionDate'])),
    'income'      => $income,
    'withdrawal'  => $withdrawal,
    'description' => $row['description'],
    'balance'     => $groups[$extractedName]['balance'],
   ];

   $totalIncome    += $income;
   $totalWithdrawal += $withdrawal;
  }
 }

 $finalBalance = $totalIncome - $totalWithdrawal;
?>
<div class="table-responsive-lg">

 <!-- ملخص الحساب -->
 <table class="table table-sm w-100 table-bordered table-striped text-center">
  <thead>
   <th>رقم الحساب</th>
   <th>اسم الحساب</th>
   <th>إجمالي الوارد</th>
   <th>إجمالي المنصرف</th>
   <th>الرصيد</th>
  </thead>
  <tbody>
   <tr>
    <td><?php echo htmlspecialchars($accountCode); ?></td>
    <td><?php echo htmlspecialchars($accountName); ?></td>
    <td style="color:green;"><?php echo number_format($totalIncome, 2); ?></td>
    <td style="color:red;"><?php echo number_format($totalWithdrawal, 2); ?></td>
    <td style="color:blue;"><strong><?php echo number_format($finalBalance, 2); ?></strong></td>
   </tr>
  </tbody>
 </table>

 <!-- تفاصيل الحركة -->
 <div style="max-height: 40vh; overflow-y: scroll;">
 <table class="table table-sm w-100 table-bordered table-striped text-center cashTreasuryTable">
  <thead>
   <th>SN</th>
   <th>التاريخ</th>
   <th>الوارد</th>
   <th>المنصرف</th>
   <th>الرصيد</th>
   <th>البيان</th>
  </thead>
  <tbody>
   <?php
    $sn = 0;
    foreach ($groups as $groupName => $g) {
     // رأس المجموعة الخاص بكل اسم مستخرج من الوصف
     echo "<tr style=\"background-color:#e9ecef; font-weight:bold;\">
      <td colspan=\"6\" style=\"text-align:right;\">" . htmlspecialchars($groupName) . "</td>
     </tr>";

     foreach ($g['rows'] as $r) {
      $sn++;
      echo "<tr>
       <td>$sn</td>
       <td>{$r['date']}</td>
       <td style=\"color:green;\">" . number_format($r['income'], 2) . "</td>
       <td style=\"color:red;\">" . number_format($r['withdrawal'], 2) . "</td>
       <td style=\"color:blue;\">" . number_format($r['balance'], 2) . "</td>
       <td>" . htmlspecialchars($r['description']) . "</td>
      </tr>";
     }

     // إجمالي فرعي لكل اسم
     $groupBalance = $g['income'] - $g['withdrawal'];
     echo "<tr style=\"background-color:#f8f9fa;\">
      <td></td>
      <td><strong>إجمالي " . htmlspecialchars($groupName) . "</strong></td>
      <td style=\"color:green;\"><strong>" . number_format($g['income'], 2) . "</strong></td>
      <td style=\"color:red;\"><strong>" . number_format($g['withdrawal'], 2) . "</strong></td>
      <td style=\"color:blue;\"><strong>" . number_format($groupBalance, 2) . "</strong></td>
      <td></td>
     </tr>";
    }
   ?>
  </tbody>
  <tfoot>
   <th></th>
   <th>الإجمالي</th>
   <th style="color:green;"><?php echo number_format($totalIncome, 2); ?></th>
   <th style="color:red;"><?php echo number_format($totalWithdrawal, 2); ?></th>
   <th style="color:blue;"><?php echo number_format($finalBalance, 2); ?></th>
   <th></th>
  </tfoot>
 </table>
 </div>
</div>

<!-- زر الطباعة / تصدير PDF -->
<div class="text-center my-2 cashTreasuryPrintBtn">
 <button type="button" class="btn btn-primary" onclick="window.print()">
  <i class="fa fa-print"></i> طباعة / حفظ PDF
 </button>
</div>

<style>
 @media print {
  body * {
   visibility: hidden;
  }
  .table-responsive-lg, .table-responsive-lg * {
   visibility: visible;
  }
  .cashTreasuryPrintBtn, .cashTreasuryPrintBtn * {
   display: none !important;
  }
  .table-responsive-lg {
   position: absolute;
   left: 0;
   top: 0;
   width: 100%;
  }
  .table-responsive-lg div[style*="overflow"] {
   max-height: none !important;
   overflow: visible !important;
  }
 }
</style>
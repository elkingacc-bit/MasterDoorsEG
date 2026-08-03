<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

// ---------------------------------------------------------------
// 1) تنقية المدخل الأساسي (كان يُستخدم خام في الاستعلام - ثغرة SQLi)
// ---------------------------------------------------------------
$jopRowId = isset($_POST['project']) ? (int)$_POST['project'] : 0;

if ($jopRowId <= 0) {
    echo "<div class='alert alert-danger'>طلب غير صالح: رقم المشروع مفقود.</div>";
    exit;
}

/**
 * دالة مساعدة لتنفيذ استعلام محضّر (Prepared Statement) وإرجاع صف واحد
 * $types مثل "i" لعدد صحيح, "s" لنص ... إلخ
 */
function fetchOne($link, $sql, $types = '', $params = []) {
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        return false;
    }
    if ($types !== '' && count($params) > 0) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);
    return $row;
}

/**
 * دالة مساعدة لتنفيذ استعلام محضّر وإرجاع نتيجة (لعمل loop عليها)
 */
function fetchAll($link, $sql, $types = '', $params = []) {
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        return false;
    }
    if ($types !== '' && count($params) > 0) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// ---------------------------------------------------------------
// 2) جلب بيانات أمر الشراء (Job Id)
// ---------------------------------------------------------------
$jopIdData = fetchOne(
    $link,
    "SELECT `PoNum`,`poVal`,`jobidref` FROM `customerpo` WHERE `poId` = ?",
    "i",
    [$jopRowId]
);

if (!$jopIdData) {
    echo "<div class='alert alert-danger'>لا يوجد أمر شراء بهذا الرقم.</div>";
    exit;
}

$poNum   = $jopIdData['PoNum'];
$jopId   = (int)$jopIdData['jobidref'];
$poValue = (float)$jopIdData['poVal'];

// ---------------------------------------------------------------
// 3) بيانات المشروع (job)
// ---------------------------------------------------------------
$jopData = fetchOne(
    $link,
    "SELECT `projectName`,`customer`,`jobtype` FROM `job` WHERE `jobId` = ?",
    "i",
    [$jopId]
);

if (!$jopData) {
    echo "<div class='alert alert-danger'>لا توجد بيانات مشروع مرتبطة بهذا الأمر.</div>";
    exit;
}

$projectName  = $jopData['projectName'];
$customerCode = $jopData['customer'];
$orderType    = $jopData['jobtype'];

// ---------------------------------------------------------------
// 4) اسم العميل
// ---------------------------------------------------------------
$customerData = fetchOne(
    $link,
    "SELECT `customername` FROM `customers` WHERE `customercode` = ?",
    "s",
    [$customerCode]
);

$customerName = $customerData ? $customerData['customername'] : 'غير معروف';

// تنظيف المخرجات قبل الطباعة (حماية من XSS)
$safeCustomerName = htmlspecialchars($customerName, ENT_QUOTES, 'UTF-8');
$safeProjectName  = htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8');

echo "<input type='text' class='project_Expencess invisible' value='{$safeCustomerName} / {$safeProjectName} Expencess'>";

$poSN = 2;
$projectExpencess = 0.0;
?>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped projectExpen w-100">
  <thead class="bg-primary text-center">
   <th>#</th>
   <th>Income</th>
   <th>Outcome</th>
   <th>Description</th>
   <th>Account</th>
  </thead>
  <tbody>
   <?php
    // ------------------------------------------------------------
    // صف 1: قيمة الأمر
    // ------------------------------------------------------------
    echo "<tr>
     <td>1</td>
     <td>" . number_format($poValue, 2) . "</td>
     <td></td>
     <td>Order Value</td>
     <td>{$safeCustomerName}</td>
    </tr>";

    // ------------------------------------------------------------
    // صف 2: إجمالي التحصيل من العميل
    // ------------------------------------------------------------
    $resProjectCollect = fetchOne(
        $link,
        "SELECT SUM(`income`) as totalCollect FROM `cash_transaction` WHERE `poNum` = ?",
        "i",
        [$jopId]
    );
    $totalCollect = ($resProjectCollect && $resProjectCollect['totalCollect'] !== null)
        ? (float)$resProjectCollect['totalCollect']
        : 0.0;

    echo "<tr>
     <td>2</td>
     <td>" . number_format($totalCollect, 2) . "</td>
     <td></td>
     <td>Customer Collect</td>
     <td>{$safeCustomerName}</td>
    </tr>";

    // ------------------------------------------------------------
    // المدفوعات النقدية لكل حساب (Payment Cash)
    // ------------------------------------------------------------
    $paymentsResult = fetchAll(
        $link,
        "SELECT SUM(`withdrawal`) as payment, `account`
         FROM `cash_transaction`
         WHERE `poNum` = ?
         GROUP BY `account`",
        "i",
        [$jopId]
    );

    if ($paymentsResult) {
        while ($row = mysqli_fetch_assoc($paymentsResult)) {
            $accountData = fetchOne(
                $link,
                "SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = ?",
                "s",
                [$row['account']]
            );

            if ($accountData) {
                $poSN++;
                $payment = (float)$row['payment'];
                $projectExpencess += $payment;
                $safeAccount = htmlspecialchars($accountData['accountName'], ENT_QUOTES, 'UTF-8');

                echo "<tr>
                 <td>{$poSN}</td>
                 <td></td>
                 <td>" . number_format($payment, 2) . "</td>
                 <td>Project Payment</td>
                 <td>{$safeAccount}</td>
                </tr>";
            }
        }
    }

    // ------------------------------------------------------------
    // تصنيع (Purchase Orders) - كان الكود القديم يقرأ صفًا واحدًا فقط
    // رغم أن الاستعلام مجمّع GROUP BY supplierid (باگ). الآن نجمع كل الموردين.
    // ------------------------------------------------------------
    $purchaseResult = fetchAll(
        $link,
        "SELECT SUM(`totalAmout`) as purValue, `supplierid`
         FROM `purchasesorder`
         WHERE `jobref` = ?
         GROUP BY `supplierid`",
        "i",
        [$jopId]
    );

    if ($purchaseResult) {
        $totalPurchases = 0.0;
        while ($row = mysqli_fetch_assoc($purchaseResult)) {
            $totalPurchases += (float)$row['purValue'];
        }
        if ($totalPurchases > 0) {
            $poSN++;
            $projectExpencess += $totalPurchases;
            echo "<tr>
              <td>{$poSN}</td>
              <td></td>
              <td>" . number_format($totalPurchases, 2) . "</td>
              <td>Manufactur</td>
              <td>Purchases</td>
             </tr>";
        }
    }

    // ------------------------------------------------------------
    // تكلفة الهاردوير (Hardware Cost)
    // كان الكود القديم يقرأ أول صف فقط من descripcode لكل item (باگ)
    // الآن نجمع كل الـ descripcode لكل item
    // ------------------------------------------------------------
    $itemOfferResult = fetchAll(
        $link,
        "SELECT `id` FROM `itemoffer` WHERE `jobref` = ?",
        "i",
        [$jopId]
    );

    if ($itemOfferResult && mysqli_num_rows($itemOfferResult) > 0) {
        $poSN++;
        $totalPrice = 0.0;

        while ($itemsData = mysqli_fetch_assoc($itemOfferResult)) {
            $rowId = (int)$itemsData['id'];

            $hwResult = fetchAll(
                $link,
                "SELECT `descripcode`, SUM(`descripquantity`) as num
                 FROM `offerproperties`
                 WHERE `ioidref` = ?
                 GROUP BY `descripcode`",
                "i",
                [$rowId]
            );

            if ($hwResult) {
                while ($itemsHW = mysqli_fetch_assoc($hwResult)) {
                    $desCode = $itemsHW['descripcode'];

                    $resStockCost = fetchOne(
                        $link,
                        "SELECT `amount` FROM `warehouse` WHERE `description` = ?",
                        "s",
                        [$desCode]
                    );

                    if ($resStockCost) {
                        $totalPrice += ($itemsHW['num'] * $resStockCost['amount']);
                    }
                }
            }
        }

        $projectExpencess += $totalPrice;
        echo "<tr>
          <td>{$poSN}</td>
          <td></td>
          <td>" . number_format($totalPrice, 2) . "</td>
          <td>Hardware</td>
          <td></td>
         </tr>";
    }

    // ------------------------------------------------------------
    // الحسابات الختامية - مع الحماية من القسمة على صفر
    // ------------------------------------------------------------
    $subValue = $poValue - $projectExpencess;
    $stutsValue2 = ($poValue != 0) ? ($subValue / $poValue) * 100 : 0;
    $stutsValue = number_format($stutsValue2, 2);

    $subCollect = $totalCollect - $projectExpencess;

    echo "<tr class='bg-info text-white font-weight-bolder text-center'>
     <td>99</td>
     <td>" . number_format($poValue, 2) . "</td>
     <td>" . number_format($projectExpencess, 2) . "</td>
     <td>Net Profit (" . number_format($subCollect, 2) . ")</td>
     <td>Total</td>
    </tr>";

    /**
     * دالة موحّدة لبناء progress bar وتلوينه حسب النسبة.
     * منطق الألوان مصحّح: كلما زادت نسبة الربح كان اللون أفضل
     * (سابقًا كانت حالة <= 0 خضراء وهو معكوس منطقيًا).
     */
    function buildProgressBar($value) {
        if ($value <= 0) {
            $class = 'bg-danger';
        } elseif ($value <= 25) {
            $class = 'bg-warning';
        } elseif ($value <= 50) {
            $class = 'bg-info';
        } else {
            $class = 'bg-success';
        }

        return "<div class='progress'>
          <div class='progress-bar progress-bar-striped {$class}' role='progressbar'
               style='width: " . max(0, min(100, $value)) . "%'
               aria-valuenow='{$value}' aria-valuemin='0' aria-valuemax='100'>
           {$value}%
          </div>
         </div>";
    }

    $valueBar = buildProgressBar((float)$stutsValue);

    $stutsCollect2 = ($poValue != 0) ? ($subCollect / $poValue) * 100 : 0;
    $stutsCollect = number_format($stutsCollect2, 2);
    $collectBar = buildProgressBar((float)$stutsCollect);
   ?>
  </tbody>
  <tfoot>
   <th>project Stuts</th>
   <th>Profit By Order Value</th>
   <th><?php echo $valueBar; ?></th>
   <th>Profit By project Collect</th>
   <th><?php echo $collectBar; ?></th>
  </tfoot>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var titleName = $('.project_Expencess').val();
  var currentdate = new Date();
  var datetime = currentdate.getDate() + "/"
               + (currentdate.getMonth()+1)  + "/"
               + currentdate.getFullYear() + " @ "
               + currentdate.getHours() + ":"
               + currentdate.getMinutes() + ":"
               + currentdate.getSeconds();
  var table = $('.projectExpen').DataTable({
   fixedHeader: false,
   scrollY:'35vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false,
   order:[[0, "asc"]],
   dom: 'Bfrtip',
   buttons:[
    {
     extend: 'excel',
     text: 'Excel',
     extension: '.xlsx',
     title:titleName+datetime,
     filename: function () {
      return titleName
     },
     className: 'btn btn-primary',
     exportOptions:{
      columns: [ 0,1,2,3,4 ]
     },
     footer: false,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:titleName+datetime,
     filename: function () {
      return titleName
     },
     extension: '.pdf',
     className: 'btn btn-info',
     exportOptions:{
      columns: [ 0,1,2,3,4 ]
     },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-primary',
     title:'Master Doors EG |'+titleName+datetime,
     footer: true,
     exportOptions: {
      columns: [ 0,1,2,3,4 ]
     },
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
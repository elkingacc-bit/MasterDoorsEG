<?php
/**
 * getCashDataToEdit.php
 * ------------------------------------------------------------
 * تم إصلاح الأخطاء التالية:
 * 1) ثغرة SQL Injection في كل الاستعلامات (بما فيها rowId القادم من $_POST).
 * 2) نفس مشكلة عدم تصفير $accountName / $empName.
 * 3) عمود إضافي زائد في صف financialTransactions (كان 10 أعمدة بدل 9).
 * 4) خطأ الفاصلة المنقوطة داخل value الخاص بحقل الوصف:
 *      value='...[description];'   ->   value='...[description]'
 * 5) الحقول لم يكن لها name attribute فكانت غير قابلة للإرسال في أي فورم حقيقي.
 * 6) إضافة hidden input لمعرف الصف (cash_transaction_id) وزر "حفظ" فعلي
 *    يرسل البيانات إلى saveCashEdit.php عبر AJAX.
 * 7) htmlspecialchars() لمنع XSS.
 * ------------------------------------------------------------
 */

include_once("../authCheck.php");
date_default_timezone_set("Africa/Cairo");
include_once("../connection.php");

function getAccountName($link, $code)
{
    $name = '';

    $stmt = mysqli_prepare($link, "SELECT accountName FROM accountantcode WHERE accountCode = ?");
    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        mysqli_stmt_close($stmt);
        return $row['accountName'];
    }
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($link, "SELECT customername FROM customers WHERE customercode = ?");
    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        mysqli_stmt_close($stmt);
        return $row['customername'];
    }
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($link, "SELECT suppliername FROM allsuppliers WHERE suppliercode = ?");
    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $name = $row['suppliername'];
    }
    mysqli_stmt_close($stmt);

    return $name;
}

function getEmpName($link, $empCode)
{
    $name = '';

    $stmt = mysqli_prepare($link, "SELECT fullname FROM users WHERE userid = ?");
    mysqli_stmt_bind_param($stmt, "s", $empCode);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        mysqli_stmt_close($stmt);
        return $row['fullname'];
    }
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($link, "SELECT staffname FROM allstaff WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "s", $empCode);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $name = $row['staffname'];
    }
    mysqli_stmt_close($stmt);

    return $name;
}

$tableRowId = isset($_POST['rowId']) ? $_POST['rowId'] : 0;

$stmt = mysqli_prepare($link, "SELECT `cash_transaction_id`,`transactionDate`,`income`,`withdrawal`,`description`,`account`,`empCode`,`tableName`,`tableRowId`,`ftId`
 FROM `cash_transaction` WHERE `cash_transaction_id` = ?");
mysqli_stmt_bind_param($stmt, "i", $tableRowId);
mysqli_stmt_execute($stmt);
$queryDataEdit = mysqli_stmt_get_result($stmt);
$resultDataEdit = mysqli_fetch_assoc($queryDataEdit);
mysqli_stmt_close($stmt);

if (!$resultDataEdit) {
    echo "<p class='text-danger'>لم يتم العثور على البيانات.</p>";
    exit;
}

$searchCode = $resultDataEdit['account'];
$empCode = $resultDataEdit['empCode'];

$accountName = getAccountName($link, $searchCode);
$empName = getEmpName($link, $empCode);
?>
<table class='table table-bordered table-striped w-100'>
  <thead>
   <th>transactionDate</th>
   <th>income/debtor</th>
   <th>withdrawal</th>
   <th>description</th>
   <th>account</th>
   <th>empCode</th>
   <th>tableName</th>
   <th>tableRowId</th>
   <th>ftId</th>
  </thead>
  <tbody>
<?php
echo "<tr>
  <td>" . htmlspecialchars($resultDataEdit['transactionDate']) . "</td>
  <td>" . htmlspecialchars($resultDataEdit['income']) . "</td>
  <td>" . htmlspecialchars($resultDataEdit['withdrawal']) . "</td>
  <td>" . htmlspecialchars($resultDataEdit['description']) . "</td>
  <td>" . htmlspecialchars($accountName) . "</td>
  <td>" . htmlspecialchars($empName) . "</td>
  <td>" . htmlspecialchars($resultDataEdit['tableName']) . "</td>
  <td>" . htmlspecialchars($resultDataEdit['tableRowId']) . "</td>
  <td>" . htmlspecialchars($resultDataEdit['ftId']) . "</td>
 </tr>";

# Financial Transactions المرتبطة (9 أعمدة فقط بدل 10)
$tableRowIdRef = $resultDataEdit['tableRowId'];
$stmtFin = mysqli_prepare($link, "SELECT `financialTransactionsId`, `transactionsYear`, `transactionsMonth`, `transactionNumber`, `transactionsDate`, `debtor`, `creditor`,
 `description`,`transactionCode`,`entryRef`,`tableName`,`tableRowId`
  FROM `financialTransactions`
  WHERE `tableName` = 'Cash' AND `tableRowId` = ?");
mysqli_stmt_bind_param($stmtFin, "s", $tableRowIdRef);
mysqli_stmt_execute($stmtFin);
$queryFinancialData = mysqli_stmt_get_result($stmtFin);
while ($resultFinancialData = mysqli_fetch_assoc($queryFinancialData)) {
    echo "<tr>
   <td>" . htmlspecialchars($resultFinancialData['transactionsDate']) . "</td>
   <td>" . htmlspecialchars($resultFinancialData['debtor']) . "</td>
   <td>" . htmlspecialchars($resultFinancialData['creditor']) . "</td>
   <td>" . htmlspecialchars($resultFinancialData['description']) . "</td>
   <td>" . htmlspecialchars($resultFinancialData['transactionCode']) . "</td>
   <td></td>
   <td>" . htmlspecialchars($resultFinancialData['tableName']) . "</td>
   <td>" . htmlspecialchars($resultFinancialData['financialTransactionsId']) . "</td>
   <td>" . htmlspecialchars($resultFinancialData['entryRef']) . "</td>
  </tr>";
}
mysqli_stmt_close($stmtFin);

$amount = $resultDataEdit['income'];
$isIncome = ($amount == 0) ? 0 : 1; // 0 = withdrawal (مصروف), 1 = income (دخل)
$displayAmount = ($amount == 0) ? $resultDataEdit['withdrawal'] : $resultDataEdit['income'];

echo "<tr>
  <td><input class='form-control' type='date' id='newDate' value='" . htmlspecialchars($resultDataEdit['transactionDate']) . "'></td>";

if ($isIncome == 0) {
    echo "<td></td>
 <td><input class='form-control' type='text' id='newAmount' value='" . htmlspecialchars($displayAmount) . "'></td>";
} else {
    echo "<td><input class='form-control' type='text' id='newAmount' value='" . htmlspecialchars($displayAmount) . "'></td>
 <td></td>";
}

echo "<td><input class='form-control' type='text' id='newDis' value='" . htmlspecialchars($resultDataEdit['description']) . "'></td>

<td>
   <select class='form-control' id='newAccount'>
     <option value='" . htmlspecialchars($searchCode) . "'>" . htmlspecialchars($accountName) . "</option>";

$sqlAccountData = "SELECT `accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 12 AND `accountCode` LIKE '3%'";
$queryAccountlData = mysqli_query($link, $sqlAccountData) or die("ERROR LOA_S:01");
while ($accountData = mysqli_fetch_assoc($queryAccountlData)) {
    echo "<option value='" . htmlspecialchars($accountData['accountCode']) . "'>" . htmlspecialchars($accountData['accountName']) . "</option>";
}

echo "</select>
  </td>

  <td>
     <select class='form-control' id='newEmp'>
     <option value='" . htmlspecialchars($empCode) . "'>" . htmlspecialchars($empName) . "</option>";

$sqlFirstLevelData = "SELECT `userid`,`fullname` FROM `users`";
$queryFirstLevelData = mysqli_query($link, $sqlFirstLevelData) or die("ERROR LOA_S:01");
while ($firstLevel = mysqli_fetch_assoc($queryFirstLevelData)) {
    echo "<option value='" . htmlspecialchars($firstLevel['userid']) . "'>" . htmlspecialchars($firstLevel['fullname']) . "</option>";
}

echo "</select>
 </td>
  <td></td>
  <td></td>
  <td></td>
 </tr>";
?>
  </tbody>
</table>

<!-- بيانات مخفية يحتاجها زر الحفظ -->
<input type="hidden" id="editRowId" value="<?php echo (int)$resultDataEdit['cash_transaction_id']; ?>">
<input type="hidden" id="editIsIncome" value="<?php echo (int)$isIncome; ?>">
<div id="saveMsg" class="mt-2"></div>

<script type="text/javascript">
 // نضيف زر الحفظ داخل الـ modal-footer (خارج هذا المحتوى نفسه حتى لا يُستبدل عند إعادة فتح التعديل)
 (function(){
  $(".saveArea").html(
   "<button type='button' class='btn btn-secondary' data-dismiss='modal'>إلغاء</button>" +
   "<button type='button' class='btn btn-success saveEditBtn'>حفظ التعديلات</button>"
  );

  $(document).off('click', '.saveEditBtn').on('click', '.saveEditBtn', function(){
   var payload = {
    id: $('#editRowId').val(),
    isIncome: $('#editIsIncome').val(),
    transactionDate: $('#newDate').val(),
    amount: $('#newAmount').val(),
    description: $('#newDis').val(),
    account: $('#newAccount').val(),
    empCode: $('#newEmp').val()
   };

   if (!payload.transactionDate || payload.amount === '' || !payload.account || !payload.empCode) {
    $('#saveMsg').html("<span class='text-danger'>من فضلك أكمل كل الحقول.</span>");
    return;
   }

   $.ajax({
    url: "dist/php/Acc/saveCashEdit.php",
    type: "POST",
    data: payload,
    dataType: "json",
    success: function(response){
     if (response.success) {
      $('#saveMsg').html("<span class='text-success'>تم الحفظ بنجاح.</span>");
      setTimeout(function(){
       $('.close').click(); // اغلاق المودال
       // إعادة تحميل جدول الحركات ليعكس التعديل
       $.ajax({
        url: 'dist/php/Acc/admin_cash.php',
        success: function(doneRes){
         $(".editingRes").html($(doneRes).find('.editingRes').html());
         if (typeof initCashTable === 'function') { initCashTable(); }
        }
       });
      }, 700);
     } else {
      $('#saveMsg').html("<span class='text-danger'>" + (response.message || 'حدث خطأ أثناء الحفظ.') + "</span>");
     }
    },
    error: function(){
     $('#saveMsg').html("<span class='text-danger'>تعذر الاتصال بالخادم.</span>");
    }
   });
  });
 })();
</script>
<?php
/**
 * saveCashEdit.php
 * ------------------------------------------------------------
 * صفحة حفظ التعديلات القادمة من نافذة التعديل (getCashDataToEdit.php).
 * تقوم بتحديث:
 *   1) صف واحد في جدول cash_transaction (حسب cash_transaction_id).
 *   2) كل الصفوف المرتبطة في جدول financialTransactions
 *      (WHERE tableName = 'Cash' AND tableRowId = <tableRowId الخاص بالصف>)
 *      بحيث:
 *        - في كل صف: لو debtor كان أصلاً != 0 يتحدث بالقيمة الجديدة، ولو كان صفر يفضل زي ما هو.
 *          نفس المنطق بالظبط لـ creditor. (العمود اللي مش نشط في الصف مبيتلمسش خالص).
 *        - transactionsDate و transactionsYear و transactionsMonth
 *          تتحدث كلها مع بعض حسب التاريخ الجديد.
 *
 * العمليتان تتم داخل Transaction واحدة (commit/rollback) حتى لا يحدث
 * تحديث في جدول وفشل في الآخر فيختل التوازن المحاسبي.
 *
 * المدخلات المتوقعة (POST):
 *  - id            : cash_transaction_id (رقم)
 *  - isIncome      : 1 = دخل (income) / 0 = مصروف (withdrawal)
 *  - transactionDate (YYYY-MM-DD)
 *  - amount
 *  - description
 *  - account       : accountCode
 *  - empCode
 *
 * المخرج: JSON  { success: true|false, message: "..." }
 * ------------------------------------------------------------
 */

header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

function respond($success, $message = '')
{
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// -------- التحقق من المدخلات --------
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$isIncome = isset($_POST['isIncome']) && $_POST['isIncome'] == '1' ? 1 : 0;
$transactionDate = isset($_POST['transactionDate']) ? trim($_POST['transactionDate']) : '';
$amountRaw = isset($_POST['amount']) ? trim($_POST['amount']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$account = isset($_POST['account']) ? trim($_POST['account']) : '';
$empCode = isset($_POST['empCode']) ? trim($_POST['empCode']) : '';

if ($id <= 0) {
    respond(false, 'معرف الحركة غير صالح.');
}

if ($transactionDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $transactionDate)) {
    respond(false, 'تاريخ الحركة غير صالح.');
}

$dateObj = DateTime::createFromFormat('Y-m-d', $transactionDate);
if (!$dateObj) {
    respond(false, 'تاريخ الحركة غير صالح.');
}

if (!is_numeric($amountRaw)) {
    respond(false, 'القيمة المدخلة يجب أن تكون رقماً.');
}
$amount = (float)$amountRaw;
if ($amount < 0) {
    respond(false, 'القيمة يجب ألا تكون سالبة.');
}

if ($description === '') {
    respond(false, 'الوصف مطلوب.');
}

if (!ctype_digit((string)$account)) {
    respond(false, 'كود الحساب غير صالح.');
}

if (!ctype_digit((string)$empCode)) {
    respond(false, 'كود الموظف غير صالح.');
}

// -------- جلب الصف الحالي (للتأكد من وجوده وأخذ tableRowId المرتبط) --------
$stmtCheck = mysqli_prepare($link, "SELECT `tableRowId`, `tableName` FROM `cash_transaction` WHERE `cash_transaction_id` = ?");
mysqli_stmt_bind_param($stmtCheck, "i", $id);
mysqli_stmt_execute($stmtCheck);
$resCheck = mysqli_stmt_get_result($stmtCheck);
$currentRow = mysqli_fetch_assoc($resCheck);
mysqli_stmt_close($stmtCheck);

if (!$currentRow) {
    respond(false, 'الحركة غير موجودة.');
}

$linkedTableRowId = $currentRow['tableRowId'];

// -------- القيم المشتقة --------
$income = $isIncome === 1 ? $amount : 0;
$withdrawal = $isIncome === 1 ? 0 : $amount;
$transactionsYear = $dateObj->format('Y');
$transactionsMonth = $dateObj->format('m');

// -------- تنفيذ التحديثين داخل Transaction واحدة --------
mysqli_begin_transaction($link);
$ok = true;
$errorMessage = '';

// 1) تحديث cash_transaction
$sqlUpdateCash = "UPDATE `cash_transaction`
    SET `transactionDate` = ?, `income` = ?, `withdrawal` = ?, `description` = ?, `account` = ?, `empCode` = ?
    WHERE `cash_transaction_id` = ?";
$stmt = mysqli_prepare($link, $sqlUpdateCash);
if ($stmt) {
    mysqli_stmt_bind_param(
        $stmt,
        "sddsiii",
        $transactionDate,
        $income,
        $withdrawal,
        $description,
        $account,
        $empCode,
        $id
    );
    if (!mysqli_stmt_execute($stmt)) {
        $ok = false;
        $errorMessage = 'فشل تحديث cash_transaction: ' . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
    $ok = false;
    $errorMessage = 'خطأ في إعداد استعلام cash_transaction.';
}

// 2) تحديث كل صفوف financialTransactions المرتبطة (نفس tableName='Cash' و tableRowId)
if ($ok) {
    // debtor يتحدث فقط لو كان أصلاً مش صفر (نفس الكلام لـ creditor)، والعمود الآخر يفضل زي ما هو من غير ما يتلمس
    $sqlUpdateFin = "UPDATE `financialTransactions`
        SET `transactionsDate` = ?,
            `transactionsYear` = ?,
            `transactionsMonth` = ?,
            `debtor` = CASE WHEN `debtor` <> 0 THEN ? ELSE `debtor` END,
            `creditor` = CASE WHEN `creditor` <> 0 THEN ? ELSE `creditor` END
        WHERE `tableName` = 'Cash' AND `tableRowId` = ?";
    $stmtFin = mysqli_prepare($link, $sqlUpdateFin);
    if ($stmtFin) {
        mysqli_stmt_bind_param(
            $stmtFin,
            "sssdds",
            $transactionDate,
            $transactionsYear,
            $transactionsMonth,
            $amount,
            $amount,
            $linkedTableRowId
        );
        if (!mysqli_stmt_execute($stmtFin)) {
            $ok = false;
            $errorMessage = 'فشل تحديث financialTransactions: ' . mysqli_stmt_error($stmtFin);
        }
        mysqli_stmt_close($stmtFin);
    } else {
        $ok = false;
        $errorMessage = 'خطأ في إعداد استعلام financialTransactions.';
    }
}

if ($ok) {
    mysqli_commit($link);
    respond(true, 'تم تحديث بيانات الحركة والقيود المرتبطة بنجاح.');
} else {
    mysqli_rollback($link);
    respond(false, $errorMessage ?: 'حدث خطأ أثناء الحفظ، تم التراجع عن كل التعديلات.');
}

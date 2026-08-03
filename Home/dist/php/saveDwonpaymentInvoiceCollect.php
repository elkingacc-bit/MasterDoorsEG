<?php
/**
 * saveDwonpaymentInvoiceCollect.php  (نسخة مصححة)
 *
 * الإصلاحات المطبّقة مقارنة بالنسخة الأصلية:
 *  1) استخدام prepared statements لكل استعلام (منع SQL Injection).
 *  2) التحقق من صحة/وجود مدخلات $_POST قبل استخدامها.
 *  3) التحقق من وجود الجلسة (session) بدل قمع الخطأ بـ @.
 *  4) استخدام mysqli_insert_id() بدل استعلام "آخر صف" (يمنع قراءة معرف خاطئ
 *     عند التزامن).
 *  5) لفّ كل العمليات في transaction واحدة (begin/commit/rollback) لضمان
 *     تناسق البيانات المالية: إما أن تُنفذ كل العمليات معاً أو لا شيء.
 *  6) توليد transactionNumber عبر دالة آمنة من الـ Race Condition
 *     (SELECT ... FOR UPDATE) بدل SELECT MAX ثم +1 بشكل منفصل.
 *  7) التحقق من نتائج mysqli_fetch_assoc قبل استخدام المصفوفة الناتجة.
 */

session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
include_once("common_financial_functions.php");

header('Content-Type: application/json');

// ---------- 1) التحقق من الجلسة ----------
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "الجلسة غير صالحة، الرجاء تسجيل الدخول من جديد"]);
    exit;
}
$recipient = $_SESSION['id'];

// ---------- 2) التحقق من مدخلات POST ----------
$jopIdA    = filter_input(INPUT_POST, 'invNum', FILTER_VALIDATE_INT);
$cashCode  = $_POST['cashType']  ?? null;
$dateInvoice = $_POST['extractDate'] ?? null;
$supTotal  = filter_input(INPUT_POST, 'amountVal', FILTER_VALIDATE_FLOAT);

if ($jopIdA === false || $jopIdA === null
    || empty($cashCode)
    || empty($dateInvoice)
    || $supTotal === false || $supTotal === null
    || !strtotime($dateInvoice)
) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "بيانات غير صحيحة أو ناقصة"]);
    exit;
}

$transactionsYear  = date('Y', strtotime($dateInvoice));
$transactionsMonth = date('m', strtotime($dateInvoice));
$financialRef = "Collect Dwonpaymen";

// ---------- 3) بدء Transaction ----------
mysqli_begin_transaction($link);

try {
    $poData = getPoData($link, $jopIdA);
    if (!$poData) {
        throw new Exception("لم يتم العثور على أمر الشراء (PO) المرتبط بهذا العمل");
    }
    $customer     = $poData['custCode'];
    $PoNumber     = $poData['PoNum'];
    $validAmount  = $poData['dwonpay'];

    $project = getProjectName($link, $jopIdA);
    if ($project === null) {
        throw new Exception("لم يتم العثور على اسم المشروع");
    }

    $nextNumber = getNextTransactionNumber($link, $transactionsYear, $transactionsMonth);

    // ---------- إضافة حركة الكاش ----------
    $description = "Collect Dwonpayment From ($PoNumber - $project )";
    $stmt = mysqli_prepare(
        $link,
        "INSERT INTO `cash_transaction`
            (`transactionDate`,`income`,`withdrawal`,`description`,`amountRef`,`statmentRef`,
             `account`,`poNum`,`invNumber`,`empCode`)
         VALUES (?,?,0,?,?,?,?,?,?,?)"
    );
    if (!$stmt) {
        throw new Exception("فشل تجهيز استعلام حركة الكاش");
    }
    mysqli_stmt_bind_param(
        $stmt,
        "sdsdsssss",
        $dateInvoice,
        $supTotal,
        $description,
        $validAmount,
        $cashCode,
        $customer,
        $jopIdA,
        $jopIdA,
        $recipient
    );
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("فشل حفظ حركة الكاش");
    }
    mysqli_stmt_close($stmt);

    $tableRow  = mysqli_insert_id($link);
    $tableName = "Cash";

    // ---------- تحديث حركة الكاش بالمرجع ----------
    $stmtUpd = mysqli_prepare(
        $link,
        "UPDATE `cash_transaction` SET `tableName` = ?, `tableRowId` = ?, `ftId` = ?
         WHERE `cash_transaction_id` = ?"
    );
    mysqli_stmt_bind_param($stmtUpd, "siii", $tableName, $tableRow, $nextNumber, $tableRow);
    if (!mysqli_stmt_execute($stmtUpd)) {
        throw new Exception("فشل تحديث حركة الكاش");
    }
    mysqli_stmt_close($stmtUpd);

    // ---------- إضافة القيود المالية (مدين/دائن) ----------
    $ok = insertFinancialTransactionPair(
        $link,
        $transactionsYear,
        $transactionsMonth,
        $nextNumber,
        $dateInvoice,
        /* debtorCode   */ $cashCode,
        /* creditorCode */ $customer,
        /* descDebtor   */ "$jopIdA Collect Dwonpayment From ( $project )",
        /* descCreditor */ "Collect Dwonpayment From ( $project )",
        $supTotal,
        $financialRef,
        $tableName,
        $tableRow
    );

    if (!$ok) {
        throw new Exception("فشل حفظ القيود المالية");
    }

    // متغير $action و $recipient متاحان هنا لملف اللوج القديم إن احتاج لهما
    $action = " Collect Dwonpaymen From ($project )";
    if (file_exists(__DIR__ . "/aduLog.php")) {
        include __DIR__ . "/aduLog.php";
    }

    mysqli_commit($link);
    echo json_encode(["status" => "success", "message" => "تم حفظ التحصيل بنجاح"]);
} catch (Exception $e) {
    mysqli_rollback($link);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
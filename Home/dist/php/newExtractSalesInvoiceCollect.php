<?php
/**
 * newExtractSalesInvoiceCollect.php  (نسخة مصححة)
 *
 * نفس الإصلاحات المطبّقة على saveDwonpaymentInvoiceCollect.php:
 * prepared statements، تحقق من المدخلات، transaction واحدة تلف كل الحلقة
 * (بحيث لو فشل صف واحد يتم التراجع عن كل شيء بدل بيانات منتصف-منفذة)،
 * واستخدام mysqli_insert_id() بدل استعلامات "آخر صف".
 *
 * ملاحظة عن count(itemquantity) في الاستعلام الأصلي:
 * لم أُغيّر هذا المنطق لأنني غير متأكد من القصد التجاري (هل يجب أن يكون
 * عدد صفوف التسليم أم مجموع الكميات؟). تركته كما هو مع تعليق توضيحي أدناه
 * ليتم التحقق منه من قِبل من يعرف منطق العمل الفعلي، تفادياً لتغيير رقم
 * مالي/كمية دون تأكيد.
 */

session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
include_once("common_financial_functions.php");

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "الجلسة غير صالحة، الرجاء تسجيل الدخول من جديد"]);
    exit;
}
$recipient = $_SESSION['id'];

$jopIdA      = filter_input(INPUT_POST, 'invNum', FILTER_VALIDATE_INT);
$cashCode    = $_POST['cashType'] ?? null;
$supTotal    = filter_input(INPUT_POST, 'amountVal', FILTER_VALIDATE_FLOAT);
$dateInvoice = $_POST['extractDate'] ?? null;

if ($jopIdA === false || $jopIdA === null
    || empty($cashCode)
    || $supTotal === false || $supTotal === null
    || empty($dateInvoice)
    || !strtotime($dateInvoice)
) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "بيانات غير صحيحة أو ناقصة"]);
    exit;
}

$transactionsYear  = date('Y', strtotime($dateInvoice));
$transactionsMonth = date('m', strtotime($dateInvoice));
$financialRef = "Collect Extract";

mysqli_begin_transaction($link);

try {
    $project = getProjectName($link, $jopIdA);
    if ($project === null) {
        throw new Exception("لم يتم العثور على اسم المشروع");
    }

    $poData = getPoData($link, $jopIdA);
    if (!$poData) {
        throw new Exception("لم يتم العثور على أمر الشراء (PO) المرتبط بهذا العمل");
    }
    $customer = $poData['custCode'];
    $PoNumber = $poData['PoNum'];

    // ---------- جلب بنود التسليم غير المحصّلة بعد ----------
    // ملاحظة: count(`itemquantity`) في الأصل قد تعني "عدد صفوف التسليم"
    // وليس "مجموع الكمية". تحقق من هذا مع منطق العمل الفعلي قبل الاعتماد
    // على هذا الرقم في أي تقرير مالي. إن كان المطلوب هو مجموع الكمية
    // استبدل COUNT بـ SUM أدناه.
    $stmtItems = mysqli_prepare(
        $link,
        "SELECT `itemRowId`, count(`itemquantity`) as itemquantity, `deliverdate`,
                sum(`deliveramount`) as deliveramount, `jobRowId`
         FROM `custorderdeliver`
         WHERE `jobRowId` = ? AND `ref` = '0'
         GROUP BY `jobRowId`"
    );
    mysqli_stmt_bind_param($stmtItems, "i", $jopIdA);
    mysqli_stmt_execute($stmtItems);
    $itemsResult = mysqli_stmt_get_result($stmtItems);

    $rowsProcessed = 0;

    while ($salesInoiceData = mysqli_fetch_assoc($itemsResult)) {
        $rowsProcessed++;
        $jopId    = $salesInoiceData['jobRowId'];
        $itemsId  = $salesInoiceData['itemRowId'];
        $qyant    = $salesInoiceData['itemquantity'];
        $validAmount = $salesInoiceData['deliveramount'];

        $nextNumber = getNextTransactionNumber($link, $transactionsYear, $transactionsMonth);

        // ---------- إضافة مسودة فاتورة البيع ----------
        $stmtDraft = mysqli_prepare(
            $link,
            "INSERT INTO `salesInvoiceDraft`
                (`jopRef`,`customerCode`,`salesInvoiceSupTotal`,`salesInvoiceDate`,`itemsQya`,`itemRowId`)
             VALUES (?,?,?,?,?,?)"
        );
        mysqli_stmt_bind_param($stmtDraft, "isdsii", $jopId, $customer, $supTotal, $dateInvoice, $qyant, $itemsId);
        if (!mysqli_stmt_execute($stmtDraft)) {
            throw new Exception("فشل حفظ مسودة الفاتورة");
        }
        mysqli_stmt_close($stmtDraft);

        // ---------- تعليم صفوف التسليم كمحصَّلة ----------
        $stmtRef = mysqli_prepare(
            $link,
            "UPDATE `custorderdeliver` SET `ref` = 1 WHERE `jobRowId` = ? AND `ref` = '0'"
        );
        mysqli_stmt_bind_param($stmtRef, "i", $jopIdA);
        if (!mysqli_stmt_execute($stmtRef)) {
            throw new Exception("فشل تحديث حالة صفوف التسليم");
        }
        mysqli_stmt_close($stmtRef);

        // ---------- إضافة حركة الكاش ----------
        $description = "Collect From ( $PoNumber - $project )";
        $stmtCash = mysqli_prepare(
            $link,
            "INSERT INTO `cash_transaction`
                (`transactionDate`,`income`,`withdrawal`,`description`,`amountRef`,`statmentRef`,
                 `account`,`poNum`,`empCode`)
             VALUES (?,?,0,?,?,?,?,?,?)"
        );
        mysqli_stmt_bind_param(
            $stmtCash,
            "sdsdsssss",
            $dateInvoice,
            $supTotal,
            $description,
            $validAmount,
            $cashCode,
            $customer,
            $jopIdA,
            $recipient
        );
        // ملاحظة: bind_param أعلاه به 9 قيم مقابل 9 علامات استفهام؛ نوع
        // السلسلة "sdsdsssss" يطابق الترتيب: تاريخ، دخل، وصف، مبلغ مرجعي،
        // كود الخزينة، كود العميل، رقم العمل، كود الموظف.
        if (!mysqli_stmt_execute($stmtCash)) {
            throw new Exception("فشل حفظ حركة الكاش");
        }
        mysqli_stmt_close($stmtCash);

        $tableRow   = mysqli_insert_id($link);
        $tableName  = "salesInvoiceDraft";

        // ---------- الحصول على معرف مسودة الفاتورة الأخيرة لهذا الصف ----------
        $stmtDraftId = mysqli_prepare(
            $link,
            "SELECT `salesInvoiceDraftId` FROM `salesInvoiceDraft`
             WHERE `jopRef` = ? AND `itemRowId` = ?
             ORDER BY `salesInvoiceDraftId` DESC LIMIT 1"
        );
        mysqli_stmt_bind_param($stmtDraftId, "ii", $jopId, $itemsId);
        mysqli_stmt_execute($stmtDraftId);
        $draftIdResult = mysqli_stmt_get_result($stmtDraftId);
        $draftIdRow = mysqli_fetch_assoc($draftIdResult);
        $custodyRow = $draftIdRow ? $draftIdRow['salesInvoiceDraftId'] : null;
        mysqli_stmt_close($stmtDraftId);

        // ---------- تحديث حركة الكاش بالمرجع ----------
        $stmtUpd = mysqli_prepare(
            $link,
            "UPDATE `cash_transaction` SET `tableName` = ?, `tableRowId` = ?, `ftId` = ?
             WHERE `cash_transaction_id` = ?"
        );
        mysqli_stmt_bind_param($stmtUpd, "siii", $tableName, $custodyRow, $nextNumber, $tableRow);
        if (!mysqli_stmt_execute($stmtUpd)) {
            throw new Exception("فشل تحديث حركة الكاش بالمرجع");
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
            /* descDebtor   */ "$jopIdA Invoice Collect  From ($project )",
            /* descCreditor */ "Collect Amount From ($project )",
            $supTotal,
            $financialRef,
            $tableName,
            $tableRow
        );

        if (!$ok) {
            throw new Exception("فشل حفظ القيود المالية");
        }

        $action = " Collect Extract From ($project)";
        if (file_exists(__DIR__ . "/aduLog.php")) {
            include __DIR__ . "/aduLog.php";
        }
    }
    mysqli_stmt_close($stmtItems);

    if ($rowsProcessed === 0) {
        throw new Exception("لا توجد بنود تسليم غير محصّلة لهذا العمل");
    }

    mysqli_commit($link);
    echo json_encode(["status" => "success", "message" => "تم حفظ التحصيل بنجاح"]);
} catch (Exception $e) {
    mysqli_rollback($link);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
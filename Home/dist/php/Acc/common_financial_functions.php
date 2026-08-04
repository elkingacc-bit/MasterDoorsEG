<?php
/**
 * common_financial_functions.php
 *
 * دوال مشتركة تستخدمها ملفات التحصيل (Dwonpayment / Sales Invoice Collect)
 * تم فصلها لتفادي تكرار الكود، ولإصلاح مشكلة الـ Race Condition عند توليد
 * رقم القيد المالي التالي (transactionNumber).
 *
 * ملاحظة هامة: يجب أن يكون هذا الملف مضمّناً (include) بعد فتح الاتصال ($link)
 * وبعد بدء transaction بواسطة mysqli_begin_transaction($link).
 */

/**
 * جلب اسم المشروع الخاص بالـ jobId عبر prepared statement.
 * يرجع null إذا لم يوجد الصف بدلاً من إصدار PHP Warning لاحقاً.
 */
function getProjectName($link, $jobId)
{
    $stmt = mysqli_prepare($link, "SELECT `projectName` FROM `job` WHERE `jobId` = ?");
    if (!$stmt) {
        return null;
    }
    mysqli_stmt_bind_param($stmt, "i", $jobId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row ? $row['projectName'] : null;
}

/**
 * جلب بيانات أمر الشراء (customerpo) الخاصة بالـ jobId عبر prepared statement.
 */
function getPoData($link, $jobId)
{
    $stmt = mysqli_prepare(
        $link,
        "SELECT `PoNum`,`poDate`,`custCode`,`poVal`,`dwonpay` FROM `customerpo` WHERE `jobidref` = ?"
    );
    if (!$stmt) {
        return null;
    }
    mysqli_stmt_bind_param($stmt, "i", $jobId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row;
}

/**
 * توليد رقم القيد المالي التالي (transactionNumber) لسنة/شهر معينين
 * بطريقة آمنة من الـ Race Condition.
 *
 * الحل المعتمد هنا:
 *   SELECT ... FOR UPDATE داخل transaction تحجز الصف (أو تمنع القراءة
 *   المتزامنة لنفس الشهر/السنة) حتى يتم الـ COMMIT، فلا يمكن لطلبين
 *   متزامنين الحصول على نفس الرقم التالي.
 *
 * ملاحظة: هذا حل عملي سريع بدون تعديل بنية الجدول. الحل الأمثل على المدى
 * الطويل هو إنشاء جدول عدّادات (counters) منفصل بحقل AUTO_INCREMENT لكل
 * سنة/شهر، لكنه يتطلب تعديل السكيمة وهو خارج نطاق هذا الإصلاح السريع.
 */
function getNextTransactionNumber($link, $year, $month)
{
    $stmt = mysqli_prepare(
        $link,
        "SELECT `transactionNumber` FROM `financialTransactions`
         WHERE `transactionsYear` = ? AND `transactionsMonth` = ?
         ORDER BY `transactionNumber` DESC LIMIT 1 FOR UPDATE"
    );
    if (!$stmt) {
        return 1;
    }
    mysqli_stmt_bind_param($stmt, "ss", $year, $month);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row && isset($row['transactionNumber'])) {
        return ((int) $row['transactionNumber']) + 1;
    }
    return 1;
}

/**
 * إدراج زوج القيود المدين/الدائن في financialTransactions عبر prepared statements.
 */
function insertFinancialTransactionPair(
    $link,
    $year,
    $month,
    $number,
    $date,
    $debtorCode,
    $creditorCode,
    $descriptionDebtor,
    $descriptionCreditor,
    $amount,
    $entryRef,
    $tableName,
    $tableRowId
) {
    $sql = "INSERT INTO `financialTransactions`
            (`transactionsYear`,`transactionsMonth`,`transactionNumber`,`transactionsDate`,
             `debtor`,`creditor`,`description`,`transactionCode`,`entryRef`,`tableName`,`tableRowId`)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        return false;
    }

    // -- القيد الأول (دائن على الطرف المقابل) --
    $zero = '0';
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssss",
        $year,
        $month,
        $number,
        $date,
        $zero,
        $amount,
        $descriptionDebtor,
        $creditorCode,
        $entryRef,
        $tableName,
        $tableRowId
    );
    $ok1 = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!$ok1) {
        return false;
    }

    // -- القيد الثاني (مدين) --
    $stmt2 = mysqli_prepare($link, $sql);
    if (!$stmt2) {
        return false;
    }
    mysqli_stmt_bind_param(
        $stmt2,
        "sssssssssss",
        $year,
        $month,
        $number,
        $date,
        $amount,
        $zero,
        $descriptionCreditor,
        $debtorCode,
        $entryRef,
        $tableName,
        $tableRowId
    );
    $ok2 = mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    return $ok2;
}
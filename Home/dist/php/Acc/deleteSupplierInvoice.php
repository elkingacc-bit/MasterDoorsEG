<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 include_once("supplierInvoiceDeleteHelpers.php");
 header('Content-Type: application/json');

 $invoiceId = isset($_POST['invoiceId']) ? (int)$_POST['invoiceId'] : 0;
 if ($invoiceId <= 0) {
  echo json_encode(['success' => false, 'message' => 'Invalid Request']);
  exit;
 }

 $stmt = mysqli_prepare($link, "SELECT `suppliersInvoiceId`,`suppliersInvoiceNumber`,`supplierOrderNum`,`suppliersInvoiceTotal`,`paidAmount`,`paiedStuts`
  FROM `supplierInvoice` WHERE `suppliersInvoiceId` = ?");
 mysqli_stmt_bind_param($stmt, "i", $invoiceId);
 mysqli_stmt_execute($stmt);
 $invoice = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
 mysqli_stmt_close($stmt);

 if (!$invoice) {
  echo json_encode(['success' => false, 'message' => 'Invoice Not Found']);
  exit;
 }

 $invoiceNumber = $invoice['suppliersInvoiceNumber'];
 $supplierOrderNum = $invoice['supplierOrderNum'];
 $paidAmount = (float)$invoice['paidAmount'];
 $paiedStuts = $invoice['paiedStuts'];

 // Never trust client-supplied impact data - recompute from scratch.
 $traceability = getPaymentTraceability($link, $invoiceId, $paidAmount);
 $warehouseCount = getWarehouseImpact($link, $invoiceNumber);
 $orderRevert = getOrderRevertEligibility($link, $supplierOrderNum, $paiedStuts);

 mysqli_begin_transaction($link);
 try {
  // Step 3 - only reverse the payment side when the FULL paidAmount is traceable to
  // withdrawals made specifically against this invoice. Otherwise the payment came
  // (at least partly) through the generic FIFO supplier-payment path, which can be
  // split across several invoices and cannot be safely unwound per-invoice - leave
  // those cash/financial rows untouched, that money genuinely left the business.
  if ($paidAmount > 0 && $traceability['fullyTraceable']) {
   foreach ($traceability['rows'] as $ctRow) {
    $ctId = (int)$ctRow['cash_transaction_id'];
    $amt = (float)$ctRow['withdrawal'];

    $stmt = mysqli_prepare($link, "DELETE FROM `financialTransactions`
     WHERE `tableName` = 'supplierInvoice' AND `tableRowId` = ? AND `entryRef` = 'Withdrawal To Supplier'
       AND (`debtor` = ? OR `creditor` = ?)");
    mysqli_stmt_bind_param($stmt, "idd", $ctId, $amt, $amt);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($link, "DELETE FROM `cash_transaction` WHERE `cash_transaction_id` = ?");
    mysqli_stmt_bind_param($stmt, "i", $ctId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
   }
  }

  // Step 4 - this invoice's own creation-side financialTransactions. entryRef is the
  // safety net against the tableRowId-namespace collision (tableRowId can otherwise
  // mean either an invoice id or a cash_transaction id under tableName='supplierInvoice').
  $stmt = mysqli_prepare($link, "DELETE FROM `financialTransactions`
   WHERE `tableName` = 'supplierInvoice' AND `tableRowId` = ?
     AND `entryRef` IN ('New Suppliers Invoice','Withdrawal Stock Suppliers Invoice')");
  mysqli_stmt_bind_param($stmt, "i", $invoiceId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  // Step 5 - line items
  $stmt = mysqli_prepare($link, "DELETE FROM `supplierInvoiceData` WHERE `supplierInvoiceNumber` = ?");
  mysqli_stmt_bind_param($stmt, "i", $invoiceId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  // Step 6 - revert order items only when eligible (sole invoice on that order)
  if ($orderRevert['eligible']) {
   $soId = $orderRevert['soId'];
   $stmt = mysqli_prepare($link, "UPDATE `supporderitems` SET `status` = 1 WHERE `SOIdRef` = ?");
   mysqli_stmt_bind_param($stmt, "i", $soId);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_close($stmt);
  }

  // Step 7 - the invoice itself
  $stmt = mysqli_prepare($link, "DELETE FROM `supplierInvoice` WHERE `suppliersInvoiceId` = ?");
  mysqli_stmt_bind_param($stmt, "i", $invoiceId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  mysqli_commit($link);

  $action = "Delete Supplier Invoice $invoiceNumber (Id $invoiceId), Amount=" . $invoice['suppliersInvoiceTotal'] . ", Paid=$paidAmount";
  $logRef = 122;
  include_once("../aduLog.php");

  echo json_encode([
   'success' => true,
   'invoiceId' => $invoiceId,
   'invoiceNumber' => $invoiceNumber,
   'paidAmount' => $paidAmount,
   'paymentReconciled' => $traceability['fullyTraceable'],
   'unreconciledAmount' => $traceability['unreconciledAmount'],
   'warehouseRowsAffected' => $warehouseCount,
   'orderStatusReverted' => $orderRevert['eligible'],
   'orderStatusNote' => $orderRevert['note'],
  ]);
 }
 catch (Throwable $e) {
  mysqli_rollback($link);
  echo json_encode(['success' => false, 'message' => 'Delete Failed - No Changes Were Made']);
 }
?>

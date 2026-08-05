<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 include_once("salesInvoiceDeleteHelpers.php");
 header('Content-Type: application/json');

 $invoiceId = isset($_POST['invoiceId']) ? (int)$_POST['invoiceId'] : 0;
 if ($invoiceId <= 0) {
  echo json_encode(['success' => false, 'message' => 'Invalid Request']);
  exit;
 }

 $stmt = mysqli_prepare($link, "SELECT `salesInvoiceId`,`salesInvoiceNumber`,`jopRef`,`customerCode`,`totalInvoice`,`invoiceCollectAmount`
  FROM `salesInvoice` WHERE `salesInvoiceId` = ?");
 mysqli_stmt_bind_param($stmt, "i", $invoiceId);
 mysqli_stmt_execute($stmt);
 $invoice = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
 mysqli_stmt_close($stmt);

 if (!$invoice) {
  echo json_encode(['success' => false, 'message' => 'Invoice Not Found']);
  exit;
 }

 $invoiceNumber = $invoice['salesInvoiceNumber'];
 $jopRef = (int)$invoice['jopRef'];
 $customerCode = (int)$invoice['customerCode'];
 $collectAmount = (float)$invoice['invoiceCollectAmount'];

 // Never trust client-supplied impact data - recompute from scratch.
 $traceability = getPaymentTraceability($link, $invoiceId, $collectAmount);
 $orderRevert = getOrderRevertEligibility($link, $jopRef);

 mysqli_begin_transaction($link);
 try {
  // Step 3 - only reverse the collection side when the FULL invoiceCollectAmount is
  // traceable to collections made specifically against this invoice (cash collect +
  // WHT). Otherwise some/all of it came through the generic FIFO customer-collection
  // path, which can be split across several invoices and cannot be safely unwound
  // per-invoice - leave those cash/financial rows untouched.
  if ($traceability['fullyTraceable']) {
   foreach ($traceability['cashRows'] as $ctRow) {
    $ctId = (int)$ctRow['cash_transaction_id'];
    $amt = (float)$ctRow['income'];

    // Money collected before the invoice existed (extract/down-payment) gets its
    // cash_transaction row retagged to this invoice at creation time (saveNewSalesInvoice.php),
    // but its paired financialTransactions rows are left as originally tagged - confirmed
    // in real data as tableName='Cash', entryRef='Collect Dwonpaymen', tableRowId=<cash_transaction_id>
    // (same key as the normal tableName='salesInvoice'/'Collect Invoice' case) - so this
    // must match both flavors, not just the post-invoice 'Collect Invoice' one.
    $stmt = mysqli_prepare($link, "DELETE FROM `financialTransactions`
     WHERE `tableRowId` = ? AND `entryRef` IN ('Collect Invoice','Collect Dwonpaymen')
       AND (`debtor` = ? OR `creditor` = ?)");
    mysqli_stmt_bind_param($stmt, "idd", $ctId, $amt, $amt);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($link, "DELETE FROM `cash_transaction` WHERE `cash_transaction_id` = ?");
    mysqli_stmt_bind_param($stmt, "i", $ctId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
   }

   $stmt = mysqli_prepare($link, "DELETE FROM `financialTransactions`
    WHERE `tableName` = 'salesInvoice' AND `tableRowId` = ? AND `entryRef` = 'Add WHT To Invoice'");
   mysqli_stmt_bind_param($stmt, "i", $invoiceId);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_close($stmt);
  }

  // Step 4 - this invoice's own creation-side financialTransactions.
  $stmt = mysqli_prepare($link, "DELETE FROM `financialTransactions`
   WHERE `tableName` = 'salesInvoice' AND `tableRowId` = ? AND `entryRef` = 'Sales Invoice'");
  mysqli_stmt_bind_param($stmt, "i", $invoiceId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  // Step 5 - line items
  $stmt = mysqli_prepare($link, "DELETE FROM `salesInvoiceData` WHERE `salesInvoiceId` = ?");
  mysqli_stmt_bind_param($stmt, "i", $invoiceId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  // Step 6 - job goes back to "not yet invoiced" (confirmed strict 1:1, always safe)
  $stmt = mysqli_prepare($link, "UPDATE `job` SET `invoice` = 'No' WHERE `jobId` = ?");
  mysqli_stmt_bind_param($stmt, "i", $jopRef);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  // Step 7 - un-consume any pre-invoice draft collections tied to this job/customer
  $stmt = mysqli_prepare($link, "UPDATE `salesInvoiceDraft` SET `ref` = 1
   WHERE `jopRef` = ? AND `customerCode` = ? AND `ref` = 2");
  mysqli_stmt_bind_param($stmt, "ii", $jopRef, $customerCode);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  // Step 8 - the invoice itself
  $stmt = mysqli_prepare($link, "DELETE FROM `salesInvoice` WHERE `salesInvoiceId` = ?");
  mysqli_stmt_bind_param($stmt, "i", $invoiceId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  mysqli_commit($link);

  $action = "Delete Sales Invoice $invoiceNumber (Id $invoiceId), Total=" . $invoice['totalInvoice'] . ", Collected=$collectAmount";
  $logRef = 123;
  include_once("../aduLog.php");

  echo json_encode([
   'success' => true,
   'invoiceId' => $invoiceId,
   'invoiceNumber' => $invoiceNumber,
   'collectAmount' => $collectAmount,
   'collectionReconciled' => $traceability['fullyTraceable'],
   'unreconciledAmount' => $traceability['unreconciledAmount'],
   'orderStatusReverted' => true,
  ]);
 }
 catch (Throwable $e) {
  mysqli_rollback($link);
  echo json_encode(['success' => false, 'message' => 'Delete Failed - No Changes Were Made']);
 }
?>

<?php
 // Shared by getSalesInvoiceDeleteImpact.php (preview) and deleteSalesInvoice.php (execute)
 // so the two can never disagree.

 // Collected money can reach an invoice via 3 paths:
 //  A) saveNewCollectSalesInvoice.php - cash_transaction (income) tagged directly
 //     tableName='salesInvoice'/tableRowId=<invoiceId>, paired financialTransactions
 //     tagged tableRowId=<cash_transaction_id> (entryRef='Collect Invoice').
 //  B) saveNewHoldingTax.php - no cash, just financialTransactions tagged directly
 //     tableName='salesInvoice'/tableRowId=<invoiceId> (entryRef='Add WHT To Invoice').
 //  C) saveCustomerCollecdData.php - a lump FIFO collection across a customer's won
 //     jobs, tagged tableName='CustomerCollect'/tableRowId=<customerId> - no link
 //     back to a specific invoice at all.
 // Only A and B are traceable back to this exact invoice.
 function getPaymentTraceability($link, $invoiceId, $collectAmount) {
  $invoiceId = (int)$invoiceId;
  $collectAmount = (float)$collectAmount;

  $cashRows = [];
  $cashSum = 0.0;
  $stmt = mysqli_prepare($link, "SELECT `cash_transaction_id`, `income` FROM `cash_transaction`
   WHERE `income` > 0 AND `tableName` = 'salesInvoice' AND `tableRowId` = ?");
  mysqli_stmt_bind_param($stmt, "i", $invoiceId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while ($row = mysqli_fetch_assoc($result)) {
   $cashRows[] = $row;
   $cashSum += (float)$row['income'];
  }
  mysqli_stmt_close($stmt);

  $stmt = mysqli_prepare($link, "SELECT COALESCE(SUM(`creditor`),0) AS whtSum FROM `financialTransactions`
   WHERE `tableName` = 'salesInvoice' AND `tableRowId` = ? AND `entryRef` = 'Add WHT To Invoice'");
  mysqli_stmt_bind_param($stmt, "i", $invoiceId);
  mysqli_stmt_execute($stmt);
  $whtRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  $whtSum = (float)$whtRow['whtSum'];

  $traceableSum = $cashSum + $whtSum;
  $fullyTraceable = (abs($traceableSum - $collectAmount) < 0.01);

  return [
   'fullyTraceable' => $fullyTraceable,
   'cashRows' => $cashRows,
   'whtSum' => $whtSum,
   'traceableSum' => $traceableSum,
   'unreconciledAmount' => $fullyTraceable ? 0.0 : round($collectAmount - $traceableSum, 2),
  ];
 }

 // No "shared order" ambiguity on the sales side (confirmed strict 1:1 salesInvoice<->job) -
 // this just carries the jopRef through for the job.invoice / salesInvoiceDraft.ref reverts,
 // named for symmetry with the supplier-side helper rather than being a real eligibility check.
 function getOrderRevertEligibility($link, $jopRef) {
  return ['eligible' => true, 'jopRef' => (int)$jopRef, 'note' => 'Job will be marked as not yet invoiced.'];
 }
?>

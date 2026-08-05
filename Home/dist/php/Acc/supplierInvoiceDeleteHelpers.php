<?php
 // Shared by getSupplierInvoiceDeleteImpact.php (preview) and deleteSupplierInvoice.php (execute)
 // so the two can never disagree on what is/isn't safe to reverse.

 function getPaymentTraceability($link, $invoiceId, $paidAmount) {
  $invoiceId = (int)$invoiceId;
  $paidAmount = (float)$paidAmount;
  $rows = [];
  $sum = 0.0;
  if ($paidAmount > 0) {
   // Mirrors suuplierWithdrawalDetils.php's own query (cash_transaction.poNum = invoice id
   // for withdrawals made against a specific invoice), plus the tableName/tableRowId tag
   // saveNewWithdrawToSupplier.php also stamps on the same row as a second confirmation.
   $stmt = mysqli_prepare($link, "SELECT `cash_transaction_id`, `withdrawal` FROM `cash_transaction`
    WHERE `withdrawal` > 0 AND `poNum` = ? AND `tableName` = 'supplierInvoice' AND `tableRowId` = ?");
   mysqli_stmt_bind_param($stmt, "ii", $invoiceId, $invoiceId);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);
   while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
    $sum += (float)$row['withdrawal'];
   }
   mysqli_stmt_close($stmt);
  }
  $fullyTraceable = (abs($sum - $paidAmount) < 0.01);
  return [
   'fullyTraceable' => $fullyTraceable,
   'rows' => $rows,
   'traceableSum' => $sum,
   'unreconciledAmount' => $fullyTraceable ? 0.0 : round($paidAmount - $sum, 2),
  ];
 }

 function getWarehouseImpact($link, $invoiceNumber) {
  $stmt = mysqli_prepare($link, "SELECT COUNT(*) AS cnt FROM `warehouse` WHERE `invoicenumber` = ?");
  mysqli_stmt_bind_param($stmt, "s", $invoiceNumber);
  mysqli_stmt_execute($stmt);
  $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  return (int)$row['cnt'];
 }

 // Only Manufactur invoices (paiedStuts=1) touch supporderitems at creation time.
 // Only reverts when this is the SOLE invoice against that supplierOrderNum - if the
 // order has more than one invoice, we can't safely tell which items belonged to which
 // invoice (the creation flow flips every item on the order regardless of batch), so we
 // leave the order status untouched rather than guess.
 function getOrderRevertEligibility($link, $supplierOrderNum, $paiedStuts) {
  $result = ['eligible' => false, 'soId' => null, 'note' => ''];

  if ((int)$paiedStuts !== 1) {
   $result['note'] = 'Supplier-type invoice - order status is not affected by invoicing.';
   return $result;
  }
  if ($supplierOrderNum === null || $supplierOrderNum === '') {
   $result['note'] = 'No linked manufacturing order found - order status unchanged.';
   return $result;
  }

  $stmt = mysqli_prepare($link, "SELECT COUNT(*) AS cnt FROM `supplierInvoice` WHERE `supplierOrderNum` = ?");
  mysqli_stmt_bind_param($stmt, "s", $supplierOrderNum);
  mysqli_stmt_execute($stmt);
  $cntRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  if ((int)$cntRow['cnt'] > 1) {
   $result['note'] = 'Order status left as-is - this manufacturing order has more than one invoice against it.';
   return $result;
  }

  $stmt = mysqli_prepare($link, "SELECT `poId` FROM `purchasesorder` WHERE `purchasesOrderNum` = ? LIMIT 1");
  mysqli_stmt_bind_param($stmt, "s", $supplierOrderNum);
  mysqli_stmt_execute($stmt);
  $poRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  if (!$poRow) {
   $result['note'] = 'Linked manufacturing order not found - order status unchanged.';
   return $result;
  }
  $poId = (int)$poRow['poId'];

  $stmt = mysqli_prepare($link, "SELECT `SOId` FROM `supplierorder` WHERE `custPOId` = ? LIMIT 1");
  mysqli_stmt_bind_param($stmt, "i", $poId);
  mysqli_stmt_execute($stmt);
  $soRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  if (!$soRow) {
   $result['note'] = 'Linked supplier order not found - order status unchanged.';
   return $result;
  }

  return ['eligible' => true, 'soId' => (int)$soRow['SOId'], 'note' => 'Order items will be reverted to Delivered status.'];
 }
?>

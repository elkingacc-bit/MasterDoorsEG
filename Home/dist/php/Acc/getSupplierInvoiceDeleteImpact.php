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

 $traceability = getPaymentTraceability($link, $invoiceId, $invoice['paidAmount']);
 $warehouseCount = getWarehouseImpact($link, $invoice['suppliersInvoiceNumber']);
 $orderRevert = getOrderRevertEligibility($link, $invoice['supplierOrderNum'], $invoice['paiedStuts']);

 echo json_encode([
  'success' => true,
  'invoiceId' => $invoiceId,
  'invoiceNumber' => $invoice['suppliersInvoiceNumber'],
  'total' => (float)$invoice['suppliersInvoiceTotal'],
  'paidAmount' => (float)$invoice['paidAmount'],
  'paymentReconciled' => $traceability['fullyTraceable'],
  'unreconciledAmount' => $traceability['unreconciledAmount'],
  'warehouseRowsAffected' => $warehouseCount,
  'orderStatusWillRevert' => $orderRevert['eligible'],
  'orderStatusNote' => $orderRevert['note'],
 ]);
?>

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

 $traceability = getPaymentTraceability($link, $invoiceId, $invoice['invoiceCollectAmount']);

 echo json_encode([
  'success' => true,
  'invoiceId' => $invoiceId,
  'invoiceNumber' => $invoice['salesInvoiceNumber'],
  'total' => (float)$invoice['totalInvoice'],
  'collectAmount' => (float)$invoice['invoiceCollectAmount'],
  'collectionReconciled' => $traceability['fullyTraceable'],
  'unreconciledAmount' => $traceability['unreconciledAmount'],
 ]);
?>

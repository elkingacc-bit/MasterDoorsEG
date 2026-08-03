<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 header('Content-Type: application/json; charset=utf-8');

 // TODO: عدّل السطر ده حسب اسم متغير الجلسة الفعلي فى نظامك لو مختلف
 $empCode = isset($_SESSION['userid']) ? intval($_SESSION['userid']) : 0;

 $invoiceDate = isset($_POST['invoiceDate']) ? $_POST['invoiceDate'] : null;
 $itemNames   = isset($_POST['itemName']) ? $_POST['itemName'] : [];
 $quantities  = isset($_POST['quantity']) ? $_POST['quantity'] : [];
 $prices      = isset($_POST['price']) ? $_POST['price'] : [];
 $taxes       = isset($_POST['tax']) ? $_POST['tax'] : [];

 // تحقق أساسي من البيانات
 if(empty($invoiceDate) || empty($itemNames) || count($itemNames) !== count($quantities) || count($itemNames) !== count($prices) || count($itemNames) !== count($taxes)){
  echo json_encode(['status' => 'error', 'message' => 'بيانات الفاتورة غير مكتملة']);
  exit;
 }

 mysqli_begin_transaction($link);

 try{
  // 1) إدراج رأس الفاتورة
  $stmtHeader = mysqli_prepare($link, "INSERT INTO `purchase_invoices` (`invoiceDate`, `empCode`) VALUES (?, ?)");
  mysqli_stmt_bind_param($stmtHeader, "si", $invoiceDate, $empCode);
  if(!mysqli_stmt_execute($stmtHeader)){
   throw new Exception('فشل حفظ رأس الفاتورة');
  }
  $invoiceId = mysqli_insert_id($link);
  mysqli_stmt_close($stmtHeader);

  // 2) إدراج كل صنف
  $stmtItem = mysqli_prepare($link, "INSERT INTO `purchase_invoice_items` (`invoiceId`, `itemName`, `quantity`, `price`, `tax`) VALUES (?, ?, ?, ?, ?)");

  for($i = 0; $i < count($itemNames); $i++){
   $itemName = trim($itemNames[$i]);
   $quantity = floatval($quantities[$i]);
   $price    = floatval($prices[$i]);
   $tax      = floatval($taxes[$i]);

   if($itemName === '' || $quantity <= 0){
    continue; // تجاهل الصفوف الفارغة
   }

   mysqli_stmt_bind_param($stmtItem, "isddd", $invoiceId, $itemName, $quantity, $price, $tax);
   if(!mysqli_stmt_execute($stmtItem)){
    throw new Exception('فشل حفظ صنف بالفاتورة');
   }
  }
  mysqli_stmt_close($stmtItem);

  mysqli_commit($link);
  echo json_encode(['status' => 'success', 'invoiceId' => $invoiceId]);

 }
 catch(Exception $e){
  mysqli_rollback($link);
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
 }
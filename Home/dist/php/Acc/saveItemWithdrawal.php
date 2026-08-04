<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 header('Content-Type: application/json; charset=utf-8');

 // TODO: عدّل السطر ده حسب اسم متغير الجلسة الفعلي فى نظامك لو مختلف
 $empCode = isset($_SESSION['userid']) ? intval($_SESSION['userid']) : 0;

 $withdrawDate = isset($_POST['withdrawDate']) ? $_POST['withdrawDate'] : null;
 $itemNames    = isset($_POST['itemName']) ? $_POST['itemName'] : [];
 $quantities   = isset($_POST['quantity']) ? $_POST['quantity'] : [];
 $descriptions = isset($_POST['description']) ? $_POST['description'] : [];

 if(empty($withdrawDate) || empty($itemNames) || count($itemNames) !== count($quantities)){
  echo json_encode(['status' => 'error', 'message' => 'بيانات الصرف غير مكتملة']);
  exit;
 }

 // تجميع الكميات المطلوبة لكل صنف (لو نفس الصنف اتكرر أكتر من سطر فى نفس العملية)
 $requestedByItem = [];
 for($i = 0; $i < count($itemNames); $i++){
  $itemName = trim($itemNames[$i]);
  $qty = floatval($quantities[$i]);
  if($itemName === '' || $qty <= 0){ continue; }
  if(!isset($requestedByItem[$itemName])){
   $requestedByItem[$itemName] = 0;
  }
  $requestedByItem[$itemName] += $qty;
 }

 if(empty($requestedByItem)){
  echo json_encode(['status' => 'error', 'message' => 'لا يوجد أصناف صالحة للصرف']);
  exit;
 }

 mysqli_begin_transaction($link);

 try{
  // 1) التحقق من الرصيد المتاح لكل صنف قبل أي حفظ
  foreach($requestedByItem as $itemName => $requestedQty){
   $stmtPurchased = mysqli_prepare($link, "SELECT COALESCE(SUM(`quantity`),0) AS totalQty FROM `purchase_invoice_items` WHERE `itemName` = ?");
   mysqli_stmt_bind_param($stmtPurchased, "s", $itemName);
   mysqli_stmt_execute($stmtPurchased);
   $purchasedQty = floatval(mysqli_fetch_assoc(mysqli_stmt_get_result($stmtPurchased))['totalQty']);
   mysqli_stmt_close($stmtPurchased);

   $stmtWithdrawn = mysqli_prepare($link, "SELECT COALESCE(SUM(`quantity`),0) AS totalQty FROM `item_withdrawal_items` WHERE `itemName` = ?");
   mysqli_stmt_bind_param($stmtWithdrawn, "s", $itemName);
   mysqli_stmt_execute($stmtWithdrawn);
   $withdrawnQty = floatval(mysqli_fetch_assoc(mysqli_stmt_get_result($stmtWithdrawn))['totalQty']);
   mysqli_stmt_close($stmtWithdrawn);

   $availableBalance = $purchasedQty - $withdrawnQty;

   if($requestedQty > $availableBalance){
    throw new Exception("الكمية المطلوبة من \"$itemName\" ($requestedQty) أكبر من الرصيد المتاح ($availableBalance)");
   }
  }

  // 2) إدراج رأس عملية الصرف
  $stmtHeader = mysqli_prepare($link, "INSERT INTO `item_withdrawals` (`withdrawDate`, `empCode`) VALUES (?, ?)");
  mysqli_stmt_bind_param($stmtHeader, "si", $withdrawDate, $empCode);
  if(!mysqli_stmt_execute($stmtHeader)){
   throw new Exception('فشل حفظ رأس عملية الصرف');
  }
  $withdrawalId = mysqli_insert_id($link);
  mysqli_stmt_close($stmtHeader);

  // 3) إدراج كل صنف مصروف (بنفس الصفوف الأصلية زي ما دخلها المستخدم)
  $stmtItem = mysqli_prepare($link, "INSERT INTO `item_withdrawal_items` (`withdrawalId`, `itemName`, `quantity`, `description`) VALUES (?, ?, ?, ?)");

  for($i = 0; $i < count($itemNames); $i++){
   $itemName = trim($itemNames[$i]);
   $qty = floatval($quantities[$i]);
   $desc = isset($descriptions[$i]) ? trim($descriptions[$i]) : '';

   if($itemName === '' || $qty <= 0){ continue; }

   mysqli_stmt_bind_param($stmtItem, "isds", $withdrawalId, $itemName, $qty, $desc);
   if(!mysqli_stmt_execute($stmtItem)){
    throw new Exception('فشل حفظ صنف بعملية الصرف');
   }
  }
  mysqli_stmt_close($stmtItem);

  mysqli_commit($link);
  echo json_encode(['status' => 'success', 'withdrawalId' => $withdrawalId]);

 }
 catch(Exception $e){
  mysqli_rollback($link);
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
 }
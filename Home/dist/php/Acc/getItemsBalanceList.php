<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 header('Content-Type: application/json; charset=utf-8');

 $sql = "SELECT
   p.itemName,
   (p.purchasedQty - COALESCE(w.withdrawnQty, 0)) AS balance
  FROM (
   SELECT `itemName`, SUM(`quantity`) AS purchasedQty
   FROM `purchase_invoice_items`
   GROUP BY `itemName`
  ) p
  LEFT JOIN (
   SELECT `itemName`, SUM(`quantity`) AS withdrawnQty
   FROM `item_withdrawal_items`
   GROUP BY `itemName`
  ) w ON w.itemName = p.itemName
  ORDER BY p.itemName ASC";

 $query = mysqli_query($link, $sql);
 $items = [];
 while($row = mysqli_fetch_assoc($query)){
  $items[] = [
   'itemName' => $row['itemName'],
   'balance'  => floatval($row['balance'])
  ];
 }

 echo json_encode(['status' => 'success', 'items' => $items]);
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 # Master Offer Data
 $supplier=mysqli_real_escape_string($link, $_POST['suppliersName']);
 $offerNum=mysqli_real_escape_string($link, $_POST['offerNumber']);
 $jopRowId=mysqli_real_escape_string($link, $_POST['jopId']);
 $dateOffer=mysqli_real_escape_string($link, $_POST['offerDate']);
 $poId=(int)$_POST['customerPoId'];
 $offerTotalAmount=(float)$_POST['totalOfferAmount'];
 # Log Data
 $action="Add New Suppliers Offer $offerNum";
 $logRef=114;
 #Items Data
 $rowCount=(int)$_POST['itemsRowCount'];
 // Table name must come from the same fixed set purchasesOrderData.php generates it from -
 // never trust a table name taken directly from user input
 $allowedItemsTables=['autodoorsoffer','itemoffer','stockoffers','maintoffers'];
 $itemsTable=in_array($_POST['offerTableDataName'], $allowedItemsTables, true) ? $_POST['offerTableDataName'] : null;
 # Add Supplier Offer Data
 $sqlSupplierOffer="INSERT INTO `purchasesorder`(`supplierid`, `purchasesOrderNum`, `purchasesOrderDate`, `jobref`, `totalAmout`, `poId`)
 VALUES ('$supplier','$offerNum','$dateOffer','$jopRowId','$offerTotalAmount',$poId)";
 if($itemsTable !== null && mysqli_query($link,$sqlSupplierOffer)){
  for ($rowI=1; $rowI <= $rowCount ; $rowI++) {
   $itemRowId = (int)$_POST['itemsRowId'.$rowI];
   $itemUnitPrice = (float)$_POST['unitPrice'.$rowI];
   #Update Items Unit Cost In Items Row
   $sqlUpdateorderitems="UPDATE `$itemsTable` SET `purchasesCost`= '$itemUnitPrice' WHERE `id` = $itemRowId";
   if(mysqli_query($link,$sqlUpdateorderitems)){
    echo "Data Saved";
    include_once("../aduLog.php");
   }
  }
 }
?>
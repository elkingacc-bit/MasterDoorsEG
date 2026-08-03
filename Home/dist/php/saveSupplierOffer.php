<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 # Master Offer Data
 $supplier=$_POST['suppliersName']; 
 $offerNum=$_POST['offerNumber'];
 $jopRowId=$_POST['jopId'];
 $dateOffer=$_POST['offerDate'];
 $poId=$_POST['customerPoId'];
 $offerTotalAmount=$_POST['totalOfferAmount'];
 # Log Data
 $action="Add New Suppliers Offer $offerNum";
 $logRef=114;  
 #Items Data
 $rowCount=$_POST['itemsRowCount'];
 $itemsTable=$_POST['offerTableDataName'];
 # Add Supplier Offer Data
 $sqlSupplierOffer="INSERT INTO `purchasesorder`(`supplierid`, `purchasesOrderNum`, `purchasesOrderDate`, `jobref`, `totalAmout`, `poId`) 
 VALUES ('$supplier','$offerNum','$dateOffer','$jopRowId','$offerTotalAmount',$poId)";
 if(mysqli_query($link,$sqlSupplierOffer)){
  for ($rowI=1; $rowI <= $rowCount ; $rowI++) { 
   $itemRowId = $_POST['itemsRowId'.$rowI];
   $itemUnitPrice = $_POST['unitPrice'.$rowI];
   #Update Items Unit Cost In Items Row
   $sqlUpdateorderitems="UPDATE `$itemsTable` SET `purchasesCost`= '$itemUnitPrice' WHERE `id` = $itemRowId";
   if(mysqli_query($link,$sqlUpdateorderitems)){
    echo "Data Saved";   	
    include_once("aduLog.php");
   }
  } 	
 }
?>
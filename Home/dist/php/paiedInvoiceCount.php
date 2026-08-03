<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $sqlSupplier="SELECT `suppliersInvoiceId` FROM `supplierInvoice` WHERE `suppliersInvoiceTotal` != `paidAmount`";
 $querySupplierData=mysqli_query($link,$sqlSupplier)or die("ERROR_SNSC : 02");
 echo $suplierInvCount=mysqli_num_rows($querySupplierData);
/*
 $sqlSupplierOrderData="SELECT `OIId`,`ItemRowId`,`qty`,`price`,`status`,`SOIdRef`,`soType`,`OIRef`,`receivedQTY`,`receiveddate` 
 FROM `supporderitems` 
 WHERE `status` = 5 
 GROUP BY `SOIdRef`";
 $querySupplierOrderData=mysqli_query($link,$sqlSupplierOrderData)or die("ERROR_SNSC : 02");
 $supplierOrder=mysqli_fetch_assoc($querySupplierOrderData);
 echo  mysqli_num_rows($querySupplierOrderData);
*/
?>
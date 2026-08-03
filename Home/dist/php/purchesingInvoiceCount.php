<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 //status = 1 : Delivaryed
 //status = 5 : Make Invoice
 //status = 6 : Paied Invoice
 $sqlSupplierOrderData="SELECT `OIId`,`ItemRowId`,`qty`,`price`,`status`,`SOIdRef`,`soType`,`OIRef`,`receivedQTY`,`receiveddate` FROM `supporderitems` WHERE `status` = 1 GROUP BY `SOIdRef`";
 $querySupplierOrderData=mysqli_query($link,$sqlSupplierOrderData)or die("ERROR_SNSC : 02");
 $supplierOrder=mysqli_fetch_assoc($querySupplierOrderData);



 echo  mysqli_num_rows($querySupplierOrderData);
?>
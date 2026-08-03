<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $sqlCustodyData="SELECT `salesInvoiceId` FROM `salesInvoice` WHERE `totalInvoice` != `invoiceCollectAmount`";
 $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_SNSC : 02");
 echo  mysqli_num_rows($queryCustodyData);
?>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 #1 Supplier Invoice
 $sqlSupplier="SELECT `OIId` FROM `supporderitems` WHERE `status` = 5 GROUP BY `SOIdRef`";
 $querySupplierData=mysqli_query($link,$sqlSupplier)or die("ERROR_Acc_Noti : 01");
 $suplierInvCount=mysqli_num_rows($querySupplierData);
 #2 Supplier Invoice
 $sqlSupplier2="SELECT `SOIdRef` FROM `supporderitems` WHERE `status` = 1 GROUP BY `SOIdRef`";
 $querySupplierData2=mysqli_query($link,$sqlSupplier2)or die("ERROR_Acc_Noti : 02");
 $suplierInvCount2=mysqli_num_rows($querySupplierData2);
 #3 Custody
 $sqlCustodyData="SELECT `empCode` , sum(`amount`) as cash,sum(`cashBack`) as comback FROM `custody` WHERE  `custodyRef` = 1";
 $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_Acc_Noti : 03");
 $custodyCount =mysqli_num_rows($queryCustodyData);
 #4 Sales Collect
 $sqlSalesData="SELECT `salesInvoiceId` FROM `salesInvoice` WHERE `totalInvoice` != `invoiceCollectAmount`";
 $querySalesData=mysqli_query($link,$sqlSalesData)or die("ERROR_Acc_Noti : 04");
 $salesInvCount = mysqli_num_rows($querySalesData); 
 #5 Sales Invoice
 $sqlMakeInv="SELECT `jobId` FROM `job` WHERE `offerStatus` = 'Won' AND `invoice` = 'No'";
 $queryMakeInv=mysqli_query($link,$sqlMakeInv)or die("ERROR_Acc_Noti : 05");
 $makeInvCount = mysqli_num_rows($queryMakeInv);
 #6
 echo $notification=($suplierInvCount + $suplierInvCount2 + $custodyCount + $salesInvCount + $makeInvCount);
?>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 #1 Supplier Invoice
 $sqlSupplier="SELECT `OIId` FROM `supporderitems` WHERE `status` = 5 GROUP BY `SOIdRef`";
 $querySupplierData=mysqli_query($link,$sqlSupplier)or die("ERROR_Acc_Noti : 01");
 $suplierInvCount=mysqli_num_rows($querySupplierData);
 if($suplierInvCount > 0){
  echo"<li class='nav-item' id='newSupplierInvNot'>
   <a href='#' class='nav-link'><i class='far fa-circle nav-icon'></i><p> Paied $suplierInvCount Invoice </p></a>
  </li>";	
 }
 #2 Supplier Invoice
 $sqlSupplier2="SELECT `SOIdRef` FROM `supporderitems` WHERE `status` = 1 GROUP BY `SOIdRef`";
 $querySupplierData2=mysqli_query($link,$sqlSupplier2)or die("ERROR_Acc_Noti : 02");
 $suplierInvCount2=mysqli_num_rows($querySupplierData2);
 if($suplierInvCount2 > 0){
 echo"<li class='nav-item' id='newSupplierInvReceivedNot'>
  <a href='#' class='nav-link'><i class='far fa-circle nav-icon'></i><p> Received $suplierInvCount2 Invoice</p></a>
 </li>"; 
 }
 #3 Custody
 $sqlCustodyData="SELECT `empCode` , sum(`amount`) as cash,sum(`cashBack`) as comback FROM `custody` WHERE  `custodyRef` = 1";
 $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_Acc_Noti : 03");
 $custodyCount =mysqli_num_rows($queryCustodyData);
 if($custodyCount > 0){
 echo"<li class='nav-item' id='allopenCustdy'>
  <a href='#' class='nav-link'><i class='far fa-circle nav-icon'></i><p>  $custodyCount Open Custody</p></a>
 </li>";
 }
 #4 Sales Collect
 $sqlSalesData="SELECT `salesInvoiceId` FROM `salesInvoice` WHERE `totalInvoice` != `invoiceCollectAmount`";
 $querySalesData=mysqli_query($link,$sqlSalesData)or die("ERROR_Acc_Noti : 04");
 $salesInvCount = mysqli_num_rows($querySalesData); 
 if($salesInvCount > 0){
 echo"<li class='nav-item' id='newCollectSalesInvNot'>
  <a href='#' class='nav-link'><i class='far fa-circle nav-icon'></i><p>$salesInvCount Collect Invoice </p></a>
 </li>";
 }
 #5 Sales Invoice
 $sqlMakeInv="SELECT `jobId` FROM `job` WHERE `offerStatus` = 'Won' AND `invoice` = 'No'";
 $queryMakeInv=mysqli_query($link,$sqlMakeInv)or die("ERROR_Acc_Noti : 05");
 $makeInvCount = mysqli_num_rows($queryMakeInv);
 if($makeInvCount > 0){
 echo"<li class='nav-item' id='newSalesInvNot'>
  <a href='#' class='nav-link'><i class='far fa-circle nav-icon'></i><p>Add $makeInvCount Sales Invoice </p></a>
 </li>";
 }
 #6
?>
<script type="text/javascript">
 $(document).ready(function(){
  $("#newSalesInvNot").click(function(){
   $(".data_display").html('');
   $(".m-0").html("Cash Withdraw");
   $(".data_display").load("dist/html/newSalesInvoice.html");
  });
  $("#newCollectSalesInvNot").click(function(){
   $(".data_display").html('');
   $(".m-0").html("Collect Invoice");
   $(".data_display").load("dist/html/newSalesInvoiceCollect.html");
  });
  $("#allopenCustdy").click(function(){
   $(".data_display").html('');
   $(".m-0").html("All Open Custody");
   $(".data_display").load("dist/php/allCustodyCountData.php");
  });
  $("#newSupplierInvNot").click(function(){
   var supplierType = 5;
   $.ajax({
    url:'dist/php/purchesingInvoiceData.php',
    type:"POST",
    data:{repType:supplierType},
    success: function(getInvData){
     $(".data_display").html('');
     $(".m-0").html("Supplier Invoice Paied");
     $(".data_display").html(getInvData)
    }
   });
   return false;
  });
  $("#newSupplierInvReceivedNot").click(function(){
   var suppType =1;
   $.ajax({
    url:'dist/php/purchesingInvoiceData.php',
    type:"POST",
    data:{repType:suppType},
    success: function(getInvPaiedData){
     $(".data_display").html('');
     $(".m-0").html("New Supplier Invoice Received");
     $(".data_display").html(getInvPaiedData)
    }
   });
   return false;
  });
 });
</script>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $sqlSupplierInoiceData="SELECT `jobId`,`jobtype` FROM `job` WHERE `offerStatus` = 'Won' AND `invoice` = 'No'";
 $querySupplierInoiceData=mysqli_query($link,$sqlSupplierInoiceData)or die("ERROR_SNSC : 01");
 while($supplierInoiceGetData=mysqli_fetch_assoc($querySupplierInoiceData)){
  $orderId=$supplierInoiceGetData['jobId'];
  $sqlSupplierOrder="SELECT `PoNum`,`custCode` FROM `customerpo` WHERE `jobidref` = $orderId";
  $querySupplierOrderData=mysqli_query($link,$sqlSupplierOrder)or die("ERROR_SNSC : 02");
  $supplierOrderData=mysqli_fetch_assoc($querySupplierOrderData);
  $supplierCode=$supplierOrderData['custCode'];
  $poNum=$supplierOrderData['PoNum'];
  $sqlSupplier="SELECT `customername` FROM `customers` WHERE `customercode` = $supplierCode";
  $querySupplierData=mysqli_query($link,$sqlSupplier)or die("ERROR_SNSC : 03");
  $supplierData=mysqli_fetch_assoc($querySupplierData);
  echo"<li>
   <button class='btn btn-link salesInvoiceData' value='$orderId'>Inv <span>$poNum</span>:- $supplierData[customername]</button>
  </li>";
 }
?>
<script type="text/javascript">
 $(document).ready(function(){
  $(".suppliersInvoiceData").click(function(){
   var empName =$(this).val();
   $.ajax({
    url:'dist/php/paiedSalesInvoiceData.php',
    type:"POST",
    data:{invId:empName},
    success: function(custodyCheack){
     $(".data_display").html('');
     $(".data_display").html(custodyCheack);
    }
   });
  });
 });
</script>
<div class="table-responsive-lg text-center">
 <table class="table table-sm table-bordered table-striped myTablePaymentInvoice w-100">
  <thead  class="bg-primary">
   <th> Number </th>
   <th> date </th>
   <th> Delivery </th>
   <th> Supplier </th>
   <th class="col-2">Amount</th>
   <th class="col-2">Paid</th>
   <th class="col-2">Remaining</th>
   <th class="col-1">Type</th>
   <th class="col-1">Action</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $stutsNum=$_POST['repType'];
    if($stutsNum == 1){
     // account/customer name resolved via JOIN instead of 2 extra queries per row
     $sqlPurOffer="SELECT po.`purchasesorderid`,po.`supplierid`,po.`purchasesOrderNum`, po.`jobref`,po.`VAT`,po.`totalAmout`, po.`poId`,po.`purchasesOrderRef`,
     j.`projectName`, ac.`codename` as cName
     FROM `purchasesorder` po
     LEFT JOIN `job` j ON j.`jobId` = po.`jobref`
     LEFT JOIN `allcode` ac ON ac.`code` = j.`customer`
     WHERE po.`purchasesOrderNum` NOT IN (SELECT `supplierOrderNum` FROM `supplierInvoice`)";
     $queryPurOffer=mysqli_query($link,$sqlPurOffer)or die("ERROR_SNSC : 01");
     if(mysqli_num_rows($queryPurOffer) > 0 ){
      while($manufacturInv=mysqli_fetch_assoc($queryPurOffer)){
       $orderId=$manufacturInv['purchasesorderid'];
       $projectName=$manufacturInv['projectName'];
       $cName=$manufacturInv['cName'] ?? "";

       echo"<tr>
        <td>$manufacturInv[purchasesOrderNum] </td>
        <td>$cName</td>
        <td>( $projectName )</td>
        <td>$manufacturInv[supplierid]</td>
        <td>".number_format(($manufacturInv['totalAmout']), 2)."</td> 
        <td>".number_format((0), 2)."</td> 
        <td>".number_format(($manufacturInv['totalAmout']), 2)."</td>
        <td> Manufactur </td>
        <td>
         <button class='btn btn-link suppliersInvoiceData' value='$orderId' data-toggle='tooltip' data-placement='left' title='Add'>
          <i class='fas fa-file-invoice nav-icon'  aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
         </button>
         <button class='btn btn-link editOfferPrices' value='$orderId' data-toggle='tooltip' data-placement='left' title='Edit Prices'>
          <i class='fas fa-edit nav-icon'  aria-hidden='true' style='font-size:20px;color:#5cb85c'></i>
         </button>
        </td>
       </tr>";
      }
     }
    }
    if($stutsNum == 5){
     // Paid Invoice - project name and supplier name resolved via JOIN instead of
     // up to 2 extra queries per row
     $sqlSupplierStockInv="SELECT si.`suppliersInvoiceId`,si.`suppliersInvoiceNumber`,si.`supplierOrderNum`,si.`suppliersInvoiceDate`,si.`supplierCode`,
     si.`suppliersInvoiceType`,si.`suppliersInvoiceSupTotal`,si.`suppliersInvoiceDiscount`,si.`suppliersInvoiceVat`,si.`suppliersInvoiceTotal`,si.`paidAmount`,si.`paidType`,
     si.`paiedStuts`, j.`projectName`, sup.`suppliername`
     FROM `supplierInvoice` si
     LEFT JOIN `purchasesorder` po ON po.`purchasesOrderNum` = si.`supplierOrderNum`
     LEFT JOIN `job` j ON j.`jobId` = po.`jobref`
     LEFT JOIN `allsuppliers` sup ON sup.`suppliercode` = si.`supplierCode`
     WHERE si.`suppliersInvoiceTotal` != si.`paidAmount`";
     $querySupplierStockInv=mysqli_query($link,$sqlSupplierStockInv)or die("ERROR_SNSC : 01");
     if(mysqli_num_rows($querySupplierStockInv) > 0 ){
      while($supplierStockInv=mysqli_fetch_assoc($querySupplierStockInv)){
       $invoiceType=$supplierStockInv['paiedStuts'];
       $invId=$supplierStockInv['suppliersInvoiceId'];
       $projectName=$supplierStockInv['projectName'];

       if($invoiceType == 1){
        $typeName = "Manufactur";
        $button="<button class='btn btn-link PaiedsuppliersInvoiceData' value='$invId' data-toggle='tooltip' data-placement='left' title='Paid'>
         <i class='far fa-money-bill-alt nav-icon'  aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
        </button>";
        $button2="<button class='btn btn-link showItems' value='$invId'>$supplierStockInv[suppliersInvoiceNumber]</button>";
        //showItems
        $jop=$projectName;
       }
       else if($invoiceType == 3){
        $typeName = "Supplier";
        $button="<button class='btn btn-link PaiedsuppliersStockInvoiceData' value='$invId' data-toggle='tooltip' data-placement='left' title='Paid'>
         <i class='far fa-money-bill-alt nav-icon'  aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
        </button>";
        $button2="<button class='btn btn-link showStockItems' value='$invId'>$supplierStockInv[suppliersInvoiceNumber]</button>";
       $jop="";
       }
       $invAmount=$supplierStockInv['suppliersInvoiceTotal'];
       $invPaid=$supplierStockInv['paidAmount'];
       $valid=($invAmount - $invPaid);
       echo"<tr>
        <td> $button2 </td>
        <td>$supplierStockInv[suppliersInvoiceDate]</td>
        <td>$jop</td>
        <td>$supplierStockInv[suppliername]</td>
        <td>".number_format(($invAmount), 2)."</td> 
        <td><button class='btn btn-link showPaied' value='$invId'>".number_format(($invPaid), 2)."</button></td> 
        <td>".number_format(($valid), 2)."</td>
        <td>$typeName</td>
        <td> $button</td>
       </tr>";
      }
     }
    }




   ?>
  </tbody>
 </table>
</div>
<!-- Modal -->
<div class="modal fade" id="supplierModal" aria-hidden="true" aria-labelledby="supplierModalData" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title" id="supplierModalData"> Supplier Invoice</h5>
    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   </div>
   <div class="modal-body supplierInvoiceData"></div>
   <div class="modal-footer msg"></div>
  </div>
 </div>
</div>
<script type="text/javascript">
$(document).ready(function(){  
 
var table = $('.myTablePaymentInvoice').DataTable({
   fixedHeader: false,
   scrollY:'50vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false, 
   order:[[0, "asc"]]
});

 $(function(){
  $('[data-toggle="tooltip"]').tooltip()
 })

 $(".showItems").click(function(){
  var rowId = $(this).val();
   $.ajax({
    url:'dist/php/supplierOrderItems.php',
    type:"POST",
    data:{orderId:rowId},
    success: function(getSuuplierOrderData){
     $(".supplierInvoiceData").html('');
     $("#supplierModalData").html('');
     $("#supplierModalData").html('Manufactur Order Items');
     $(".supplierInvoiceData").html(getSuuplierOrderData);
     $("#supplierModal").modal('show');  
    }
   });
  return false;
 });
 

$(".showStockItems").click(function(){
  var invoiceId = $(this).val();
   $.ajax({
    url:'dist/php/supplierStockItems.php',
    type:"POST",
    data:{rowId:invoiceId},
    success: function(getSuuplierStockData){
     $(".supplierInvoiceData").html('');
     $("#supplierModalData").html('');
     $("#supplierModalData").html('Supplier Order Items');
     $(".supplierInvoiceData").html(getSuuplierStockData);
     $("#supplierModal").modal('show');  
    }
   });
  return false;
 });


 $(".showPaied").click(function(){
  var invId = $(this).val();
  $.ajax({
   url:'dist/php/suuplierWithdrawalDetils.php',
   type:"POST",
   data:{orderId:invId},
   success: function(getSuuplierWithdrawalData){
    $(".supplierInvoiceData").html('');
    $("#supplierModalData").html('');
    $("#supplierModalData").html('Withdrawal To Supplier Details');
    $(".supplierInvoiceData").html(getSuuplierWithdrawalData);
    $("#supplierModal").modal('show');  
   }
  });
  return false;
 });
 $(".suppliersInvoiceData").click(function(){
  var PONum = $(this).val();
  $.ajax({
   url:'dist/php/supplierInvoiceContant.php',
   type:"POST",
   data:{orderId:PONum},
   success: function(getSuuplierData){
    $(".supplierInvoiceData").html('');
    $("#supplierModalData").html('');
    $("#supplierModalData").html('Received Invoice From Supplier Order');
    $(".supplierInvoiceData").html(getSuuplierData);
    $("#supplierModal").modal('show');  
   }
  });
  return false;
 });

 $(".editOfferPrices").click(function(){
  var orderId = $(this).val();
  $.ajax({
   url:'dist/php/editPurchasesOrderData.php',
   type:"POST",
   data:{orderId:orderId},
   success: function(getEditOfferData){
    $(".supplierInvoiceData").html('');
    $("#supplierModalData").html('');
    $("#supplierModalData").html('Edit Manufactur Offer Prices');
    $(".supplierInvoiceData").html(getEditOfferData);
    $("#supplierModal").modal('show');
   }
  });
  return false;
 });

 $(".PaiedsuppliersInvoiceData").click(function(){
  var empName =$(this).val();
  $.ajax({
   url:'dist/php/paiedSuppliersInvoiceData.php',
   type:"POST",
   data:{invId:empName},
   success: function(getPaiedData){
    $(".supplierInvoiceData").html('');
    $("#supplierModalData").html('');
    $("#supplierModalData").html('Supplier Withdrawal Data');
    $(".supplierInvoiceData").html(getPaiedData);
    $("#supplierModal").modal('show');  
   }
  });
  return false;
 });
 
$(".PaiedsuppliersStockInvoiceData").click(function(){
  var invNum =$(this).val();
  $.ajax({
   url:'dist/php/paiedSuppliersStockInvoice.php',
   type:"POST",
   data:{invId:invNum},
   success: function(getPaiedStockData){
    $(".supplierInvoiceData").html('');
    $("#supplierModalData").html('');
    $("#supplierModalData").html('Supplier Withdrawal Data');
    $(".supplierInvoiceData").html(getPaiedStockData);
    $("#supplierModal").modal('show');  
   }
  });
  return false;
 });

$(".manufacturOffer").click(function(){
  var invNum =$(this).val();
  $.ajax({
   url:'dist/php/purchasesOrderData.php',
   type:"POST",
   data:{poId:invNum},
   success: function(getPaiedStockData){
    $(".supplierInvoiceData").html('');
    $("#supplierModalData").html('');
    $("#supplierModalData").html('Supplier Withdrawal Data');
    $(".supplierInvoiceData").html(getPaiedStockData);
    $("#supplierModal").modal('show');  
   }
  });
  return false;
 });


 $(".close").click(function(){
  $("#supplierModal").modal('toggle');
 });


 });
</script>
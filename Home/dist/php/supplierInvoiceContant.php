<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $orderId=(int)$_POST['orderId'];
 $sqlPurOffer="SELECT `supplierid`,`purchasesOrderNum`,`purchasesOrderDate`, `jobref`,`VAT`,`totalAmout`, `poId`,`purchasesOrderRef` 
 FROM `purchasesorder` 
 WHERE `purchasesorderid` = $orderId ";
 $queryPurOffer=mysqli_query($link,$sqlPurOffer)or die("ERROR_SNSC : 01");
 if(mysqli_num_rows($queryPurOffer) > 0){
  $purOfferData=mysqli_fetch_assoc($queryPurOffer);
  $jopRef = $purOfferData['jobref'];
  $poId=$purOfferData['poId'];
  if($purOfferData['VAT'] > 0){
   $vatOption="<option value='VAT'>VAT</option>";
  } 
  else{
   $vatOption="<option value='Not'>Not VAT</option>";
  }
  // Get Supplier Name
  $sqlSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $purOfferData[supplierid]";
  $querySupplierData=mysqli_query($link,$sqlSupplier)or die("ERROR_SNSC : 01");
  $supplierData=mysqli_fetch_assoc($querySupplierData);    
 }

  // Get Supplier Invoice Count
  $sqlSupplierInvoiceCount="SELECT `suppliersInvoiceId` FROM `supplierinvoice` ORDER BY suppliersInvoiceId DESC LIMIT 1";
  $querySupplierInvoiceCountData=mysqli_query($link,$sqlSupplierInvoiceCount)or die("ERROR_SNSC : 01");
  $supplierInvoiceCountData=mysqli_fetch_assoc($querySupplierInvoiceCountData);
  $lastId=$supplierInvoiceCountData['suppliersInvoiceId'];
  $invNum=($lastId + 1);
?>
<form id="suplierInvoiceData">
 <center>
  <div class="table-responsive-lg">
   <table class="table table-sm w-75 table-borderless">
    <thead class="text-center">
     <th style="display: none;"></th>
     <th>Date</th>
     <th>Number</th>
     <th>VAT</th>
     <th>Supplier</th>
     <th>Order Number</th>
     <th>Total</th>
    </thead>
    <tbody>
     <td style="display: none;">
      <input type="number" name="invoiceDis" class="form-control" id="invoiceDis" value="0" min="0" max="100"> 
     </td>   
     <td>
      <input type="date" class="form-control text-left" name="invoiceDate" id="invoiceDate" value="<?php echo $purOfferData['purchasesOrderDate'] ?>"
       required>
     </td>
     <td><input type="text" class="form-control text-left" name="invoiceNumber" id="invoiceNumber" value="INV- <?php echo $invNum ?>" required></td>
     <td><select class="form-control" name="invoiceTypy" id="invoiceTypy"><?php echo $vatOption; ?></select></td>    
     <td>
      <input type="text" class="form-control text-left" id="suplierName" value="<?php echo  $supplierData['suppliername'] ?>" readonly>
      <input class="form-control text-center" name="suplierCode" id="suplierCode" value="<?php echo  $purOfferData['supplierid'] ?>" hidden>
     </td>
     <td><input type="text" class="form-control text-left" name="orderNumber" id="orderNumber" value="<?php echo  $purOfferData['purchasesOrderNum'] ?>" readonly></td>
     <td><?php echo  number_format(($purOfferData['totalAmout']), 2) ?></td>
    </tbody>
   </table>
   <table class='table table-sm w-75'>
    <thead>
     <th>Items</th>
     <th>Quantity</th>
     <th>Unit Price</th>
     <th>Total</th>
    </thead>
    <tbody>
     <?php
      $sn=1;
      $totalOrder=0;
      $sqlSupplierOrder="SELECT `SOId`,`SuppCode`,`OrderNumber`,`date`,`deliveryDate`,`totalAmout`,`authUser`,`orderNotes`,`paied`,`custPOId`,`SORef` 
      FROM `supplierorder` 
      WHERE `custPOId` = $poId";
      $querySupplierOrderData=mysqli_query($link,$sqlSupplierOrder)or die("ERROR_SNSC : 01");
      $supplierOrderData=mysqli_fetch_assoc($querySupplierOrderData);
      $SOId=$supplierOrderData['SOId'];
      # Get Supplier Order Items
      $sqlSupplierOrderItems="SELECT `ItemRowId`,`qty`,`price`,`status`,`SOIdRef`,`soType`,`OIRef`,`receivedQTY`,`receiveddate` 
      FROM `supporderitems` WHERE `SOIdRef` = $SOId";
      $querySupplierOrderItemsData=mysqli_query($link,$sqlSupplierOrderItems)or die("ERROR_SNSC : 01");
      $orderItemsCount=mysqli_num_rows($querySupplierOrderItemsData);
      echo "<input id='itemCount' value='$orderItemsCount' name='dataCount' hidden><input id='SOId' value='$SOId' name='SOId' hidden>";
      while($orderItemsData=mysqli_fetch_assoc($querySupplierOrderItemsData)){
       $itemRowId=$orderItemsData['ItemRowId'];
       echo "<input id='itemRow$sn' value='$itemRowId' name='itemRow$sn' hidden>";
       # Get Supplier Order Items Countant
       $orderType=$orderItemsData['soType'];
       # Automatic Type
       if($orderType == "Automatic"){
        $sqlOrderItems="SELECT `doortype`,`doorspecs`,`motorspecs`,`doorprice`,`doorqty`,`totalprice`,`jobid`,`ref`, `purchasesCost` FROM `autodoorsoffer` WHERE `id` = $itemRowId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        $itemsData=mysqli_fetch_assoc($queryOrderItemsData);
        $items=$itemsData['doortype'];
        $itemsUnitCost=$itemsData['purchasesCost'];
       }
       # Doors Type
       else if($orderType == "Doors" ){
        $sqlOrderItems="SELECT `itemname`,`itemtype`,`itemhight`,`itemwidth`,`itemdepth`,`itemm2`,`msquerprice`,`itemqty`,`totalprice`,`jobref`,`itemRef`,`FRMin`,
        `remarks`,`Overlap`,`ref`, `purchasesCost` FROM `itemoffer` WHERE `id` = $itemRowId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        $itemsData=mysqli_fetch_assoc($queryOrderItemsData);
        $items=$itemsData['itemname'];
        $itemsUnitCost=$itemsData['purchasesCost'];
       }
       # Stock Type
       else if($orderType == "Stock" ){
        $sqlOrderItems="SELECT `id`, `descripcode`,`descripqty`,`descripprice`,`totalprice`,`jobref`,`ref`,`whref`, `purchasesCost` FROM `stockoffers` WHERE `jobref` = $jopId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
         $stocCode=$itemsData['descripcode'];
         $getAllItemCode="SELECT `descriptionname` FROM `stockitems` WHERE `description` = $stocCode";
         $queryAllItemCode=mysqli_query($link,$getAllItemCode)or die("ERROR :01-AIC_AIDL_S");
         $resAllItemCode=mysqli_fetch_assoc($queryAllItemCode);

        $items=$resAllItemCode['descriptionname'];
        $itemsUnitCost=$itemsData['purchasesCost'];        
        
        }
       }//Stock 
       # Maintenance Type  
       else if($orderType == "Maintenance" ){
        $sqlOrderItems="SELECT `id`, `type`, `hights`, `widths`, `depths`, `price`, `typeqty`, `totalprice`, `jobid`, `ref`, `purchasesCost` FROM `maintoffers` WHERE `jobid` = $jopId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
         $items=$itemsData['type'];
         $itemsUnitCost=$itemsData['purchasesCost'];
        }
       }
       $totalCost=( ($orderItemsData['receivedQTY']) * ($itemsUnitCost));
       echo"<tr>
        <td><input type='text' class='form-control' name='itemsName$sn' id='itemsName$sn' value='$items' readonly></td>
        <td><input type='text' class='form-control' name='itemsCount$sn' id='itemsCount$sn' value='$orderItemsData[receivedQTY]' readonly></td>
        <td><input type='text' class='form-control' name='unitPrice$sn' id='unitPrice$sn'  value='$itemsUnitCost'></td>
        <td><input type='text' class='form-control' name='itemTotalPrice$sn' id='itemTotalPrice$sn' readonly></td>
       </tr>";
       $sn++;
       $totalOrder+= $totalCost;
      }

     ?>
    </tbody>
   </table>
   <h3>Total:- <small><?php echo number_format(($totalOrder), 2); ?></small></h3>
  </div>
  <button class="btn btn-info" id="saveInvoice">Save</button>

  <button class="btn btn-info" id="printPo" value="<?php echo $orderId ?>">Print</button>

 </center>
</form> 
<div id="testData"></div>
<script type="text/javascript">
 $(document).ready(function(){
  "use strict";
  let rowCount =$("#itemCount").val();
  for (let i = 1; i <= rowCount; i++) {
    var itemcount = $("#itemsCount"+i).val();
    var itemprice = $("#unitPrice"+i).val();
    var supTotal = (Number(itemcount)*Number(itemprice));
    $("#itemTotalPrice"+i).val(supTotal);
  }
  $("#saveInvoice").click(function(){
   for (let rowNum = 1; rowNum <= rowCount; rowNum++) {
    var unitPrice = $("#unitPrice"+rowNum).val();
    if(unitPrice == ""){
     alert ("Dont Skip Unit Price Empty");
    }  
   }
   $.ajax({
    url:'dist/php/saveSupplierInvoice.php',
    type:"POST",
    data:$('#suplierInvoiceData').serialize(),
    success: function(invSuuplierData){
     $("#supplierModal").modal('toggle');
     alert(invSuuplierData);
     $(".data_display").load("dist/php/purchesingInvoiceData.php");
    }
   });
   return false;
  });
 

 $("#printPo").click(function(){
  var rowId = $(this).val();
  $.ajax({
   url:'dist/php/printManufacturOrder.php',
   type:"POST",
   data:{orderId:rowId},
   success: function(invSuuplierData2){
window.open("dist/php/printManufacturOrder.php?orderId="+rowId, "_blank");
   }
  });
  
   return false;
  });
 });
</script>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $orderId=(int)$_POST['orderId'];

 // Look up the existing offer header
 $sqlOrder="SELECT `purchasesorderid`,`supplierid`,`purchasesOrderNum`,`purchasesOrderDate`,`jobref`,`poId`,`totalAmout` FROM `purchasesorder` WHERE `purchasesorderid` = $orderId";
 $queryOrder=mysqli_query($link,$sqlOrder)or die("ERROR_SNSC : 01");
 $orderData=mysqli_fetch_assoc($queryOrder);
 $jopId=$orderData['jobref'];
 $poId=$orderData['poId'];

 $sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $orderData[supplierid]";
 $queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :01-AU_AU_S".mysqli_error($link));
 $supplierData=mysqli_fetch_assoc($queryGetSupplier);

 $sqlProjectName="SELECT `projectName` FROM `job` WHERE `jobId` = $jopId";
 $quaryProjectName=mysqli_query($link,$sqlProjectName)or die("ERROR LOA_S:01");
 $projectName=mysqli_fetch_assoc($quaryProjectName);

 // Same orderType -> item table resolution as purchasesOrderData.php (the create-offer flow)
 $sqlGetOrderType="SELECT `orderType` FROM `customerpo` WHERE `poId` = $poId";
 $queryGetOrderType=mysqli_query($link,$sqlGetOrderType)or die("ERROR_SNSC : 01");
 $orderTypeData=mysqli_fetch_assoc($queryGetOrderType);
 $orderType=$orderTypeData['orderType'];
 if($orderType == "Automatic"){$myTable= "autodoorsoffer";}
 else if($orderType == "Doors" ){$myTable= "itemoffer";}
 else if($orderType == "Stock" ){$myTable= "stockoffers";}
 else if($orderType == "Maintenance" ){$myTable= "maintoffers";}

 echo "<input id='editTitelData' value='Edit Prices - $supplierData[suppliername] / $projectName[projectName]' style='display: none;'>";
?>
<style>
 #editOfferPricesForm .form-control{transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;}
 #editOfferPricesForm .form-control:focus{box-shadow: 0 0 0 0.2rem rgba(40,167,69,.25);}
 #editOfferPricesForm .is-invalid{border-color:#dc3545;}
 #saveEditOfferPrices{transition: transform .1s ease-in-out;}
 #saveEditOfferPrices:active{transform: scale(0.97);}
</style>
<form id="editOfferPricesForm">
 <center>
  <div class="table-responsive-lg text-center">
   <table class="table table-sm w-75 table-borderless">
    <thead class='bg-info'>
     <th>Supplier</th>
     <th>Order Num</th>
     <th>Order Date</th>
     <th>Current Total</th>
    </thead>
    <tbody>
     <td><?php echo $supplierData['suppliername']; ?></td>
     <td><?php echo $orderData['purchasesOrderNum']; ?></td>
     <td><?php echo $orderData['purchasesOrderDate']; ?></td>
     <td><?php echo number_format($orderData['totalAmout'], 2); ?></td>
    </tbody>
   </table>
   <hr>
   <div class="editOfferItems">
    <table class="table table-sm w-100 table-borderless">
     <thead class='bg-info'>
      <th style='display: none;'>ItemRowId</th>
      <th>Items</th>
      <th>Quantity</th>
      <th>Unit Price</th>
      <th>Amount</th>
     </thead>
     <tbody>
      <?php
       $rowCount=0;
       if($orderType == "Automatic"){
        $sqlOrderItems="SELECT `id`,`doortype`,`doorspecs`,`doorqty`,`purchasesCost` FROM `autodoorsoffer` WHERE `jobid` = $jopId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
         $rowCount++;
         $curPrice=$itemsData['purchasesCost'] ?? 0;
         echo "<tr>
          <td style='display: none;'><input type='number' name='itemsRowId$rowCount' value='$itemsData[id]'></td>
          <td class='col-6'>$itemsData[doorspecs]</td>
          <td class='col-2'><input type='number' class='form-control' id='qun$rowCount' value='$itemsData[doorqty]' readonly></td>
          <td class='col-2'><input type='number' class='form-control' id='unitPrice$rowCount' name='unitPrice$rowCount' value='$curPrice'></td>
          <td class='col-2'><input type='number' class='form-control' id='totalPrice$rowCount' readonly></td>
         </tr>";
        }
       }
       else if($orderType == "Doors" ){
        $sqlOrderItems="SELECT `id`,`itemtype`,`itemhight`,`itemwidth`,`itemqty`,`purchasesCost` FROM `itemoffer` WHERE `jobref` = $jopId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
         $rowCount++;
         $curPrice=$itemsData['purchasesCost'] ?? 0;
         echo "<tr><td style='display: none;'><input type='number' name='itemsRowId$rowCount' value='$itemsData[id]'></td>
          <td class='col-6'>$itemsData[itemtype] -w $itemsData[itemwidth] -h $itemsData[itemhight] </td>
          <td class='col-2'><input type='number' class='form-control' id='qun$rowCount' value='$itemsData[itemqty]' readonly></td>
          <td class='col-2'><input type='number' class='form-control' id='unitPrice$rowCount' name='unitPrice$rowCount' value='$curPrice'></td>
          <td class='col-2'><input type='number' class='form-control' id='totalPrice$rowCount' readonly></td>
         </tr>";
        }
       }
       else if($orderType == "Stock" ){
        $sqlOrderItems="SELECT `id`, `descripcode`,`descripqty`,`purchasesCost` FROM `stockoffers` WHERE `jobref` = $jopId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
         $rowCount++;
         $curPrice=$itemsData['purchasesCost'] ?? 0;
         echo"<tr><td style='display: none;'><input type='number' name='itemsRowId$rowCount' value='$itemsData[id]'></td>
          <td class='col-6'>$itemsData[descripcode]</td>
          <td class='col-2'><input type='number' class='form-control' id='qun$rowCount' value='$itemsData[descripqty]' readonly></td>
          <td class='col-2'><input type='number' class='form-control' id='unitPrice$rowCount' name='unitPrice$rowCount' value='$curPrice'></td>
          <td class='col-2'><input type='number' class='form-control' id='totalPrice$rowCount' readonly></td>
         </tr>";
        }
       }
       else if($orderType == "Maintenance" ){
        $sqlOrderItems="SELECT `id`, `type`, `typeqty`, `purchasesCost` FROM `maintoffers` WHERE `jobid` = $jopId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
         $rowCount++;
         $curPrice=$itemsData['purchasesCost'] ?? 0;
         echo"<tr><td style='display: none;'><input type='number' name='itemsRowId$rowCount' value='$itemsData[id]'></td>
          <td class='col-6'>$itemsData[type]</td>
          <td class='col-2'><input type='number' class='form-control' id='qun$rowCount' value='$itemsData[typeqty]' readonly></td>
          <td class='col-2'><input type='number' class='form-control' id='unitPrice$rowCount' name='unitPrice$rowCount' value='$curPrice'></td>
          <td class='col-2'><input type='number' class='form-control' id='totalPrice$rowCount' readonly></td>
         </tr>";
        }
       }
      ?>
     </tbody>
     <tfoot>
      <th></th>
      <th></th>
      <th></th>
      <th>New Total</th>
      <th><input type='number' id='editNewTotal' readonly></th>
     </tfoot>
    </table>
   </div>
  </div>
  <input type="hidden" id="editOrderId" name="orderId" value="<?php echo $orderId; ?>">
  <input type="hidden" name="offerTableDataName" value="<?php echo $myTable; ?>">
  <input type="hidden" id="editRowCount" name="itemsRowCount" value="<?php echo $rowCount; ?>">
  <button class="btn btn-success" id="saveEditOfferPrices">Save</button>
 </center>
</form>
<script type="text/javascript">
 $(document).ready(function(){
  var dataTitel = $("#editTitelData").val();
  $(".m-0").html("");
  $(".m-0").html(dataTitel);

  let count = $("#editRowCount").val();

  function recalcTotal(){
   let total = 0;
   for (let i = 1; i <= count; i++){
    var up = $("#unitPrice"+i).val();
    var q = $("#qun"+i).val();
    var tAmount = (Number(up) * Number(q));
    $("#totalPrice"+i).val(tAmount.toFixed(2));
    total += tAmount;
   }
   $("#editNewTotal").val(total.toFixed(2));
  }

  for (let i = 1; i <= count; i++){
   $("#unitPrice"+i).on('input change', function(){
    $(this).removeClass('is-invalid');
    recalcTotal();
   });
  }
  recalcTotal();

  $("#saveEditOfferPrices").click(function(){
   var valid = true;
   for (let i = 1; i <= count; i++){
    var up = $("#unitPrice"+i).val();
    if(up === '' || Number(up) < 0){
     $("#unitPrice"+i).addClass('is-invalid');
     valid = false;
    }
   }
   if(!valid){
    alert("Dont Skip Unit Price Empty");
    return false;
   }
   var $btn = $('#saveEditOfferPrices');
   var originalText = $btn.text();
   $.ajax({
    url:'dist/php/Acc/saveEditPurchasesOrderPrices.php',
    type:"POST",
    data:$('#editOfferPricesForm').serialize(),
    beforeSend:function(){
     $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    },
    success: function(saveEditOfferData){
     $btn.prop('disabled', false).text(originalText);
     alert(saveEditOfferData);
    }
   });
   return false;
  });
  return false;
 });
</script>

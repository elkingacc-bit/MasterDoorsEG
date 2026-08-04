<div class="table-responsive-lg">
 <table class="table table-sm table-bordered">
  <thead class="text-center bg-secondary">
   <th>Items</th>
   <th>Quantity</th>
   <th>Unit Price</th>
   <th>Total</th>
   <th>Action</th>
  </thead>
  <tbody>
   <?php
    include_once("../connection.php");
    $invId=$_POST['inv'];
    $totalRefInv = 0 ;
    echo "<input type='number' id='invRowId' value='$invId' style='display: none'>";
    $sqlInvData="SELECT `suppliersInvoiceSupTotal` FROM `supplierInvoice` WHERE `suppliersInvoiceId` = $invId";  
    $queryInvData=mysqli_query($link,$sqlInvData);
    $invData=mysqli_fetch_assoc($queryInvData);
    $sqlAccountantCodeData="SELECT `supplierInvoiceDataId`, `supplierInvoiceNumber`,`ItemRowId`,`supplierInvoiceCount`,`supplierInvoiceUnitPrice`,
    `supplierInvoiceTotalItems`,`ref` FROM `supplierInvoiceData`
    WHERE `supplierInvoiceNumber` = $invId ";  
    $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
    $accCount=mysqli_num_rows($queryAccountantCodeData);
    if($accCount == 0 ){
     $totalRefInv = 0;
    }
    else{
     while($accData=mysqli_fetch_assoc($queryAccountantCodeData)){
      $itemsName=$accData['ItemRowId'];
      $itemsRowId=$accData['supplierInvoiceDataId'];
      if($accData['ref'] == 0){
       $actionButton="<td class='col-sm-2 text-center'>
        <button value='$itemsRowId' class='btn btn-link dellOne'><i class='far fa-trash-alt' aria-hidden='true' style='font-size:16px;color:#d9534f'></i></button>
        <button value='$itemsRowId' class='btn btn-link editOne'><i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'></i></button>
       </td>";
      }
      else if($accData['ref'] == 1){
       $actionButton="<td class='col-sm-2 text-center'>
        <p>This Item In Stock</p>
       </td>";
      }
      $getAllItemCode="SELECT `descriptionname` FROM `stockitems` WHERE `description` = $itemsName";
      $queryAllItemCode=mysqli_query($link,$getAllItemCode)or die("ERROR :01-AIC_AIDL_S");
      $resAllItemCode=mysqli_fetch_assoc($queryAllItemCode);
      $totalRefInv += round(($accData['supplierInvoiceTotalItems']), 2);
      echo"<tr>
       <td>$resAllItemCode[descriptionname]</td> 
       <td class='text-center'>$accData[supplierInvoiceCount]</td>
       <td class='text-center'>$accData[supplierInvoiceUnitPrice]</td>
       <td class='text-center'>$accData[supplierInvoiceTotalItems]</td>
       $actionButton
      </tr>";
     }        
    }
   ?>
  </tbody>
 </table>
</div>


<!-- Modal -->
<div class="modal fade" id="supplierInvoiceItemsModal" aria-hidden="true" aria-labelledby="supplierItemsModalData" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title" id="supplierItemsModalData">Add Items To Invoice</h5>
    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   </div>
   <div class="modal-body supplierItemsModalEdit"></div>
   <div class="modal-footer msg"></div>
  </div>
 </div>
</div>
<input type='number' id='refTotal' value='<?php echo  round(($totalRefInv), 2) ;?>' style="display: none">
<script type="text/javascript">
 $(document).ready(function(){
  $(".dellOne").click(function(){
   var supInvRowId=$(this).val();
   $.ajax({
    url:'dist/php/Acc/delDataToEditSupplierStockInv.php',
    type:"POST",
    data:{rRowId:supInvRowId},
    success: function(supInvDeleteData){

var invLast = $("#invoiceId").val();
 $.ajax({
  url:'dist/php/Acc/suppliersInvoiceItems2.php',
  type:"POST",
  data:{inv:invLast},
  success: function(getInvoiceData){
   $(".invoiceData").html(getInvoiceData);
   $("#AddItems").show();
   $("#saveItems").show();
   $("#items").val('');
   $("#invoiceQyan").val('');
   $("#unitPrice").val('');
   $("#supTotal").val('');
   var totalInv=$("#invoiceSuptotal").val();
   var totalRef=$("#refTotal").val();
   var remining = Math.round(Number(totalInv)-Number(totalRef)).toFixed(2);
   $("#itemsValue").val(remining);
   $(".invRem").html(remining);
   if(totalInv == totalRef){
    $("#AddItems").hide();
    $("#closePage").show();
   }
   else if(totalInv >= totalRef){
    $("#AddItems").prop('disabled', false); // disable button
   }
  }
 });

    }
   });
  });


  $(".editOne").click(function(){
   var supInvRowId=$(this).val();
   var supInvTableId=$("#invRowId").val();
   $.ajax({
    url:'dist/php/Acc/getDataToEditSupplierInv.php',
    type:"POST",
    data:{rRowId:supInvRowId,tableId:supInvTableId},
    success: function(supInvDeleteData){
     $("#supplierItemsModalData").html('');
     $("#supplierItemsModalData").html('Edit To');
     $(".supplierItemsModalEdit").html('');
     $(".supplierItemsModalEdit").html(supInvDeleteData);
     $("#supplierInvoiceItemsModal").modal('show');
    }
   });
  });
 
 $(".editStock").click(function(){
   var supInvRowIdS=$(this).val();
   var supInvTableId=$("#invRowId").val();
   $.ajax({
    url:'dist/php/Acc/getDataToEditSupplierStockInv.php',
    type:"POST",
    data:{rRowId:supInvRowIdS,tableId:supInvTableId},
    success: function(supInvDeleteData){
     $("#supplierItemsModalData").html('');
     $("#supplierItemsModalData").html('Edit To');
     $(".supplierItemsModalEdit").html('');
     $(".supplierItemsModalEdit").html(supInvDeleteData);
     $("#supplierInvoiceItemsModal").modal('show');
    }
   });
  });


 });

</script>


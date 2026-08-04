<div class="table-responsive-lg">
 <table class="table table-sm">
  <thead class="text-center">
   <th>Items</th>
   <th>Quantity</th>
   <th>Unit Price</th>
   <th>Amount</th>
   <th></th>
  </thead>
  <tbody>
   <?php
    include_once("../connection.php");
    $invId=$_POST['inv'];
    $totalRefInv = 0 ;
    $sqlInvData="SELECT `suppliersInvoiceId`,`suppliersInvoiceSupTotal` FROM `supplierInvoice` WHERE `suppliersInvoiceId` = $invId";  
    $queryInvData=mysqli_query($link,$sqlInvData);
    $invData=mysqli_fetch_assoc($queryInvData);
    $tableId=$invData['suppliersInvoiceId'];
    $sqlAccountantCodeData="SELECT `supplierInvoiceDataId`,`ItemRowId`,`supplierInvoiceCount`,`supplierInvoiceUnitPrice`,`supplierInvoiceTotalItems` 
    FROM `supplierInvoiceData` WHERE `supplierInvoiceNumber` = $invId ";  
    $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
    if(mysqli_num_rows($queryAccountantCodeData) > 0){
     while($accData=mysqli_fetch_assoc($queryAccountantCodeData)){
      $itemsName=$accData['ItemRowId'];
      $supplierRowId=$accData['supplierInvoiceDataId'];
      $getAllItemCode="SELECT `descriptionname` FROM `stockitems` WHERE `description` = $itemsName";
      $queryAllItemCode=mysqli_query($link,$getAllItemCode)or die("ERROR :01-AIC_AIDL_S");
      $resAllItemCode=mysqli_fetch_assoc($queryAllItemCode);
      $totalRefInv += $accData['supplierInvoiceTotalItems'];
      echo"<tr>
       <td>$resAllItemCode[descriptionname]</td> 
       <td class='text-center'>$accData[supplierInvoiceCount]</td>
       <td class='text-center'>$accData[supplierInvoiceUnitPrice]</td>
       <td class='text-center'>$accData[supplierInvoiceTotalItems]</td>
      <td><button value='$supplierRowId' class='btn btn-link dellOne'><i class='far fa-trash-alt' aria-hidden='true' style='font-size:16px;color:#d9534f'></i></button></td>
      </tr>";
     }        
    }
   ?>
  </tbody>
 </table>
</div> 
<input type='number' class='invoiceId' value='<?php echo $tableId;?>' style="display: none;">
<input type='number' id='refTotal' value='<?php echo  $totalRefInv;?>' style="display: none;">
<script type="text/javascript">
 $(document).ready(function(){
  $(".dellOne").click(function(){
   var supInvRowId=$(this).val();
   $.ajax({
    url:'dist/php/Acc/delDataItemsSupplierStockInv.php',
    type:"POST",
    data:{rRowId:supInvRowId},
    success: function(supInvDeleteRow){
     alert("Items Deleted From Invoice");
     var invLast2 = Number($(".invoiceId").val());
     $.ajax({
      url:'dist/php/Acc/suppliersInvoiceItems.php',
      type:"POST",
      data:{inv:invLast2},
      success: function(newInvData){
       //$(".invoiceData").html("");
       $(".invoiceData").html(newInvData);
      }
     });


    }
   });
  });
  return false;
 }); 
</script>
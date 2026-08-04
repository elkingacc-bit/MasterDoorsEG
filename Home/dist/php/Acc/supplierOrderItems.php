<div class="table-responsive-lg text-center">
 <table class='table table-sm table-bordered table-striped'>
  <thead class='bg-info'>
   <th>Items</th>
   <th>Quantity</th>
   <th>Unit Price</th>
   <th>Amount</th>
  </thead>
  <tbody>    
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("../connection.php");
    $orderId=$_POST['orderId'];
    $sqlSupplierStockInv="SELECT `supplierOrderNum` FROM `supplierInvoice` WHERE `suppliersInvoiceId` = $orderId";
    $querySupplierStockInv=mysqli_query($link,$sqlSupplierStockInv)or die("ERROR_SNSC : 01");
    $supplierStockInv=mysqli_fetch_assoc($querySupplierStockInv);
    $soNum=$supplierStockInv['supplierOrderNum'];
    $sqlPurOffer="SELECT `poId` FROM `purchasesorder` WHERE `purchasesOrderNum` = '$soNum'";
    $queryPurOffer=mysqli_query($link,$sqlPurOffer)or die("ERROR_SNSC : 01");
    $manufacturInv=mysqli_fetch_assoc($queryPurOffer);
    $poId=$manufacturInv['poId'];
    $sqlSupplierOrder="SELECT `SOId` FROM `supplierorder` WHERE `custPOId` = $poId";
    $querySupplierOrderData=mysqli_query($link,$sqlSupplierOrder)or die("ERROR_SNSC : 01");
    $supplierOrderData=mysqli_fetch_assoc($querySupplierOrderData);
    $SOId=$supplierOrderData['SOId'];


 $quantity=0;
     $col2=0;
$col3=0;
$total=0;


    $sqlSupplierInoiceData="SELECT `OIId`,`ItemRowId`,`qty`,`price`,`status`,`SOIdRef`,`soType`,`OIRef` FROM `supporderitems` WHERE `SOIdRef` = $SOId";
    $querySupplierInoiceData=mysqli_query($link,$sqlSupplierInoiceData)or die("ERROR_SNSC : 02");
    while($supplierInoiceGetData=mysqli_fetch_assoc($querySupplierInoiceData)){
     // Get Supplier Order Items Countant
     $itemId=$supplierInoiceGetData['ItemRowId'];
     $orderType=$supplierInoiceGetData['soType'];
     if($orderType == "Automatic"){
      $sqlOrderItems="SELECT `doorspecs` FROM `autodoorsoffer` WHERE `id` = $itemId";
      $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
      $itemsData=mysqli_fetch_assoc($queryOrderItemsData);
      $items=$itemsData['doorspecs'];
     }
     else if($orderType == "Doors" ){
      $sqlOrderItems="SELECT `itemname` FROM `itemoffer` WHERE `id` = $itemId";
      $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
      $itemsData=mysqli_fetch_assoc($queryOrderItemsData);
      $items=$itemsData['itemname'];
     }
     $sqlSupplierInvoiceDetiles="SELECT `supplierInvoiceCount`,`supplierInvoiceUnitPrice`,`supplierInvoiceTotalItems` FROM `supplierInvoiceData`
     WHERE `supplierInvoiceNumber` = $orderId AND `ItemRowId` = $itemId";
     // Get Supplier Name
     $querySupplierInvoiceDetilesData=mysqli_query($link,$sqlSupplierInvoiceDetiles)or die("ERROR_SNSC : 01");
     if(mysqli_num_rows($querySupplierInvoiceDetilesData) > 0){
      $supplierInvoiceDetilesData=mysqli_fetch_assoc($querySupplierInvoiceDetilesData);        
     $quantity=$supplierInvoiceDetilesData['supplierInvoiceCount'];
     $col2=$supplierInvoiceDetilesData['supplierInvoiceUnitPrice'];
     $col3=$supplierInvoiceDetilesData['supplierInvoiceTotalItems'];
     $total+=$col3;
     }
     



         
     echo"<tr>
      <td>$items</td>
      <td>$quantity</td>
      <td>".number_format(($col2), 2)."</td>
      <td>".number_format(($col3), 2)."</td>
     </tr>";
    }
   ?>
  </tbody>
  <tfoot>
   <th></th>
   <th></th>
   <th></th>
   <th><?php echo $total;?>  </th>
     
  </tfoot>
 </table>
</div>
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
    include_once("connection.php");
    $invoiceId=$_POST['rowId'];
    $sqlSupplierInvoiceDetiles="SELECT `ItemRowId`,`supplierInvoiceCount`,`supplierInvoiceUnitPrice`,`supplierInvoiceTotalItems`  FROM `supplierInvoiceData`
    WHERE `supplierInvoiceNumber`= $invoiceId";
    // Get Supplier Name
    $querySupplierInvoiceDetilesData=mysqli_query($link,$sqlSupplierInvoiceDetiles)or die("ERROR_SNSC : 01");
    if(mysqli_num_rows($querySupplierInvoiceDetilesData) > 0)
    {
     while($supplierInvoiceDetilesData=mysqli_fetch_assoc($querySupplierInvoiceDetilesData))
     {
      $sqlItemName="SELECT `descriptionname` FROM `stockitems` WHERE `description` = $supplierInvoiceDetilesData[ItemRowId]";
      $queryItemName=mysqli_query($link,$sqlItemName)or die("ERROR_SNSC : 01");
      $resultItemName=mysqli_fetch_assoc($queryItemName);
      $items=$resultItemName['descriptionname'];
      $quantity=$supplierInvoiceDetilesData['supplierInvoiceCount'];
      $col2=$supplierInvoiceDetilesData['supplierInvoiceUnitPrice'];
      $col3=$supplierInvoiceDetilesData['supplierInvoiceTotalItems'];
      echo"<tr>
       <td>$items</td>
       <td>$quantity</td>
       <td>".number_format(($col2), 2)."</td>
       <td>".number_format(($col3), 2)."</td>
      </tr>";
     }
    }
   ?>
  </tbody>
 </table>
</div>
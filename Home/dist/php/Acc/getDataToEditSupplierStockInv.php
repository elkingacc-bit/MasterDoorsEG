<div class="table-responsive-lg">
 <table class="table table-sm">
  <thead class="text-center">
   <th>Items</th>
   <th>Quantity</th>
   <th>Unit Price</th>
   <th>Total</th>
  </thead>
  <tbody>
   <?php
    include_once("../connection.php");
    $sublierDataRowId=$_POST['rRowId'];
    $sublierTableId=$_POST['tableId'];
    
    $sqlAccountantCodeData="SELECT `supplierInvoiceNumber`,`ItemRowId`,`supplierInvoiceCount`,`supplierInvoiceUnitPrice`,`supplierInvoiceTotalItems` 
    FROM `supplierInvoiceData` WHERE `supplierInvoiceDataId` = $sublierDataRowId ";  
    $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
    $SIData=mysqli_fetch_assoc($queryAccountantCodeData);
    $searchItemStockName=$SIData['ItemRowId'];
    $getItem2="SELECT `descriptionname` FROM `stockitems` WHERE `description` = $searchItemStockName";
    $queryItem2=mysqli_query($link,$getItem2)or die("ERROR :01-AIC_AIDL_S");
    $resIteme2=mysqli_fetch_assoc($queryItem2);
    
    echo"<tr>
     <td><input type='text' id='oldItems' class='form-control' value='$resIteme2[descriptionname]' readonly></td>
     <td><input type='number' id='oldQun' class='form-control' value='$SIData[supplierInvoiceCount]' readonly></td>
     <td><input type='number' id='oldPrice' class='form-control' value='$SIData[supplierInvoiceUnitPrice]' readonly></td>
     <td><input type='number' id='oldTotal' class='form-control' value='$SIData[supplierInvoiceTotalItems]' readonly></td>
    </tr>
    <tr>
     <td><input type='text' id='newItems' class='form-control' value='$resIteme2[descriptionname]' readonly></td>
     <td><input type='number' id='newQun' class='form-control' value='$SIData[supplierInvoiceCount]'></td>
     <td><input type='number' id='newPrice' class='form-control' value='$SIData[supplierInvoiceUnitPrice]'></td>
     <td><input type='number' id='newTotal' class='form-control' value='$SIData[supplierInvoiceTotalItems]' readonly></td>
    </tr>";
   ?>
  </tbody>
 </table>
</div>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $rowid=$_POST['rRowId'];
 $sqlDataEdit="SELECT `suppliersInvoiceNumber`,`suppliersInvoiceDate`, `supplierCode`,
 `suppliersInvoiceSupTotal`, `suppliersInvoiceDiscount`, `suppliersInvoiceVat`, `suppliersInvoiceTotal`, `paidAmount`, `paidType`, `paiedStuts` 
 FROM `supplierInvoice` WHERE `suppliersInvoiceId` = $rowid";
 $queryDataEdit=mysqli_query($link,$sqlDataEdit);
 $resultDataEdit=mysqli_fetch_assoc($queryDataEdit);
 $searchCode=$resultDataEdit['supplierCode'];
 include("getAccountantName.php");
 $vatAmount=$resultDataEdit['suppliersInvoiceVat'];
 if($vatAmount > 0){$vatOption="<option value='1'>VAT</option>";}
 else{$vatOption="<option value='2'>NOT VAT</option>";}
?>
<div class="table-responsive-lg">
 <table class="table table-sm table-borderless">
  <thead class="text-center">
   <th>Invoice Number</th>
   <th>Date</th>
   <th>Supplier</th>
   <th>Amount</th>
   <th>VAT</th>
   <th>Total</th>
   <th>Action</th>
  </thead>
  <tbody>
   <td><input type="text" class="form-control" id="invoiceNumber" value="<?php echo $resultDataEdit['suppliersInvoiceNumber'];?>" readonly></td>
   <td><input type="date" class="form-control" id="invoiceDate" value="<?php echo $resultDataEdit['suppliersInvoiceDate'];?>" readonly></td>
   <td><select class="form-control" id="suppliers" disabled><option value="<?php echo $searchCode ?>"><?php echo $name ?></option></select></td> 
   <td><input type="number" class="form-control" id="invoiceSuptotal"  value="<?php echo $resultDataEdit['suppliersInvoiceSupTotal'];?>" readonly></td>
   <td><select class="form-control" id="vatType" disabled><?php echo $vatOption; ?></select>
   <input id="invoiceVat" value="<?php echo $vatAmount; ?>" style="display: none;"></td>
   <td><input type="number" class="form-control" id="invoiceTotal" value="<?php echo $resultDataEdit['suppliersInvoiceTotal'];?>" readonly></td>
   <td>
    <button class="btn btn-success w-100" id="saveInvoice">Save</button>
    <button class="btn btn-info btn-sm w-100" id="AddItems">Add</button>
    <button class="btn btn-info w-100" id="closePage">Back</button>
   </td>
  </tbody>
 </table>
 <h5 style="float:right;" class="reminingText"> Remaining : <span class="invRem" style="color: red;"></span> </h5>
</div>
<input id="invoiceId" value="<?php echo $rowid ?>" style="display: none">
<input id="itemsValue" style="display: none">
<div class="invoiceData"></div>
<div class="alert msg" role="alert"></div>
<!-- Modal -->
<div class="modal fade" id="supplierInvoiceModal" aria-hidden="true" aria-labelledby="supplierModalData" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title" id="supplierModalData">Add Items To Invoice</h5>
    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   </div>
   <div class="modal-body">
    <table class="table table-sm">
     <thead class="text-center">
      <th>Items</th>
      <th>Quantity</th>
      <th>Unit Price</th>
      <th>SupTotal</th>
      <th>Save</th>
     </thead>
     <tbody>
      <td>
            <input type="text" id="chooseItems" name="chooseItems" class="form-control" autocomplete="off" list="items">
        <datalist id="items"></datalist>    

    </td> 
      <td><input type="number" class="form-control" id="invoiceQyan"></td>
      <td><input type="number" class="form-control" id="unitPrice"></td>
      <td><input type="text" class="form-control" id="supTotal"></td>
      <td><button class="btn btn-success" id="saveItems">Save</button></td>
     </tbody>
    </table>
   </div>
   <div class="modal-footer msg"></div>
  </div>
 </div>
</div>
<script src="dist/js/pages/accountant/editToBuyStock.js"></script>
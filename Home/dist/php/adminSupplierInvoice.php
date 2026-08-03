<div class="table-responsive-lg">
 <table class='table table-dark table-bordered table-striped w-100 tableAllSupplierInvEdit'>
  <thead>
   <th>Date</th>
   <th>Number</th>
   <th>Supplier</th>
   <th>Amount</th>
   <th>Vat</th>
   <th>Total</th>
   <th>Edit</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $sqlDataEdit="SELECT `suppliersInvoiceId`,`suppliersInvoiceNumber`,`supplierOrderNum`,`suppliersInvoiceDate`,`supplierCode`,`suppliersInvoiceType`,
    `suppliersInvoiceSupTotal`,`suppliersInvoiceDiscount`,`suppliersInvoiceVat`,`suppliersInvoiceTotal`,`paidAmount`,`paidType`,`paiedStuts` FROM `supplierInvoice`
    WHERE `paiedStuts` = 3";
    $queryDataEdit=mysqli_query($link,$sqlDataEdit);
    WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
     $searchCode=$resultDataEdit['supplierCode'];
     include("getAccountantName.php");
     echo"<tr>
      <input class='tableName' value='supplierInvoice' style='display: none;'> 
      <input class='choosedName' value='Purchases Invoice' style='display: none;'>
      <td>$resultDataEdit[suppliersInvoiceDate]</td>
      <td>$resultDataEdit[suppliersInvoiceNumber]</td>
      <td>$name</td>
      <td>$resultDataEdit[suppliersInvoiceSupTotal]</td>
      <td>$resultDataEdit[suppliersInvoiceVat]</td>
      <td>$resultDataEdit[suppliersInvoiceTotal]</td> 
      <td><button type='button' class='btn btn-link suplierInvEdit' data-toggle='modal' data-target='#accountantCodeModal2' value='$resultDataEdit[suppliersInvoiceId]'>
       <i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
      </button></td> 
     </tr>";
    }     
   ?>
  </tbody>
 </table>
 <!-- Modal -->
 <div class="modal" id="accountantCodeModal2" tabindex="-1" aria-labelledby="accountantCodeModal2" aria-hidden="true">
  <div class="modal-dialog modal-xl">
   <div class="modal-content">
    <div class="modal-header">
     <h5 class="modal-title" id="accountantCodeModal2">Modal title</h5>
     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body dataEdit2"></div>
    <div class="modal-footer"></div>
   </div>
  </div>
 </div>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var table = $('.tableAllSupplierInvEdit').DataTable({
   fixedHeader: true,
   scrollY:'65vh',
   scrollX: true,
   scrollCollapse: true,
   order:[[0, "desc"]],
   paging: false, 
  });
  // Edit
  $(".suplierInvEdit").click(function(){
   var editRowId =$(this).val();
   $.ajax({
    url:"dist/php/getSuplierInvDataToEdit.php",
    type:"POST",
    data:{rowId:editRowId},
    success: function(doneEdit){
     $(".dataEdit2").html(doneEdit);      
    }  
   });
  });
 }); 
</script>
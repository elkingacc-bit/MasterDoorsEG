<table class='table table-sm w-100 table-borderless'>
 <thead>
  <th class='col-sm-2 text-center'>Date</th>
  <th class='col-sm-2 text-center'>Invoice Num</th>
  <th class='col-sm-2 text-center'>supplier</th>
  <th class='col-sm-2 text-center'>Amout</th>
  <th class='col-sm-2 text-center'>VAT</th>
  <th class='col-sm-2 text-center'>Total</th>
 </thead>
 <tbody>
  <?php
   date_default_timezone_set("Africa/Cairo");
   include_once("../connection.php");
   $rowid=$_POST['rowId'];
   $sqlDataEdit="SELECT `suppliersInvoiceId`,`suppliersInvoiceNumber`,`suppliersInvoiceDate`, `supplierCode`, `suppliersInvoiceType`,`suppliersInvoiceSupTotal`,
    `suppliersInvoiceVat`,`suppliersInvoiceTotal` FROM `supplierInvoice` WHERE `suppliersInvoiceId` = $rowid";
   $queryDataEdit=mysqli_query($link,$sqlDataEdit);
   $resultDataEdit=mysqli_fetch_assoc($queryDataEdit);
   $searchCode=$resultDataEdit['supplierCode'];
   include("getAccountantName.php");
   echo"<tr class='bg-info'>
    <td><input type='date' class='form-control' id='oldDate' value='$resultDataEdit[suppliersInvoiceDate]' readonly></td>
    <td><input type='text' class='form-control' id='oldNumber' value='$resultDataEdit[suppliersInvoiceNumber]' readonly></td>
    <td><select class='form-control' disabled><option value='$searchCode'>$name</option></select></td>
    <td><input type='number' class='form-control' id='oldAmount' value='$resultDataEdit[suppliersInvoiceSupTotal]' readonly></td>
    <td><input type='number' class='form-control' id='oldVat' value='$resultDataEdit[suppliersInvoiceVat]' readonly></td>
    <td><input type='number' class='form-control' id='oldTotal' value='$resultDataEdit[suppliersInvoiceTotal]' readonly></td>
   </tr>";
   $sqlInvoiceItems="SELECT `supplierInvoiceDataId`,`supplierInvoiceNumber`,`ItemRowId`,`supplierInvoiceCount`,`supplierInvoiceUnitPrice`,`supplierInvoiceTotalItems` 
   FROM `supplierInvoiceData` WHERE `supplierInvoiceNumber` = $rowid";
   $queryInvoiceItems=mysqli_query($link,$sqlInvoiceItems);
   if(mysqli_num_rows($queryInvoiceItems) == 0){
    echo "</tbody>
     </table>
     <center>
     <button class='btn btn-success' id='delInv' value='$rowid'>Delete</button>
    <center>";   
   }
   else{
    echo"<tr>
     <td><input type='date' class='form-control' id='newDate' value='$resultDataEdit[suppliersInvoiceDate]'></td>
     <td><input type='text' class='form-control' id='newNumber' value='$resultDataEdit[suppliersInvoiceNumber]'></td>
      <td><select class='form-control' disabled><option value='$searchCode'>$name</option></select></td>
     <td><input type='number' class='form-control' id='newAmount' value='$resultDataEdit[suppliersInvoiceSupTotal]'></td>
     <td><input type='number' class='form-control' id='newVat' value='$resultDataEdit[suppliersInvoiceVat]'></td>
     <td><input type='number' class='form-control' id='newTotal' readonly value='$resultDataEdit[suppliersInvoiceTotal]'></td>
    </tr>
    </tbody>
    </table>
     <center>
     <button class='btn btn-success' id='saveEditSubSuplierInv' value='$rowid'>Save</button>
    <center>
     <h5 class='text-left'>Contant Items</h5>
     <table class='table table-sm table-bordered'>
      <thead class='text-center bg-secondary'>
       <th>Items</th>
       <th>Quantity</th>
       <th>Unit Price</th>
       <th>Total</th>
      </thead>
      <tbody>";   
      while($resultInvoiceItems=mysqli_fetch_assoc($queryInvoiceItems)){
      $subItemsName=$resultInvoiceItems['ItemRowId'];
      $getAllItemCode2="SELECT `descriptionname` FROM `stockitems` WHERE `description` = $subItemsName";
      $queryAllItemCode2=mysqli_query($link,$getAllItemCode2)or die("ERROR :01-AIC_AIDL_S");
      $resAllItemCode2=mysqli_fetch_assoc($queryAllItemCode2);
      echo"<tr>
       <td>$resAllItemCode2[descriptionname]</td>
       <td>$resultInvoiceItems[supplierInvoiceCount]</td>
       <td>$resultInvoiceItems[supplierInvoiceUnitPrice]</td>
       <td>$resultInvoiceItems[supplierInvoiceTotalItems]</td>
      </tr>";
     }        
     echo"</tbody>
     </table>
     <center>
     <button class='btn btn-success' id='editSubSuplierInv' value='$rowid'>Edit Items</button>
    <center>";
   }

?>

<script type="text/javascript">
 $(document).ready(function(){

   $("#editSubSuplierInv").click(function(){
   //$("#accountantCodeModal2").modal('toggle');
   var supInvRowId=$(this).val();
   $.ajax({
    url:'dist/php/Acc/editSupplierStockInv.php',
    type:"POST",
    data:{rRowId:supInvRowId},
    success: function(supInvEditData){
     $(".dataEdit2").html('');
     $(".dataEdit2").html(supInvEditData);
     //alert("Data Delete");
    }
   });
  });


 $("#delInv").click(function(){
   $("#accountantCodeModal2").modal('toggle');
   var supInvRowId=$(this).val();
   $.ajax({
    url:'dist/php/Acc/saveDelSupplierStockInv.php',
    type:"POST",
    data:{rRowId:supInvRowId},
    success: function(supInvDeleteData){
     alert("Data Delete");
    }
   });
  });


 $("#saveEditSubSuplierInv").click(function(){
  $("#accountantCodeModal2").modal('toggle');
  var supInvRowId=$(this).val();  
var oDate=$("#oldDate").val();
var oNumber=$("#oldNumber").val();
var oAmount=$("#oldAmount").val();
var oVat=$("#oldVat").val();
var oTotal=$("#oldTotal").val();  
var nDate=$("#newDate").val();
var nNumber=$("#newNumber").val();
var nAmount=$("#newAmount").val();
var nVat=$("#newVat").val();
var nTotal=$("#newTotal").val();
   $.ajax({
    url:'dist/php/Acc/saveEditSupplierStockInv.php',
    type:"POST",
    data:{rRowId:supInvRowId, roDate:oDate, roNumber:oNumber, roAmount:oAmount, roVat:oVat, roTotal:oTotal, rnDate:nDate, rnNumber:nNumber, rnAmount:nAmount, rnVat:nVat, rnTotal:nTotal
    },
    success: function(supInvEditData){
     alert("Edit Data Saved");
    }
   });
  });

}); 
</script>
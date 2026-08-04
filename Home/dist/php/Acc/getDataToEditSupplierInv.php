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
    echo"<input id='invoiceNumber' value='$SIData[supplierInvoiceNumber]' hidden>";
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
     <td>

        <input type='text' id='choosenewItems' name='choosenewItems' class='form-control' autocomplete='off' list='newItems'>
        <datalist id='newItems'></datalist>    


</td>
     <td><input type='number' id='newQun' class='form-control' value='$SIData[supplierInvoiceCount]'></td>
     <td><input type='number' id='newPrice' class='form-control' value='$SIData[supplierInvoiceUnitPrice]'></td>
     <td><input type='number' id='newTotal' class='form-control' value='$SIData[supplierInvoiceTotalItems]' readonly></td>
    </tr>";
   ?>
  </tbody>
 </table>
 <button type="button" class="btn btn-success" id="saveEditItemsInvoice" value="<?php echo $sublierDataRowId; ?>">Save Edit</button>
</div>
<script type="text/javascript">
 $(document).ready(function(){

var invoiceNumber =$("#invoiceNumber").val();
   $.ajax({
    url:'dist/php/Acc/itemsToBuyStock.php',
    type:"POST",
    data:{invId:invoiceNumber},
    success: function(newItemsInvData){
     $("#newItems").html(newItemsInvData);
    }
   });


   //$("#newItems").load("dist/php/Acc/itemsToBuyStock.php");


  $("#saveEditItemsInvoice").click(function(){
   $("#supplierInvoiceItemsModal").modal('toggle');
   var supInvRowId=$(this).val();  
   var oItems=$("#oldItems").val();
   var oQun=$("#oldQun").val();
   var oPrice=$("#oldPrice").val();
   var oTotal=$("#oldTotal").val();


   var data = {};
   $("#newItems option").each(function(i,el) {  
    data[$(el).data("value")] = $(el).val();
   });
   console.log(data, $("#newItems option").val());
   var itemCode = $("#choosenewItems").val();
   var nItems = $('#newItems [value="' + itemCode + '"]').data('value');
   var itemsChosenValideate = $('#newItems [value="' + itemCode + '"]');  
   if(itemsChosenValideate.length <= 0)
   {
    alert('Please Choose Items name form the list');
    $("#choosenewItems").css("border-color","red");
    setTimeout(function(){
     $("#choosenewItems").css("border-color","#EBEBEB");               
     $("#choosenewItems").val(''); 
     $("#choosenewItems").focus();             
    }, 1500);
   }



   //var nItems=$("#newItems").val();
   var nQun=$("#newQun").val();
   var nPrice=$("#newPrice").val();
   var nTotal=$("#newTotal").val();
   $.ajax({
    url:'dist/php/Acc/saveEditSupplierInvItems.php',
    type:"POST",
    data:{rRowId:supInvRowId, roItems:oItems, roQun:oQun, roPrice:oPrice, roTotal:oTotal, rnItems:nItems, rnQun:nQun, rnPrice:nPrice, rnTotal:nTotal},
    success: function(supInvEditData){
     alert("Edit Data Saved");
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
   var remining = (Number(totalInv)-Number(totalRef));
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


  $("#newQun").keyup(function(){
   var qyt = $("#newQun").val();
   var unitPrice = $('#newPrice').val();
   var sup=(Number(qyt)*Number(unitPrice));
   $("#newTotal").val(sup);
  });
  $("#newPrice").keyup(function(){
   var qyt2 = $("#newQun").val();
   var unitPrice2 = $('#newPrice').val();
   var sup2=(Number(qyt2)*Number(unitPrice2));
   $("#newTotal").val(sup2);
  });
 

$("#newQun").change(function(){
   var qyt = $("#newQun").val();
   var unitPrice = $('#newPrice').val();
   var sup=(Number(qyt)*Number(unitPrice));
   $("#newTotal").val(sup);
  });
  $("#newPrice").change(function(){
   var qyt2 = $("#newQun").val();
   var unitPrice2 = $('#newPrice').val();
   var sup2=(Number(qyt2)*Number(unitPrice2));
   $("#newTotal").val(sup2);
  });

 });
</script>
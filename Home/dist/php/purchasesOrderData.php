<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $customerPo=(int)$_POST['poId'];
 $supName= "";
 $supCode= 0;
 $sqlSuplierOrder="SELECT `SuppCode` FROM `supplierorder` WHERE `custPOId` = $customerPo";
 $querySuplierOrder=mysqli_query($link,$sqlSuplierOrder)or die("ERROR_SNSC : 01");
 if(mysqli_num_rows($querySuplierOrder) > 0){
  $getSuplierOrder=mysqli_fetch_assoc($querySuplierOrder);
  $supplier=$getSuplierOrder['SuppCode'];
  $sqlGetSupplier="SELECT `suppliername`, `suppliercode` FROM `allsuppliers` WHERE `suppliercode` = $supplier";
  $queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :01-AU_AU_S".mysqli_error($link));
  $resGetSupplier = mysqli_fetch_assoc($queryGetSupplier);
  $supName= $resGetSupplier['suppliername'];
  $supCode= $resGetSupplier['suppliercode'];
  $stutsVal = 3;  
 }
 else{
     $stutsVal = 4;
 }
 $sqlOrderNum="SELECT `purchasesorderid` FROM `purchasesorder`";
 $queryOrderNum=mysqli_query($link,$sqlOrderNum)or die("ERROR_SNSC : 01");
 $lastumber=mysqli_num_rows($queryOrderNum);
 $nextNumber=($lastumber + 1);
 $newNumber="CMS_Pur_$supName-".$nextNumber;
 $sqlGetJopId="SELECT `orderType`,`PoNum`,`custCode`,`jobidref` FROM `customerpo` WHERE  `poId` = $customerPo";
 $queryGetJopId=mysqli_query($link,$sqlGetJopId)or die("ERROR_SNSC : 01");
 $getJopId=mysqli_fetch_assoc($queryGetJopId);
 $jopId=$getJopId['jobidref'];
 $cusId=$getJopId['custCode'];
 $sqlProjectName="SELECT `projectName` FROM `job` WHERE `jobId` = $jopId";
 $quaryProjectName=mysqli_query($link,$sqlProjectName)or die("ERROR LOA_S:01");
 $projectName=mysqli_fetch_assoc($quaryProjectName);
 $sqlCheckCustN="SELECT `customername` FROM `customers` WHERE `customercode` = $cusId";
 $queryCheckCustN=mysqli_query($link,$sqlCheckCustN)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
 $resCheckCustN=mysqli_fetch_assoc($queryCheckCustN);
 $orderType=$getJopId['orderType'];
 if($orderType == "Automatic"){$myTable= "autodoorsoffer";}
 else if($orderType == "Doors" ){$myTable= "itemoffer";}
 else if($orderType == "Stock" ){$myTable= "stockoffers";}
 else if($orderType == "Maintenance" ){$myTable= "maintoffers";}
 echo "<input id='titelData' value='Supplier Offer To $resCheckCustN[customername] /  $projectName[projectName]' style='display: none;'>";
 echo "<input id='SupStuts' value='$stutsVal'  style='display: none;'>";
?>

<form id="supplierOfferItems" action="">
 <center>
  <div class="table-responsive-lg text-center">
   <table class="table table-sm w-75 table-borderless">
    <thead class='bg-info'>
     <th></th>
     <th>Supplier</th>
     <th>Order Num</th>
     <th>Order Date</th>
     <th style="display: none;">jobref</th>
     <th>Amount</th>
     <th>VAT</th>
    </thead>
    <tbody>
     <td><button class="btn" id="changeRef"><i class="fas fa-history" style="color: red;"></i></button></td>
     <td><select class="form-control" id="suppliers" name="suppliersName">
      <option value='<?php echo $supCode; ?>'> <?php echo $supName; ?> </option>;
     </select></td>
     <td><input type="text" class="form-control" name="offerNumber" value="<?php echo $newNumber;?>" ></td>
     <td><input type="date" class="form-control" name="offerDate" id="dateOfer"></td>
     <td style="display: none;"><input type="text" class="form-control" name="jopId" value=" <?php echo $jopId;?>" readonly></td>   
     <td><input type="number" class="form-control" id="invoiceAmount" name="totalOfferAmount"></td>
     <td><select class="form-control" id="invoiceTypy"><option value="">Coose</option><option value="VAT">VAT</option><option value="No">No</option>  </select>  </td>
    </tbody>
   </table>  
   <hr>
   <div class="offerItems">
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
       $itemsCount=0;
       // Automatic Type
       if($orderType == "Automatic"){
        $sqlOrderItems="SELECT `id`,`doortype`,`doorspecs`,`doorqty` FROM `autodoorsoffer` WHERE `jobid` = $jopId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        WHILE($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
         $rowCount++;
         $itemsCount+= $itemsData['doorqty'];
         echo "<tr>
          <td style='display: none;'><input type='number' name='itemsRowId$rowCount' value='$itemsData[id]'></td>
          <td class='col-6'>$itemsData[doorspecs]</td>
          <td class='col-2'><input type='number' class='form-control' id='qun$rowCount' value='$itemsData[doorqty]' readonly></td>
          <td class='col-2'><input type='number' class='form-control' id='unitPrice$rowCount'  name='unitPrice$rowCount'></td>
          <td class='col-2'><input type='number' class='form-control' id='totalPrice$rowCount' readonly></td>
         </tr>";
        }
       } 
       //Doors Type
       else if($orderType == "Doors" ){
        $sqlOrderItems="SELECT `id`,`itemtype`,`itemhight`,`itemwidth`,`itemqty` FROM `itemoffer` WHERE `jobref` = $jopId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        WHILE($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
         $rowCount++;
         $itemsCount+=$itemsData['itemqty'];
         echo "<tr><td style='display: none;'><input type='number' name='itemsRowId$rowCount' value='$itemsData[id]'></td>
          <td class='col-6'>$itemsData[itemtype] -w $itemsData[itemwidth] -h $itemsData[itemhight] </td>
          <td class='col-2'><input type='number' class='form-control' id='qun$rowCount' value='$itemsData[itemqty]' readonly></td>
          <td class='col-2'><input type='number' class='form-control' id='unitPrice$rowCount' name='unitPrice$rowCount'></td>
          <td class='col-2'><input type='number' class='form-control' id='totalPrice$rowCount' readonly></td>
         </tr>";      
        }
       }
       // Stock Type
       else if($orderType == "Stock" ){
        $sqlOrderItems="SELECT `id`, `descripcode`,`descripqty`,`descripprice`,`totalprice`,`jobref`,`ref`,`whref` FROM `stockoffers` WHERE `jobref` = $jopId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
         $rowCount++;
         echo"<tr><td style='display: none;'><input type='number' name='itemsRowId$rowCount' value='$itemsData[id]'></td>
          <td class='col-6'>$itemsData[descripcode]</td>
          <td class='col-2'><input type='number' class='form-control' id='qun$rowCount' value='$itemsData[descripqty]' readonly></td>
          <td class='col-2'><input type='number' class='form-control' id='unitPrice$rowCount' name='unitPrice$rowCount'></td>
          <td class='col-2'><input type='number' class='form-control' id='totalPrice$rowCount' readonly></td>
         </tr>";        
        }
       }
      ?>

     </tbody>
    <tfoot>
         <th></th>
          <th style="text-center"><?php echo $itemsCount; ?> </th>
           <th></th>
            <th></th>
    </tfoot>
    </table>
   </div>
  </div>
  <input type="text" id="offerVat" name="offerVat" value="0"  style="display: none;">
  <input type="text" name="customerPoId" value="<?php echo $customerPo ?>" style="display: none;">
  <input type="text" name="offerTableDataName" value="<?php echo $myTable ?>" style="display: none;">
  <!--<button class="btn btn-link" id="showOfferItems" ><i class="fas fa-cart-plus"aria-hidden='true' style='font-size:16px;color:#0275d8'></i>  Offer Items</button>--> 
  <button class="btn btn-success" id="saveInvoice">Save</button>
  <input type="text" id="rowCount" name="itemsRowCount" value="<?php echo $rowCount; ?> " style="display: none;">
  <input type="text" id="totalCount" style="display: none;">  
 </center>
</form>
<script type="text/javascript">
 $(document).ready(function(){
  var supStats = $("#SupStuts").val();
  if(supStats == 4){
   $("#suppliers").load("dist/php/allSuppliers.php");          
  }
  $("#changeRef").click(function(){
   $("#suppliers").load("dist/php/allSuppliers.php");
   return false;
  });

  var dataTitel = $("#titelData").val();
  $(".m-0").html("");
  $(".m-0").html(dataTitel);
  
  $("#invoiceTypy").change(function(){
   var tax = $(this).val();
   if(tax == "VAT"){
    var totalInvoiceT = $("#invoiceAmount").val();
    var totalAmount14=(Number(totalInvoiceT) *.14);
    $("#offerVat").val(totalAmount14);
   }
  });
  $("#invoiceAmount").change(function(){
   var amount = $(this).val();
   var invoiceT = $("#invoiceTypy").val();
   if(invoiceT == "VAT"){
    var totalAmount=(Number(amount) *.14);
    $("#offerVat").val(totalAmount);
   }
  });
  $("#showOfferItems").click(function(){
   var sup = $("#suppliers").val();
   var totalInvoice = $("#invoiceAmount").val();
   var offerDate=$("#dateOfer").val();
   var taxType=$("#invoiceTypy").val(); 
   if(sup == ""){
    alert("Choose Supplier");
   }
   else if(offerDate == ""){
    alert("Offer Date Is Empty");
   }
   else if(totalInvoice == ""){
    alert("Total Offer Is Empty");
   }
   else if(taxType == ""){
    alert("Choose VAT");
   }
   else{
    $(".offerItems").prop('hidden', false);
    $("#showOfferItems").prop('hidden', true);
    $("#saveInvoice").prop('hidden', false);
    $("#invoiceAmount").prop('readonly', true);    
   }
   return false;
  });
  // On Change Fun
  let count = $("#rowCount").val();
  for (let i = 1; i <= count; i++){
   $("#unitPrice"+i).change(function(){
    var up = $("#unitPrice"+i).val();
    var q = $("#qun"+i).val();
    var tAmount =( Number(up) * Number(q));
    $("#totalPrice"+i).val(tAmount);
   });
  }
  // Save Data
  $("#saveInvoice").click(function(){
   var totalInvoice2 = $("#invoiceAmount").val();
   if(totalInvoice2 == ""){
    alert("Total Invoice Is Empty");
   }
   else{
    let totalAmount = 0;
    for (let i2 = 1; i2 <= count; i2++){
     var up2 = $("#unitPrice"+i2).val();
     if(up2 == ""){ 
      alert("Dont Skip Unit Price Empty")
      break;
     }
     else{
      var tAmount2 = $("#totalPrice"+i2).val();
      totalAmount += Number(tAmount2);
      $("#totalCount").val(totalAmount);
     }  
    }
    var totalItems = $("#totalCount").val();
    if(totalItems == 0){
     alert("Items Price Is Empty");
    }
    else{
     if(totalInvoice2 != totalItems){
      alert("Total Items Not Equal Total Invoice");
      return false;
     }
     else if(totalInvoice2 == totalItems ){
      $.ajax({
       url:'dist/php/saveSupplierOffer.php',
       type:"POST",
       data:$('#supplierOfferItems').serialize(),
       success: function(invSuuplierData){
        alert(invSuuplierData);
       }
      });
      return false;
     }
    }
   }
  });
  return false;
 }); 
</script>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $jopId=(int)$_POST['jopId'];
 // Get Project Name
 $sqlProjectName="SELECT `projectName` FROM `job` WHERE `jobId` = $jopId";
 $quaryProjectName=mysqli_query($link,$sqlProjectName)or die("ERROR LOA_S:01");
 $projectName=mysqli_fetch_assoc($quaryProjectName);
 //Get Po Data
 $sqlSalesInoice="SELECT `orderType`,`PoNum`,`poDate`,`custCode`,`poVal`,`POVat`,`dwonpay`,`receivingpay`,`finishpay`,`deleveryDate` FROM `customerpo` 
 WHERE `jobidref` = $jopId";
 $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 02");
 $salesInoiceData=mysqli_fetch_assoc($querySalesInoice);
 $totalInvoice=($salesInoiceData['poVal'] + $salesInoiceData['POVat']);
 // Get Customer Data
 $sqlCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $salesInoiceData[custCode]";
 $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
 $customerData=mysqli_fetch_assoc($quaryCustomer);
 echo "<input id='dDate' value='$salesInoiceData[poDate]' hidden>";
 //Get Invoice Num
 $sqlInvoiceNum="SELECT `salesInvoiceId` FROM `salesInvoice`";
 $quaryInvoiceNum=mysqli_query($link,$sqlInvoiceNum)or die("ERROR_SNSC : 02");
 $invCount=mysqli_num_rows($quaryInvoiceNum);
 $invoiceLastNum= ($invCount + 1);
 $invoiceNum="INV_".$invoiceLastNum;
?>
<h4> Invoice For (<small style="color: blue;"><?php echo $projectName['projectName']?> </small>)</h4>
<center>
 <div class="table-responsive-lg">
  <table class="table table-sm w-75 table-borderless">
   <thead class="text-center">
    <th>Invoice Date</th>
    <th>Invoice Number</th>
    <th>Customer Name</th>
    <th>Back</th>
   </thead>
   <tbody>
    <td><input type="date" class="form-control" id="salesInvoiceDate" value="<?php echo $salesInoiceData['poDate'] ;?>"></td>
    <td><input type="text" class="form-control" id="invNum" value="<?php echo $invoiceNum ;?>"></td>
    <td>
     <select class="form-control" id="supplierAccountName">
      <option value="<?php echo $salesInoiceData['custCode'] ?>"><?php echo $customerData['customername']?></option>
     </select>
    </td>
    <td><button class="btn btn-info" id="backToChoosePo">Back</button></td>
   </tbody>
  </table>
 </div>    
 <hr>
 <div class="table-responsive-lg">
  <table class='text-center table table-sm table-bordered table-striped w-75'>
   <thead class="bg-info">
    <th>Items</th>
    <th>Count</th>
    <th>Unit Price</th>
    <th>Total</th>
   </thead>
   <tbody>
    <?php
     $sqlSalesJop="SELECT `startDate`,`localref`,`customer`,`offerValue`,`jobtype`,`jobreceivables`,`endDate`,`invoice`, `salesman` FROM `job` WHERE `jobId` = $jopId";
     $querySalesJop=mysqli_query($link,$sqlSalesJop)or die("ERROR_SNSC : 02");
     While($jopData=mysqli_fetch_assoc($querySalesJop)){
      $orderType=$jopData['jobtype'];
      // 1- Automatic
      if($orderType == "Automatic"){
       $sqlOrderItems="SELECT `id`,`doortype`,`doorspecs`,`motorspecs`,`doorprice`,`doorqty`,`totalprice`,`jobid`,`ref` FROM `autodoorsoffer` WHERE `jobid` = $jopId";
       $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
       WHILE($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
        echo "<tr>
         <td>$itemsData[doortype]</td>
         <td>$itemsData[doorqty]</td>
         <td>".number_format(($itemsData['doorprice']), 2)."</td>
         <td>".number_format(($itemsData['totalprice']), 2)."</td>
        </tr>";
       }
      }
      // 2- Doors
      else if($orderType == "Doors" ){
       $sqlOrderItems="SELECT `id`,`itemname`,`itemtype`,`msquerprice`,`itemqty`,`totalprice` FROM `itemoffer` WHERE `jobref` = $jopId";
       $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
       WHILE($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
        $rowId=$itemsData['id']; 
        //HW
        $sqlOrderItemsHW="SELECT sum(`totalprice`) as hTotal FROM `offerproperties` WHERE  `ioidref` = $rowId";
        $queryOrderItemsDataHW=mysqli_query($link,$sqlOrderItemsHW)or die("ERROR_SNSC : 01");
        $itemsHW=mysqli_fetch_assoc($queryOrderItemsDataHW);        
        $itemHdCost= ($itemsHW['hTotal']);
        $sumTotalPrice=($itemHdCost + $itemsData['totalprice']);
        $unitPrice=( $sumTotalPrice / $itemsData['itemqty']);
        echo "<tr>
         <td class='text-left'>$itemsData[itemtype]</td>
         <td>$itemsData[itemqty]</td>
         <td>$unitPrice</td>
         <td>$sumTotalPrice</td>
        </tr>";
       }
      }
      // 3- Stock
      else if($orderType == "Stock" ){
       $sqlOrderItems="SELECT `id`, `descripcode`,`descripqty`,`descripprice`,`totalprice`,`jobref`,`ref`,`whref` FROM `stockoffers` WHERE `jobref` = $jopId";
       $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
       while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
        echo"<tr>
         <td>$itemsData[descripcode]</td>
         <td>$itemsData[descripqty]</td>
         <td>".number_format(($itemsData['descripprice']), 2)."</td>
         <td>".number_format(($itemsData['totalprice']), 2)."</td>         
        </tr>";        
       }
      }
      // Maintenance Type  
      else if($orderType == "Maintenance" ){
       $sqlOrderItems="SELECT `type`, `price`, `typeqty`, `totalprice`, `jobid`, `ref`, `purchasesCost` FROM `maintoffers` WHERE `jobid` = $jopId";
       $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
       while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
        echo"<tr>
         <td>$itemsData[type]</td>
         <td>$itemsData[typeqty]</td>
         <td>".number_format(($itemsData['price']), 2)."</td>         
         <td>".number_format(($itemsData['totalprice']), 2)."</td>         
        </tr>";              
       }
      }
     }
    ?>
   </tbody>
   <tfoot class="table-borderless">
    <tr>
     <td colspan="2"></td> 
     <td>Sup Total </td>
     <td><?php echo number_format(($salesInoiceData['poVal']), 2); ?></td> 
    </tr>
    <tr>
     <td colspan="2"></td> 
     <td>VAT</td>
     <td><?php echo number_format(($salesInoiceData['POVat']), 2); ?></td> 
    </tr>
    <tr>
     <td colspan="2"></td> 
     <td>Total</td>
     <td><?php echo number_format(($totalInvoice), 2); ?></td>
    </tr>
   </tfoot>
  </table>
  <hr>
  <button class="btn btn-success" id="saveSalesInvoice" value="<?php echo $jopId; ?>">Save</button>
 </div>
</center>
<script type="text/javascript">
 $(document).ready(function(){
  $("#salesInvoiceDate").change(function(){
   var myDate=$(this).val();
   var poDate=$("#dDate").val();
    /*
    if(poDate > myDate){
     alert("Must Invoice Date After Po Date")
     $('#saveSalesInvoice').prop('disabled', true);
    }
    else{
     $('#saveSalesInvoice').prop('disabled', false);
    }
   */
  });
  $("#saveSalesInvoice").click(function(){
   var jopId =$(this).val();
   var invoiceNum = $("#invNum").val();
   var invoiceDate = $("#salesInvoiceDate").val();
   if(invoiceDate == ''){
    alert("Dont Skip Invoice Date Empty");
   }
   else if(invoiceNum == ''){
    alert("Dont Skip Invoice Number Empty");
   }
   else{
    $.ajax({
     url:'dist/php/Acc/saveNewSalesInvoice.php',
     type:"POST",
     data:{jopRef:jopId,invNumber:invoiceNum,invDate:invoiceDate},
     success: function(invSalesData){
      alert("Data Saved");  
      $(".data_display").load("dist/html/Acc/newSalesInvoice.html");
     }
    });
   }
   return false;
  });
  $("#backToChoosePo").click(function(){
   $(".data_display").load("dist/html/Acc/newSalesInvoice.html");
  });
 });
</script>
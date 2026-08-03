<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <link rel="icon" type="image/png" href="dist/img/logoMarkerSmall.png" sizes="128x128" />
  <title>Master Doors CMS</title>
  <style>
   .watermarked {
    height: 297mm;
    width: 21 cm;
    position: relative;
    border-style: groove;
   }
   .watermarked:after {
    content: "";
    display: block;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0px;
    left: 0px;
    background-image: url("../img/logoMarker.png");
    background-size: 100px 100px;
    background-position: 30px 30px;
    background-repeat: no-repeat;
    opacity: 0.7;
   }   
   td{
    text-align: center;
   }
  </style>
 </head>
 <body>
  <div class="watermarked">
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $orderId=$_GET['orderId'];
    $sqlPurOffer="SELECT `supplierid`,`purchasesOrderNum`,`purchasesOrderDate`,`jobref`,`totalAmout`,`poId` FROM `purchasesorder` WHERE `purchasesorderid` = '$orderId'";
    $queryPurOffer=mysqli_query($link,$sqlPurOffer)or die("ERROR_SNSC : 01");
    $manufacturInv=mysqli_fetch_assoc($queryPurOffer);
    $poId=$manufacturInv['poId'];
    $orderNumber=$manufacturInv['purchasesOrderNum'];
    $orderDate=$manufacturInv['purchasesOrderDate'];
    $tCode=$manufacturInv['supplierid'];
    $sqlFirstLevelData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $tCode";
    $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
    $firstLevel=mysqli_fetch_assoc($queryFirstLevelData);
    $manufacturName=$firstLevel['suppliername'];
    $sqlSupplierOrder="SELECT `SOId` FROM `supplierorder` WHERE `custPOId` = $poId";
    $querySupplierOrderData=mysqli_query($link,$sqlSupplierOrder)or die("ERROR_SNSC : 01");
    $supplierOrderData=mysqli_fetch_assoc($querySupplierOrderData);
    $SOId=$supplierOrderData['SOId'];
    $jopId2= $manufacturInv['jobref'];
    $sqlProjectName2="SELECT `projectName` FROM `job` WHERE `jobId` = $jopId2";
    $queryProjectName2=mysqli_query($link,$sqlProjectName2)or die("ERROR_SNSC : 01");
    $projectData2=mysqli_fetch_assoc($queryProjectName2);
    $projectName=$projectData2['projectName'];
    $sn=0;
    $desc=0;
    $width=0;
    $height=0;
    $MCOST=0;
    $qun=0;
    $uprice=0;
    $supTotal=0;
    $total=0;
    $doorQun=0;
    $sqlSupplierInoiceData3="SELECT `soType` FROM `supporderitems` WHERE `SOIdRef` = $SOId";
    $querySupplierInoiceData3=mysqli_query($link,$sqlSupplierInoiceData3)or die("ERROR_SNSC : 02");
    $supplierInoiceGetData3=mysqli_fetch_assoc($querySupplierInoiceData3);
    $tableType=$supplierInoiceGetData3['soType'];
   ?>
   <center><h3 style="padding-top: 5vh;align: center;">Manufacturing Order :- <small style="color: blue;"> <?php echo $orderNumber ?></small></h3></center>
   <h5 style="padding-top: 5vh;padding-left:30px;">Order Date :- <small style="color: blue;"> <?php echo $orderDate ?></small> </h5>
   <h5 style="padding-left:30px">Manufacturing Vendor :- <small style="color: blue;"> <?php echo $manufacturName ?></small> </h5>
   <h5 style="padding-left:30px">Manufacturing For  :- <small style="color: blue;"> <?php echo $projectName ?></small> </h5>
   <hr>
   <center><h4 style="padding-top:2vh;color: red;"><u>Manufacturing Order Details</u> </h4></center>
   <br>
   <table class="table table-bordered">
	  <?php
     if($tableType == "Automatic"){
      echo"<thead>
	     <th>SN</th>
	     <th>Description</th>
	     <th>Quantity</th>
	     <th>U.Price</th>
	     <th>Total</th>
	     </thead>
      <tbody>";
     }
     else if($tableType == "Doors" ){
      echo"<thead>
	     <th>SN</th>
	     <th>Description</th>
	     <th>Width</th>
	     <th>Height</th>
       <th>MS</th>
	     <th>M COST</th>
	     <th>Quantity</th>
	     <th>U.Price</th>
	     <th>Total</th>
	     </thead>
      <tbody>";
     }
     // Get Offer Data
     $sqlSupplierInoiceData="SELECT `OIId`,`ItemRowId`,`qty`,`price`,`status`,`SOIdRef`,`soType`,`OIRef` FROM `supporderitems` WHERE `SOIdRef` = $SOId";
     $querySupplierInoiceData=mysqli_query($link,$sqlSupplierInoiceData)or die("ERROR_SNSC : 02");
     while($supplierInoiceGetData=mysqli_fetch_assoc($querySupplierInoiceData)){
      $sn++;
      // Get Supplier Order Items Countant
      $itemId=$supplierInoiceGetData['ItemRowId'];
      $orderType=$supplierInoiceGetData['soType'];
      if($orderType == "Automatic"){
       $sqlOrderItems="SELECT `doortype`,`doorspecs`,`motorspecs`,`doorprice`,`doorqty`,`totalprice` FROM `autodoorsoffer` WHERE `id` = $itemId";
       $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
       $itemsData=mysqli_fetch_assoc($queryOrderItemsData);
       $desc=$itemsData['doortype']." / ".$itemsData['doorspecs']." / ".$itemsData['motorspecs'];
       $qun=$itemsData['doorqty'];
       $uprice=$itemsData['doorprice'];
       $supTotal=$itemsData['totalprice'];
       
       echo"<tr>
        <td>$sn</td>
        <td>$desc</td>
        <td>$qun</td>
        <td>".number_format(($uprice), 2)."</td>
        <td>".number_format(($supTotal), 2)."</td>
       </tr>";
      }
      else if($orderType == "Doors" ){
       $sqlOrderItems="SELECT `itemname`,`itemhight`,`itemwidth`,`itemm2`,`itemqty`,`msquerprice`,`totalprice` FROM `itemoffer` WHERE `id` = $itemId";
       $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
       $itemsData=mysqli_fetch_assoc($queryOrderItemsData);


       
       $desc=$itemsData['itemname'];
       $width=$itemsData['itemwidth'];
       $height=$itemsData['itemhight'];
       $MCOST=$itemsData['msquerprice'];
       if($itemsData['itemm2'] < 1 ){
        $msq=1;
       }
       else{
        $msq=$itemsData['itemm2'];
       }
       $qun=$itemsData['itemqty'];
       $uprice=($msq * $MCOST);
       $supTotal=($qun * $uprice);
      
       echo"<tr>
        <td>$sn</td>
        <td>$desc</td>
        <td>$width</td>
        <td>$height</td>
        <td>$msq</td>
        <td>".number_format(($MCOST), 2)."</td>
        <td>$qun</td>
        <td>".number_format(($uprice), 2)."</td>
        <td>".number_format(($supTotal), 2)."</td>
       </tr>";
      }
      $total+=$supTotal;
      $doorQun+=$qun;
     }
    ?>
    </tbody>
    <tfoot>
     <?php
           if($tableType == "Automatic"){
      echo"<thead>
       <th></th>
       <th>Total</th>
       <th>$doorQun</th>
       <th></th>
       <th>".number_format(($total), 2)."</th>
       </thead>
      <tbody>";
     }
     else if($tableType == "Doors" ){
      echo"<thead>
       <th></th>
       <th>Total</th>
       <th></th>
       <th></th>
       <th></th>
       <th></th>
       <th>$doorQun</th>
       <th></th>
       <th>".number_format(($total), 2)."</th>
       </thead>
      <tbody>";
     }
     ?>
    </tfoot>
   </table>
  </div>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 </body>
</html>

<?php
/*

$sqlSupplierStockInv="SELECT `suppliersInvoiceId` FROM `supplierInvoice` WHERE `supplierOrderNum` = '$orderNumber' AND `supplierCode` = $tCode";
$querySupplierStockInv=mysqli_query($link,$sqlSupplierStockInv)or die("ERROR_SNSC : 01");
if(mysqli_num_rows($querySupplierStockInv) > 0){


$supplierStockInv=mysqli_fetch_assoc($querySupplierStockInv);
$invoiceId=$supplierStockInv['suppliersInvoiceId'];



$sqlSupplierInvoiceDetiles="SELECT `supplierInvoiceCount`,`supplierInvoiceUnitPrice`,`supplierInvoiceTotalItems` FROM `supplierInvoiceData`
WHERE `supplierInvoiceNumber` = $invoiceId AND `ItemRowId` = $itemId";
// Get Supplier Name
$querySupplierInvoiceDetilesData=mysqli_query($link,$sqlSupplierInvoiceDetiles)or die("ERROR_SNSC : 01");
if(mysqli_num_rows($querySupplierInvoiceDetilesData) > 0){

}
}  
$supplierInvoiceDetilesData=mysqli_fetch_assoc($querySupplierInvoiceDetilesData);        
$quantity=$supplierInvoiceDetilesData['supplierInvoiceCount'];
$col2=$supplierInvoiceDetilesData['supplierInvoiceUnitPrice'];
$col3=$supplierInvoiceDetilesData['supplierInvoiceTotalItems'];
$total+=$col3;

}
if($tableType == "Automatic"){
echo "<tr>
<td>$sn</td>
<td>$desc</td>
<td>$qun</td>
<td>$uprice</td>
<td>$supTotal</td>
</tr>";
}
else if($tableType == "Doors" ){
echo"<tr>
<td>$sn</td>
<td>$desc</td>
<td>$width</td>
<td>$height</td>
<td>$MCOST</td>
<td>$qun</td>
<td>$uprice</td>
<td>$supTotal</td>
</tr>";
}
*/
?>

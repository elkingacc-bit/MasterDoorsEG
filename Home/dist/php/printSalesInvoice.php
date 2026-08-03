<!DOCTYPE html>
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Master Doors CMS</title>
 <!-- Google Font: Source Sans Pro -->
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
 <!-- Font Awesome -->
 <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
 <!-- Theme style -->
 <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body>
 <div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
   <!-- title row -->
   <div class="row">
    <div class="col-12">
     <h2 class="page-header m-10">
      <img src="../../dist/img/logoMarker.png" style="width:110px; height:115px">
      <img src="../../dist/img/logoMarker.png" style="position:absolute;top:2cm;left:30%;opacity: 0.1;filter: alpha(opacity=15);width: 350px; height:400px">
     </h2>
    </div>
    <!-- /.col -->
   </div>
   <?php
    @session_start();
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $invoiceId=$_GET['invNum'];
    $sqlSalesInoice="SELECT `salesInvoiceId`,`salesInvoiceNumber`,`jopRef`,`salesInvoiceDate`,`customerCode`,`salesInvoiceType`,`salesInvoiceSupTotal`,`invoiceDiscount`,
    `salesInvoictVat`,`totalInvoice`,`invoiceCollectAmount` 
    FROM `salesInvoice` WHERE `salesInvoiceId` = $invoiceId";
    $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 01");
    $salesInoiceData=mysqli_fetch_assoc($querySalesInoice);
    $jopId=$salesInoiceData['jopRef'];
    // Get Project Name
    $sqlProjectName="SELECT `projectName` FROM `job` WHERE `jobId` = $jopId";
    $quaryProjectName=mysqli_query($link,$sqlProjectName)or die("ERROR LOA_S:01");
    $projectName=mysqli_fetch_assoc($quaryProjectName);
    // Get Customer Data
    $sqlCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $salesInoiceData[customerCode]";
    $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
    $customerData=mysqli_fetch_assoc($quaryCustomer);
   ?>
   <!-- info row -->
   <div class="row invoice-info text-center">
    <div class="col-sm-4 invoice-col">
     From
     <br>
     <strong>Master Doors</strong>
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
     <b>Sales Invoice <strong>(<?php echo $salesInoiceData['salesInvoiceNumber'];?>)</strong></b>
     <br>
     <b>Project :</b><?php echo $projectName['projectName']; ?><br>
     <b>Invoice Date :</b><?php echo $salesInoiceData['salesInvoiceDate']; ?>
     <br>
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
     To
     <br>
     <strong> <?php echo $customerData['customername'];?></strong>
    </div>
    <!-- /.col -->
   </div>
   <!-- /.row -->
   <hr>
   <div class="table-responsive">
    <center>
     <table class="table table-striped" style="width: 90%;">
      <thead>
       <th>Description</th>
       <th>Quantity</th>
       <th>Unit Price</th>
       <th>Subtotal</th>
      </thead>
      <tbody>
       <?php
        $sqlSalesJop="SELECT `startDate`,`localref`,`customer`,`offerValue`,`jobtype`,`jobreceivables`,`endDate`,`invoice`, `salesman` FROM `job` WHERE `jobId` = $jopId";
        $querySalesJop=mysqli_query($link,$sqlSalesJop)or die("ERROR_SNSC : 02");
        $totalInv=0;
        While($jopData=mysqli_fetch_assoc($querySalesJop)){
         $orderType=$jopData['jobtype'];
         if($orderType == "Automatic"){
          $sqlOrderItems="SELECT `id`,`doortype`,`doorspecs`,`motorspecs`,`doorprice`,`doorqty`,`totalprice`,`jobid`,`ref` FROM `autodoorsoffer` WHERE `jobid` = $jopId";
          $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
          WHILE($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
           $totalInv += $itemsData['totalprice'];
           echo "<tr>
            <td>$itemsData[doortype]</td>
            <td>$itemsData[doorqty]</td>
            <td>".number_format(($itemsData[doorprice]), 2)."</td>
            <td>".number_format(($itemsData[totalprice]), 2)."</td> 
           </tr>";
          }
         }
         else if($orderType == "Doors" ){
          $sqlOrderItems="SELECT `id`,`itemname`,`itemtype`,`msquerprice`,`itemqty`,`totalprice` FROM `itemoffer` WHERE `jobref` = $jopId";
          $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
          WHILE($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
           $rowId=$itemsData['id'];
           $totalInv += $itemsData['totalprice'];
           echo "<tr>
            <td class='text-left'>$itemsData[itemtype]</td>
            <td>$itemsData[itemqty]</td>
            <td>".number_format(($itemsData[msquerprice]), 2)."</td>
            <td>".number_format(($itemsData[totalprice]), 2)."</td>
           </tr>";
           //HW
           $sqlOrderItemsHW="SELECT sum(`totalprice`) as hwPrice FROM `offerproperties` WHERE  `ioidref` = $rowId";
           $queryOrderItemsDataHW=mysqli_query($link,$sqlOrderItemsHW)or die("ERROR_SNSC : 01");
           $itemsHW=mysqli_fetch_assoc($queryOrderItemsDataHW);
           $hardwarePrice=$itemsHW['hwPrice'];
           $totalInv += $itemsHW['hwPrice'];
           echo"<tr>
            <td class='text-right'>Hardware</td>
            <td></td>
            <td></td>
            <td>".number_format(($hardwarePrice), 2)."</td>
           </tr>";
          }
         }    
         else if($orderType == "Stock" ){
          $sqlOrderItems="SELECT `id`, `descripcode`,`descripqty`,`descripprice`,`totalprice`,`jobref`,`ref`,`whref` FROM `stockoffers` WHERE `jobref` = $jopId";
          $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
          while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
           $totalInv += $itemsData['totalprice'];
           echo"<tr>
            <td>$itemsData[descripcode]</td>
            <td>$itemsData[descripqty]</td>
            <td>".number_format(($itemsData[descripprice]), 2)."</td>
            <td>".number_format(($itemsData[totalprice]), 2)."</td>
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
     </table>
    </center>
   </div>
   <!-- /.row -->
   <div class="row">
    <!-- accepted payments column -->
    <div class="col-6"></div>
    <!-- /.col -->
    <div class="col-6">
     <p class="lead"></p>
     <div class="table-responsive">
      <table class="table">
       <tr>
        <th style="width:50%">Subtotal:</th>
        <td><?php echo number_format(($salesInoiceData['salesInvoiceSupTotal']), 2);?></td>
       </tr>
       <tr>
        <th>VAT</th>
        <td><?php echo number_format(($salesInoiceData['salesInvoictVat']), 2);?></td>
       </tr>
       <tr>
        <th>Total:</th>
        <td><?php echo number_format(($salesInoiceData['totalInvoice']), 2);?></td>
       </tr>
      </table>
     </div>
    </div>
    <!-- /.col -->
   </div>
   <!-- /.row -->
  </section>
  <!-- /.content -->
 </div>
 <!-- jQuery -->
 <script src="../../plugins/jquery/jquery.min.js"></script>
 <script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
 <!-- Bootstrap 4 -->
 <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 <!-- multiSelect -->
 <script type="text/javascript" src="../../plugins/bootstrapMultiselect/js/bootstrap-multiselect.min.js"></script>
 <!-- AdminLTE App -->
 <script src="../../dist/js/adminlte.js"></script>
 <script src="../../dist/js/pages/navbar.js"></script>
 <script src="../../dist/js/checksession.js"></script>
 <script>
  window.addEventListener("load", window.print());
 </script>
</html>	
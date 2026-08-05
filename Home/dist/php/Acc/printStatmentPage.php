<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="../images/icons/favicon.jpg" sizes="128x128" />
  <title>Master Doors CMS</title>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <style>
   @media print {
    .statementPrintBtn { display: none !important; }
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
   }
   .statementTable { border-collapse: collapse; direction: ltr; }
   .statementTable th, .statementTable td {
    vertical-align: middle;
    border: 1px solid #333 !important;
   }
   .statementWatermark {
    position: absolute; top: 2cm; left: 30%; opacity: 0.08; filter: alpha(opacity=8);
    width: 350px; height: 400px; z-index: -1;
   }
   /* Standalone page - not using the AdminLTE sidebar shell, so don't inherit its
      .wrapper sidebar-offset margin. Center the statement with sane on-screen
      margins, and let it use the full page width when actually printed. */
   .statementContainer { max-width: 1100px; width: 100%; margin: 0 auto; padding: 15px 25px; }
   @media print {
    .statementContainer { max-width: 100%; padding: 0; margin: 0; }
   }
  </style>
 </head>
 <body>
 <?php
  date_default_timezone_set("Africa/Cairo");
  include_once("../connection.php");
  $invId=(int)$_GET['invRowNum'];
  // Invoice Data
  $sqlSalesInv="SELECT `salesInvoiceNumber`,`customerCode`,`jopRef` FROM `salesInvoice` WHERE `salesInvoiceId` = $invId";
  $querySalesInv=mysqli_query($link,$sqlSalesInv)or die("ERROR_SNSC : 02");
  $invData=mysqli_fetch_assoc($querySalesInv);
  $invoiceNumber=$invData['salesInvoiceNumber'];
  $invoiceCustomer=$invData['customerCode'];
  $jopref=$invData['jopRef'];
  // Project Name
  $sqlProject="SELECT `projectName` FROM `job` WHERE `jobId` = $jopref";
  $quaryProject=mysqli_query($link,$sqlProject)or die("ERROR_SNSC : 02");
  $projectData=mysqli_fetch_assoc($quaryProject);
  // Customer PO Data
  $sqlPoData="SELECT `orderType`, `PoNum`, `poDate`,`poVal`,`POVat`,`dwonpay`,`receivingpay`,`finishpay` FROM `customerpo` WHERE `jobidref` = $jopref";
  $quaryPoData=mysqli_query($link,$sqlPoData)or die("ERROR_SNSC : 02");
  $poData=mysqli_fetch_assoc($quaryPoData);
  $taotalPo=($poData['poVal'] + $poData['POVat']);
  // Get Customer Data
  $sqlCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $invoiceCustomer";
  $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
  $customerData=mysqli_fetch_assoc($quaryCustomer);
 ?>
 <div class="statementContainer">
   <!-- title row -->
   <div class="row">
    <div class="col-12 text-center">
     <h2 class="page-header m-10">
      <img src="../../img/logoMarker.png" style="width:110px; height:115px">
      <img src="../../img/logoMarker.png" class="statementWatermark">
     </h2>
    </div>
   </div>
   <!-- info row -->
   <div class="row invoice-info text-center">
    <div class="col-sm-4 invoice-col">
     From
     <br>
     <strong>Master Doors</strong>
    </div>
    <div class="col-sm-4 invoice-col">
     <b>Customer Statement <strong>(<?php echo htmlspecialchars($invoiceNumber); ?>)</strong></b>
     <br>
     <b>Project:</b> <?php echo htmlspecialchars($projectData['projectName'] ?? ''); ?><br>
     <b>Print Date:</b> <?php echo date('Y-m-d'); ?>
    </div>
    <div class="col-sm-4 invoice-col">
     To
     <br>
     <strong><?php echo htmlspecialchars($customerData['customername'] ?? ''); ?></strong>
    </div>
   </div>
   <hr>

   <h5 class="text-center">
    For P.O. Number (<span class="text-primary"><?php echo htmlspecialchars($poData['PoNum'] ?? ''); ?></span>)
    with Invoice Number (<span class="text-primary"><?php echo htmlspecialchars($invoiceNumber); ?></span>)
   </h5>

   <div class="table-responsive">
    <table class="table table-sm table-bordered statementTable w-100 text-center" dir="ltr">
     <thead class="bg-light">
      <th>Order Type</th>
      <th>P.O. Date</th>
      <th>P.O. Value</th>
      <th>VAT</th>
      <th>Total</th>
      <th>Down Payment</th>
      <th>Receiving Payment</th>
      <th>Finishing Payment</th>
     </thead>
     <tbody>
      <tr>
       <td><?php echo htmlspecialchars($poData['orderType'] ?? ''); ?></td>
       <td><?php echo htmlspecialchars($poData['poDate'] ?? ''); ?></td>
       <td><?php echo number_format($poData['poVal'], 2) ;?></td>
       <td><?php echo number_format($poData['POVat'], 2) ;?></td>
       <td><?php echo number_format($taotalPo, 2) ;?></td>
       <td><?php echo number_format($poData['dwonpay'], 2) ;?></td>
       <td><?php echo number_format($poData['receivingpay'], 2) ;?></td>
       <td><?php echo number_format($poData['finishpay'], 2) ;?></td>
      </tr>
     </tbody>
    </table>
   </div>

   <h5 class="mt-4">Statement of Account</h5>
   <div class="table-responsive">
    <table class="table table-sm table-bordered statementTable w-100 text-center" dir="ltr">
     <thead class="bg-light">
      <th>#</th>
      <th>Date</th>
      <th>Collected</th>
      <th>Remaining</th>
      <th>Description</th>
     </thead>
     <tbody>
      <?php
       // Collected
       // Note: `poNum` is a generic job-reference column also used by unrelated cash
       // transactions elsewhere in the app (supplier payments, expense settlements),
       // so it's scoped to this customer's own account. `<> 0` (not `> 0`) so
       // legitimate negative correction/reversal entries still count. Remaining is
       // recomputed as totalPo - collected so far, matching the summary total below.
       $sqlCash="SELECT `transactionDate`,`income`,`description`,`statmentRef` FROM `cash_transaction`
       WHERE `poNum` = $jopref AND `income` <> 0 AND `account` = $invoiceCustomer
       ORDER BY `transactionDate` ASC";
       $queryCash=mysqli_query($link,$sqlCash)or die("ERROR_SNSC : 02");
       $sn=0;
       $collected=0;
       $balance=$taotalPo;
       if(mysqli_num_rows($queryCash) > 0){
        while($collecCash=mysqli_fetch_assoc($queryCash)){
         $sn++;
         $collected += $collecCash['income'];
         $balance = ($taotalPo - $collected);
         echo"<tr>
          <td>$sn</td>
          <td>".htmlspecialchars($collecCash['transactionDate'])."</td>
          <td>".number_format(($collecCash['income']), 2)."</td>
          <td>".number_format(($balance), 2)."</td>
          <td class='text-left'>".htmlspecialchars($collecCash['description'])."</td>
         </tr>";
        }
       }
       $sqlSalesVat="SELECT `transactionsDate`,`debtor`,`creditor`,`description` FROM `financialTransactions`
       WHERE `transactionCode` = 21210110000 AND `description` like '$invoiceNumber %'";
       $querySalesVat=mysqli_query($link,$sqlSalesVat)or die("ERROR_SNSC : 02");
       if(mysqli_num_rows($querySalesVat) > 0){
        while($collectVat=mysqli_fetch_assoc($querySalesVat)){
         $sn++;
         $amoun=($collectVat['debtor']);
         $collected += $collectVat['debtor'];
         $balance = ($taotalPo - $collected);
         echo"<tr>
          <td>$sn</td>
          <td>".htmlspecialchars($collectVat['transactionsDate'])."</td>
          <td>".number_format(($amoun), 2)."</td>
          <td>".number_format(($balance), 2)."</td>
          <td class='text-left'>".htmlspecialchars($collectVat['description'])."</td>
         </tr>";
        }
       }
       if($sn == 0){
        echo "<tr><td colspan='5' class='text-center text-muted'>No transactions recorded yet</td></tr>";
       }
      ?>
     </tbody>
    </table>
   </div>

   <div class="row">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
     <div class="table-responsive">
      <table class="table table-sm">
       <tr>
        <th style="width:50%">Total:</th>
        <td><?php echo number_format($taotalPo, 2) ;?></td>
       </tr>
       <tr>
        <th>Collected:</th>
        <td><?php echo number_format($collected, 2) ;?></td>
       </tr>
       <tr class="bg-light">
        <th>Remaining:</th>
        <td><strong><?php echo number_format(($taotalPo - $collected), 2) ;?></strong></td>
       </tr>
      </table>
     </div>
    </div>
   </div>

   <div class="row mt-5">
    <div class="col-sm-6 text-center">
     <hr style="width:80%; margin:0 auto;">
     Accountant Signature
    </div>
    <div class="col-sm-6 text-center">
     <hr style="width:80%; margin:0 auto;">
     Customer Signature
    </div>
   </div>
 </div>
 <center class="statementPrintBtn my-3">
  <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
 </center>
 <!-- jQuery -->
 <script src="../../plugins/jquery/jquery.min.js"></script>
 <!-- Bootstrap 4 -->
 <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 <script type="text/javascript">
  $(document).ready(function(){
   window.print();
  });
 </script>
 </body>
</html>

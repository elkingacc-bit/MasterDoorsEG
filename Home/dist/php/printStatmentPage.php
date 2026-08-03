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
   <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
   <!-- multiSelect -->
  <link rel="stylesheet" href="../../plugins/bootstrapMultiselect/css/bootstrap-multiselect.min.css" type="text/css"/>
 </head>
 <?php
  date_default_timezone_set("Africa/Cairo");
  include_once("connection.php");
  $invId=$_GET['invRowNum'];
  // Invoice Data
  $sqlSalesInv="SELECT `salesInvoiceNumber`,`customerCode`,`jopRef` FROM `salesInvoice` WHERE `salesInvoiceId` = $invId";
  $querySalesInv=mysqli_query($link,$sqlSalesInv)or die("ERROR_SNSC : 02");
  $invData=mysqli_fetch_assoc($querySalesInv);
  $invoiceNumber=$invData['salesInvoiceNumber'];
  $invoiceCustomer=$invData['customerCode'];
  $jopref=$invData['jopRef'];
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
 <div class="table-responsive text-center">
  <div class="card text-center">
   <div class="card-header">
    <table class="table table-sm w-100 table-borderless">
     <td class="col-3 text-left">MasterDoorsEG</td>
     <td class="col-6"><span class="text-muted"><?php echo $customerData['customername']; ?> </span> Statment </td> 
     <td class="col-3"><?php echo date('Y-m-d'); ?></td>   
    </table>
   </div>
   <div class="card-body">
    <h5 class="card-title">
     For Po Number (<span class="text-primary"><?php echo $poData['PoNum']; ?> </span> ) 
     With Invoice Number (<span class="text-primary"><?php echo $invoiceNumber; ?> </span>)
    </h5>
    <br>
    <table class='table table-sm table-bordered table-striped w-100'>
     <thead>
      <th>orderType</th>
      <th>poDate</th>
      <th>poVal</th>
      <th>VAT</th>
      <th>Total</th>
      <th>dwonpay</th>
      <th>receivingpay</th>
      <th>finishpay</th>
     </thead>
     <tbody>
      <td><?php echo $poData['orderType'] ;?></td>
      <td><?php echo $poData['poDate'] ;?></td>
      <td><?php echo number_format($poData['poVal'], 2) ;?></td>
      <td><?php echo number_format($poData['POVat'], 2) ;?></td>
      <td><?php echo number_format($taotalPo, 2) ;?></td>
      <td><?php echo number_format($poData['dwonpay'], 2) ;?></td>
      <td><?php echo number_format($poData['receivingpay'], 2) ;?></td>
      <td><?php echo number_format($poData['finishpay'], 2) ;?></td>
     </tbody>
    </table>
    <hr>
    <table class='table table-sm table-bordered table-striped  w-100'>
     <thead class='bg-info'>
      <th>Sn</th>
      <th>Date</th>
      <th>Valid</th>
      <th>pay</th>
      <th>Remaining</th>
      <th>Discription</th>
     </thead>
     <tbody>
      <?php
       // Collected
       $sqlCash="SELECT `transactionDate`,`income`,`description`,`amountRef`,`statmentRef` FROM `cash_transaction` WHERE `poNum` = $jopref
       ORDER BY `transactionDate` ASC";
       $queryCash=mysqli_query($link,$sqlCash)or die("ERROR_SNSC : 02");
       $sn=0;
       $collected=0;
       $valid=0;
       $balance=0;
       while($collecCash=mysqli_fetch_assoc($queryCash)){
        $sn++;
        $collected += $collecCash['income'];
        $valid += $collecCash['amountRef'];
        $balance = ($valid - $collected);
        echo"<tr> 
         <td>$sn</td>
         <td>$collecCash[transactionDate]</td>
         <td>$collecCash[amountRef]</td>
         <td>$collecCash[income]</td>
         <td>$balance</td>
         <td>$collecCash[description]</td>     
        </tr>";
       }     
       $sqlSalesVat="SELECT `transactionsDate`,`debtor`,`creditor`,`description` FROM `financialTransactions` 
       WHERE `transactionCode` = 21210110000 AND `description` like '$invoiceNumber %'";
       $querySalesVat=mysqli_query($link,$sqlSalesVat)or die("ERROR_SNSC : 02");
       while($collectVat=mysqli_fetch_assoc($querySalesVat)){
        $sn++;
        $amoun=($collectVat['debtor']);
        $collected += $collectVat['debtor'];
        $balance -= $amoun;
        echo"<tr>
         <td>$sn</td>
         <td>$collectVat[transactionsDate]</td>
         <td></td>
         <td>$amoun</td>
         <td>$balance</td>
         <td>$collectVat[description]</td>
        </tr>";        
       }
      ?>
     </tbody>
    </table>    
   </div>
   <div class="card-footer">
    <h3 class=" text-muted">Summary Statistics</h3>
    <table class='table table-sm table-bordered table-striped  w-100'>
     <thead>
      <th>Total</th>
      <th>Collected</th>
      <th>Remaining</th>
      <th>Valid</th>
     </thead>
     <tbody>
      <td><?php echo number_format($taotalPo, 2) ;?> </td>
      <td><?php echo number_format($collected, 2) ;?> </td>
      <td><?php echo number_format(($balance), 2) ;?></td>
      <td><?php echo number_format(($taotalPo - $collected), 2) ;?> </td>
     </tbody>
    </table>
   </div>
  </div>
 </div>
 <!-- jQuery -->
 <script src="../../plugins/jquery/jquery.min.js"></script>
 <script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
 <!-- Bootstrap 4 -->
 <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 <script type="text/javascript">
  $(document).ready(function(){
   window.print();
  }): 
 </script>
</html>
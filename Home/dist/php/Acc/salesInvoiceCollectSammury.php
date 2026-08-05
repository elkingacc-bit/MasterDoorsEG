<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $invId=(int)$_POST['invRowNum'];
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
<center>
 <div class="table-responsive text-center">
  <table class='table table-sm table-bordered table-striped w-100'>
   <thead>
    <th>Customer</th>
    <th>Order Type</th>
    <th>PoNum</th>
    <th>poDate</th>
    <th>Amount</th>
    <th>VAT</th>
    <th>Total</th>
    <th>Dwonpayment</th>
    <th>Collecting</th>
    <th>Finish Amount</th>
   </thead>
   <tbody>
    <td><?php echo $customerData['customername'] ;?></td>
    <td><?php echo $poData['orderType'] ;?></td>
    <td><?php echo $poData['PoNum'] ;?></td>
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
    <th>pay</th>
    <th>Remaining</th>
    <th>Description</th>
   </thead>
   <tbody>
    <?php
     // Collected
     // FIX: `poNum` is a generic job-reference column reused by many unrelated cash
     // transactions across the app (supplier payments, expense settlements, ...) -
     // without `income <> 0 AND account = <this customer>` it can pull in completely
     // unrelated rows that merely happen to share this job's id as `poNum`. Using
     // `<> 0` rather than `> 0` so legitimate negative correction/reversal entries
     // (seen in real data, e.g. a collection later reversed) still count.
     // FIX: the running "Remaining" balance used to be computed from `amountRef`,
     // a column that's only ever populated for down-payment/FIFO-collection rows
     // and stays NULL for a normal "Collect Invoice" row - so it produced a
     // meaningless (often negative) balance for the most common collection type.
     // The correct remaining is simply totalPo - collected so far, recomputed here
     // to match the (already correct) summary total at the bottom of the page.
     $sn=0;
     $collected=0;
     $balance=$taotalPo;
     $sqlCash="SELECT `transactionDate`,`income`,`description`,`statmentRef` FROM `cash_transaction`
     WHERE `poNum` = $jopref AND `income` <> 0 AND `account` = $invoiceCustomer
     ORDER BY `transactionDate` ASC";
     $queryCash=mysqli_query($link,$sqlCash)or die("ERROR_SNSC : 02");
     if(mysqli_num_rows($queryCash) > 0 ){
      while($collecCash=mysqli_fetch_assoc($queryCash)){
       $sn++;
       $collected += $collecCash['income'];
       $balance = ($taotalPo - $collected);
       echo"<tr>
        <td>$sn</td>
        <td>$collecCash[transactionDate]</td>
        <td>".number_format(($collecCash['income']), 2)."</td>
        <td>".number_format(($balance ), 2)."</td>
        <td>$collecCash[description]</td>
       </tr>";
      }
     }
     $sqlSalesVat="SELECT `transactionsDate`,`debtor`,`creditor`,`description` FROM `financialTransactions` WHERE `transactionCode` = 21210110000
     AND `description` like '$invoiceNumber %'";
     $querySalesVat=mysqli_query($link,$sqlSalesVat)or die("ERROR_SNSC : 02");
     if(mysqli_num_rows($querySalesVat) > 0){
      while($collectVat=mysqli_fetch_assoc($querySalesVat)){
       $sn++;
       $amoun=($collectVat['debtor']);
       $collected += $collectVat['debtor'];
       $balance = ($taotalPo - $collected);
       echo"<tr>
        <td>$sn</td>
        <td>$collectVat[transactionsDate]</td>
        <td>".number_format(($amoun), 2)."</td>
        <td>".number_format(($balance), 2)."</td>
        <td>$collectVat[description]</td>
       </tr>";
      }
     }
    ?>
   </tbody>
  </table>
  <hr>
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
 <button class="btn btn-info" id="printStatment" value="<?php echo $invId; ?>"><i class='fas fa-print nav-icon'></i></button>
</center>
<script type="text/javascript">
 $(document).ready(function(){
  $("#printStatment").click(function(){
   var rowId = $(this).val();
   window.open(`dist/php/Acc/printStatmentPage.php?invRowNum=${rowId}`, '_blank');
   return false;
  });
 });
</script>
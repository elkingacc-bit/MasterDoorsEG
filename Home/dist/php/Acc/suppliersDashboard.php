<?php  include_once("../authCheck.php"); include_once("../connection.php"); ?>
<div class="row">
 <!-----*{ Manufactur }*----->
 <div class="col-md-6 bg-light text-dark">
  <div class="card mb-4">
   <div class="card-header bg-info text-white"> <h6 class="text-center">Manufacturing</h6></div>
   <!-- /.card-header -->
   <div class="card-body">
    <table class="table table-bordered">
     <thead class="bg-success text-white text-center">
      <tr>
       <th style="width: 10px">#</th>
       <th>Manufacture</th>
       <th>Orders</th>
       <th>Payments</th>
       <th>Manufacture dues</th>
       <th>Progress</th>
      </tr>
     </thead>
     <tbody>
      <?php
       $sn=0;
       $totalManufacturOrder=0;
       $totalManufacturpayment=0;
       $totalManufacturValid=0;
       $allManufacturOrder=0;
       $sqlAllManufacturing="SELECT sum(`totalAmout`) AS totalAllInv FROM `purchasesorder`";
       $queryAllStatment=mysqli_query($link,$sqlAllManufacturing);
       $statmentAllManufactur=mysqli_fetch_assoc($queryAllStatment);
       $allManufacturOrder=$statmentAllManufactur['totalAllInv'];

       // Pre-fetch per-supplier aggregates in 2 queries instead of running them
       // per supplier inside the loop below (was up to 2 extra queries x supplier count)
       $purchaseOrderTotals=[];
       $sqlPurchaseOrderTotals="SELECT `supplierid`, sum(`totalAmout`) AS totalInv FROM `purchasesorder` GROUP BY `supplierid`";
       $queryPurchaseOrderTotals=mysqli_query($link,$sqlPurchaseOrderTotals)or die("ERROR :01-AU_AU_S".mysqli_error($link));
       while($row=mysqli_fetch_assoc($queryPurchaseOrderTotals)){
        $purchaseOrderTotals[$row['supplierid']]=$row['totalInv'];
       }
       $cashWithdrawalTotals=[];
       $sqlCashWithdrawalTotals="SELECT `account`, sum(`withdrawal`) AS paymentAmount FROM `cash_transaction` GROUP BY `account`";
       $queryCashWithdrawalTotals=mysqli_query($link,$sqlCashWithdrawalTotals)or die("ERROR :01-AU_AU_S".mysqli_error($link));
       while($row=mysqli_fetch_assoc($queryCashWithdrawalTotals)){
        $cashWithdrawalTotals[$row['account']]=$row['paymentAmount'];
       }

       $sqlGetManufacturing="SELECT `suppliercode`,`suppliername` FROM `allsuppliers`";
       $queryGetManufactur=mysqli_query($link,$sqlGetManufacturing)or die("ERROR :01-AU_AU_S".mysqli_error($link));
       while($resGetManufactur = mysqli_fetch_assoc($queryGetManufactur)){
        $statmentManufactur=['totalInv'=>$purchaseOrderTotals[$resGetManufactur['suppliercode']] ?? null];
        $Manufacturpayment=['paymentAmount'=>$cashWithdrawalTotals[$resGetManufactur['suppliercode']] ?? null];
        $valiedManufacturAmount= ($statmentManufactur['totalInv'] - $Manufacturpayment['paymentAmount']);
        $perSubManufactur=($statmentManufactur['totalInv']/$allManufacturOrder)*100;
        $perManufactur=number_format(($perSubManufactur), 2)." % ";
        if($valiedManufacturAmount > 0){
         $totalManufacturOrder += $statmentManufactur['totalInv'];
         $totalManufacturpayment += $Manufacturpayment['paymentAmount'];
         $totalManufacturValid += $valiedManufacturAmount;
         $supPer=($Manufacturpayment['paymentAmount']/$statmentManufactur['totalInv']);
         $receiveOrder=$supPer * 100 ;
         $supTotalPer=($totalManufacturpayment / $totalManufacturOrder);
         $totalProssess=$supTotalPer * 100 ;
         if($receiveOrder  <= 25 ){
          $receiveProgressM = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-danger' style='width: $receiveOrder%'></div></div>";
          $receiveProgressT = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-danger' style='width: $totalProssess%'></div></div>";
         }
         else if($receiveOrder  <= 50 ){
          $receiveProgressM = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-warning' style='width: $receiveOrder%'></div></div>";
          $receiveProgressT = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-warning' style='width: $totalProssess%'></div></div>";
         }
         else if($receiveOrder  <= 75 ){
          $receiveProgressM = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-info' style='width: $receiveOrder%'></div></div>";
          $receiveProgressT = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-info' style='width: $totalProssess%'></div></div>";
         }
         else if($receiveOrder  <= 99 ){
          $receiveProgressM = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-success' style='width: $receiveOrder%'></div></div>";
          $receiveProgressT = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-success' style='width: $totalProssess%'></div></div>";
         }
         else if($receiveOrder  == 100 ){
          $receiveProgressM = "<i class='fas fa-check'></i>";
          $receiveProgressT = "<i class='fas fa-check'></i>";
         }
        }
        else{
	     $receiveOrder=0;
	     $receiveProgressM = "<i class='far fa-times-circle'></i> Missing Invoice";
        }
        if($statmentManufactur['totalInv'] > 0){
 	     $sn++;
         echo"<tr class='align-middle'>
          <td>$sn</td>
          <td><button type='button' class='btn btn-link showManfuctureData' value='$resGetManufactur[suppliercode]'>$resGetManufactur[suppliername]</button>$perManufactur</td>
          <td>".number_format(($statmentManufactur['totalInv']), 2)."</td>
          <td>".number_format(($Manufacturpayment['paymentAmount']), 2)."</td>
          <td>".number_format(($valiedManufacturAmount), 2)."</td>
          <td>$receiveProgressM</td>
         </tr>";
        }
       }
      ?>
     </tbody>
     <tfoot class="bg-secondary text-white text-center">
     	<th colspan="2" class="text-center">Total</th>
     	<th><?php echo number_format(($totalManufacturOrder), 2); ?> </th>
     	<th><?php echo number_format(($totalManufacturpayment), 2); ?> </th>
     	<th><?php echo number_format(($totalManufacturValid), 2); ?> </th>
     	<th><?php echo $receiveProgressT; ?> </th>
     </tfoot>
    </table>
   </div>
  </div>
 </div>
 <!-----*{ Suppliers }*----->
 <div class="col-md-6 bg-light text-dark">
  <div class="card mb-4">
   <div class="card-header bg-primary text-white"> <h6 class="text-center">Suppliers</h6></div>
   <!-- /.card-header -->
   <div class="card-body">
    <table class="table table-bordered">
     <thead>
      <tr class="bg-warning text-dark text-center">
       <th style="width: 10px">#</th>
       <th>Suppliers</th>
       <th>Invoice</th>
       <th>Paid</th>
       <th>Valid</th>
       <th>Progress</th>
      </tr>
     </thead>
     <tbody>
      <?php
       // Pre-fetch supplier-invoice totals in 1 query instead of per-supplier inside the loop
       $supplierInvoiceTotals=[];
       $sqlSupplierInvoiceTotals="SELECT `supplierCode`, sum(`suppliersInvoiceTotal`) AS tAmount FROM `supplierInvoice` GROUP BY `supplierCode`";
       $querySupplierInvoiceTotals=mysqli_query($link,$sqlSupplierInvoiceTotals)or die("ERROR_SNSC : 01");
       while($row=mysqli_fetch_assoc($querySupplierInvoiceTotals)){
        $supplierInvoiceTotals[$row['supplierCode']]=$row['tAmount'];
       }

       $sqlGetSupplier="SELECT `suppliercode`,`suppliername` FROM `allsuppliers`";
       $queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :01-AU_AU_S".mysqli_error($link));
       $sn2=0;
       $totalSupplierOrder =0;
       $totalSupplierpayment =0;
       $totalSupplierValid =0;
       while($resGetSupplier = mysqli_fetch_assoc($queryGetSupplier)){
        // Same semantics as the original NOT IN(...) subquery: only count invoices
        // for suppliers that don't appear at all in purchasesorder.supplierid
        $totalPur= array_key_exists($resGetSupplier['suppliercode'], $purchaseOrderTotals)
         ? null
         : ($supplierInvoiceTotals[$resGetSupplier['suppliercode']] ?? null);
        $payment=['paymentAmount'=>$cashWithdrawalTotals[$resGetSupplier['suppliercode']] ?? null];
        $valiedAmount= ($totalPur - $payment['paymentAmount']);
        if($valiedAmount < 0 ){
         $receiveSupplierOrder = '';
         $receiveProgressS = 'Missing Invoice';
        }
        if($totalPur > 0){
          $totalSupplierOrder += $totalPur;
          $totalSupplierpayment += $payment['paymentAmount'];
          $totalSupplierValid += $valiedAmount;
          $supSupplierPer=($payment['paymentAmount']/$totalPur);
          $receiveSupplierOrder=$supSupplierPer * 100 ;
          $supTotalSupplier=($totalSupplierpayment / $totalSupplierOrder);
          $totalSupplierProssess=$supTotalSupplier * 100 ;
        
        

        if($receiveSupplierOrder  <= 25 ){
          $receiveProgressS = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-danger' style='width: $receiveSupplierOrder%'></div></div>";
          $receiveProgressST = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-danger' style='width: $totalSupplierProssess%'></div></div>";
         }
         else if($receiveSupplierOrder  <= 50 ){
          $receiveProgressS = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-warning' style='width: $receiveSupplierOrder%'></div></div>";
          $receiveProgressST = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-warning' style='width: $totalSupplierProssess%'></div></div>";
         }
         else if($receiveSupplierOrder  <= 75 ){
          $receiveProgressS = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-info' style='width: $receiveSupplierOrder%'></div></div>";
          $receiveProgressST = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-info' style='width: $totalSupplierProssess%'></div></div>";
         }
         else if($receiveSupplierOrder  <= 99 ){
          $receiveProgressS = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-success' style='width: $receiveSupplierOrder%'></div></div>";
          $receiveProgressST = "<div class='progress progress-xs'><div class='progress-bar progress-bar-striped bg-success' style='width: $totalSupplierProssess%'></div></div>";
         }
         else if($receiveSupplierOrder  == 100 ){
          $receiveProgressS = "<i class='fas fa-check'></i>";
          $receiveProgressST = "<i class='fas fa-check'></i>";
         }	
 	          
 	     $sn2++;
  	     echo"<tr class='align-middle'>
          <td>$sn2</td>
          <td><button type='button' class='btn btn-link showSupplierData' value='$resGetSupplier[suppliercode]'>$resGetSupplier[suppliername]</button></td>
          <td>".number_format(($totalPur), 2)." </td>
          <td>".number_format(($payment['paymentAmount']), 2)." </td>
          <td>".number_format(($valiedAmount), 2)." </td>
          <td>$receiveProgressS</td>
         </tr>";
        }
       }
      ?>
     </tbody>
      <tfoot class="bg-secondary text-white text-center">
     	<th colspan="2" class="text-center">Total</th>
     	<th><?php echo number_format(($totalSupplierOrder), 2); ?> </th>
     	<th><?php echo number_format(($totalSupplierpayment), 2); ?> </th>
     	<th><?php echo number_format(($totalSupplierValid), 2); ?> </th>
     	<th><?php echo $receiveProgressST; ?> </th>
     </tfoot>
    </table>
   </div>
  </div>
 </div>
</div>
<script>
$(document).ready(function(){
 $(".showSupplierData").click(function(){
  var acountCode=$(this).val();
  $.ajax({ 
   url:'dist/php/Acc/allSupplierStatmentData.php',
   type:"POST",
   data:{accCode:acountCode},
   success: function(supplierAllStatmentData){
    $(".data_display").html('');
    $(".data_display").html(supplierAllStatmentData); 
   }
  });     
 });

$(".showManfuctureData").click(function(){
  var acountCode=$(this).val();
  $.ajax({ 
   url:'dist/php/Acc/allManfuctureStatmentData.php',
   type:"POST",
   data:{accCode:acountCode},
   success: function(supplierAllStatmentData){
    $(".data_display").html('');
    $(".data_display").html(supplierAllStatmentData); 
   }
  });     
 });

});
</script>
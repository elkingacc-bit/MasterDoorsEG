 <table class="table table-sm table-bordered table-striped">
  <thead>
  <th>Date</th>
  <th>Amount</th>
  <th>Description</th>
  <th>Account</th>
  </thead>
 <tbody>
  <?php
   date_default_timezone_set("Africa/Cairo");
  include_once("connection.php");
  $invoiceId=$_POST['invRowNum'];
  $sqlSupplierCashData="SELECT `transactionDate`,`withdrawal`,`description`,`statmentRef` FROM `cash_transaction` WHERE `poNum` = $invoiceId";
  $querySupplierCashData=mysqli_query($link,$sqlSupplierCashData)or die("ERROR_SNSC : 02");
  if(mysqli_num_rows($querySupplierCashData) > 0){
   while($supplierCashData=mysqli_fetch_assoc($querySupplierCashData)){
    $bank=$supplierCashData['statmentRef'];
      $sqlGroupData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $bank";
      $queryGroupData=mysqli_query($link,$sqlGroupData)or die("ERROR LOA_S:01");
      $groupName=mysqli_fetch_assoc($queryGroupData);
    echo"<tr>
     <td>$supplierCashData[transactionDate]</td>
     <td>".number_format(($supplierCashData['withdrawal']), 2)."</td>
     <td>$supplierCashData[description]</td>
     <td>$groupName[accountName]</td>
    </tr>";
   }
  }
  ?>
 </tbody>
</table>
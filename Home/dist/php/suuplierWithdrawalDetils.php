<div class="table-responsive-lg text-center">
 <table class='table table-sm table-bordered table-striped'>
  <thead class='bg-info'>
   <th>Date2</th>
   <th>Amount</th>
   <th>Description</th>
   <th>From</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $invId=$_POST['orderId'];    
    $sqlSupplierCashData="SELECT `transactionDate`,`withdrawal`,`description`,`statmentRef` 
    FROM `cash_transaction` 
    WHERE  `withdrawal` > '0' AND `poNum` = '$invId' ";
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
</div>
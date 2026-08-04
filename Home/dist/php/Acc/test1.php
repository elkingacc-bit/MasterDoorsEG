<div class="table-responsive-lg">
 <table class='table table-sm table-bordered table-striped w-100 editingTable'>
  <thead class='bg-info text-center' style='width: 100%;'> 
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("../connection.php");
    $resData=$_POST['editType'];
    #Partnership
    if($resData == 1){
     echo"
      <th>partnersName</th>
      <th>partnerPercentage</th>
      <th>Edit</th>
     </thead>
     <tbody>";
      $sqlDataEdit="SELECT `partnershipRatioId`, `partnersName`, `partnerPercentage` FROM `partnershipRatio`";  
      $queryDataEdit=mysqli_query($link,$sqlDataEdit);
      WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
       $searchCode=$resultDataEdit['partnersName'];
       $sqlAccountantCodeData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $searchCode";  
       $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
       $sqlCustomerCodeData="SELECT `customername` FROM `customers` WHERE `customercode` = $searchCode";  
       $queryCustomerCodeData=mysqli_query($link,$sqlCustomerCodeData);
       $sqlSupplierCodeData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $searchCode";  
       $querySupplierCodeData=mysqli_query($link,$sqlSupplierCodeData);
       $sqlEmpCodeData="SELECT `fullname` FROM `users` WHERE `userid` = $searchCode";  
       $queryEmpCodeData=mysqli_query($link,$sqlEmpCodeData);
       $sqlStaffCodeData="SELECT `staffname` FROM `allstaff` WHERE `id` = $searchCode";  
       $queryStaffCodeData=mysqli_query($link,$sqlStaffCodeData);
       if(mysqli_num_rows($queryAccountantCodeData) > 0 ){
        $accData=mysqli_fetch_assoc($queryAccountantCodeData);        
        $name=$accData['accountName'];
       }
       else if(mysqli_num_rows($queryCustomerCodeData) > 0 ){
        $accData=mysqli_fetch_assoc($queryCustomerCodeData);        
        $name=$accData['customername'];
       }
       else if(mysqli_num_rows($querySupplierCodeData) > 0 ){
        $accData=mysqli_fetch_assoc($querySupplierCodeData);        
        $name=$accData['suppliername'];
       }
       else if(mysqli_num_rows($queryEmpCodeData) > 0 ){
        $accData=mysqli_fetch_assoc($queryEmpCodeData);        
        $name=$accData['fullname'];
       }
       else if(mysqli_num_rows($queryStaffCodeData) > 0 ){
        $accData=mysqli_fetch_assoc($queryStaffCodeData);        
        $name=$accData['staffname'];
       }
       echo "<tr>
        <input class='choosedName' value='Partnership' style='display: none;'>
        <input class='tableName' value='partnershipRatio' style='display: none;'>
        <td>$name</td>
        <td>$resultDataEdit[partnerPercentage]</td>
        <td><button class='btn btn-link Edit' value='$resultDataEdit[partnershipRatioId]'>Edit</button></td> 
       </tr>";
      }
     }
     # Cash
     else if($resData == 2){
     echo"
      <th>Date</th>
      <th>Deposit</th>
      <th>withdrwal</th>
      <th>Discr</th>
      <th>account</th>
      <th>poNum</th>
      <th>invNumber</th>
      <th>Edit</th>
     </thead>
     <tbody>";
     $sqlDataEdit="SELECT `cash_transaction_id`,`transactionDate`,`income`,`withdrawal`,`description`,`account`,`poNum`,`invNumber`
     FROM `cash_transaction` WHERE `statmentRef` LIKE '1161%'";  
     $queryDataEdit=mysqli_query($link,$sqlDataEdit); 
     WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
      $searchCode=$resultDataEdit['account'];
      $sqlAccountantCodeData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $searchCode";  
      $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
      $sqlCustomerCodeData="SELECT `customername` FROM `customers` WHERE `customercode` = $searchCode";  
      $queryCustomerCodeData=mysqli_query($link,$sqlCustomerCodeData);
      $sqlSupplierCodeData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $searchCode";  
      $querySupplierCodeData=mysqli_query($link,$sqlSupplierCodeData);
      $sqlEmpCodeData="SELECT `fullname` FROM `users` WHERE `userid` = $searchCode";  
      $queryEmpCodeData=mysqli_query($link,$sqlEmpCodeData);
      $sqlStaffCodeData="SELECT `staffname` FROM `allstaff` WHERE `id` = $searchCode";  
      $queryStaffCodeData=mysqli_query($link,$sqlStaffCodeData);
      if(mysqli_num_rows($queryAccountantCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryAccountantCodeData);        
       $name=$accData['accountName'];
      }
      else if(mysqli_num_rows($queryCustomerCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryCustomerCodeData);        
       $name=$accData['customername'];
      }
      else if(mysqli_num_rows($querySupplierCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($querySupplierCodeData);        
       $name=$accData['suppliername'];
      }
      else if(mysqli_num_rows($queryEmpCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryEmpCodeData);        
       $name=$accData['fullname'];
      }
      else if(mysqli_num_rows($queryStaffCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryStaffCodeData);        
       $name=$accData['staffname'];
      }
      echo"<tr>
       <input class='choosedName' value='Cash' style='display: none;'>
       <input class='tableName' value='cash_transaction' style='display: none;'>
       <td>$resultDataEdit[transactionDate]</td>
       <td>$resultDataEdit[income]</td>
       <td>$resultDataEdit[withdrawal]</td>
       <td>$resultDataEdit[description]</td>
       <td>$name</td>
       <td>$resultDataEdit[poNum]</td>
       <td>$resultDataEdit[invNumber]</td>
       <td><button class='btn btn-link Edit' value='$resultDataEdit[cash_transaction_id]'>Edit</button></td> 
      </tr>";
     }
    }
    # Bank
    else if($resData == 3){ 
     echo"
      <th>Date</th>
      <th>Deposit</th>
      <th>withdrwal</th>
      <th>Discr</th>
      <th>account</th>
      <th>poNum</th>
      <th>invNumber</th>
      <th>Edit</th>
     </thead>
     <tbody>";
     $sqlDataEdit="SELECT `cash_transaction_id`,`transactionDate`,`income`,`withdrawal`,`description`,`account`,`poNum`,`invNumber`
     FROM `cash_transaction` WHERE `statmentRef` LIKE '1162%'";  
     $queryDataEdit=mysqli_query($link,$sqlDataEdit); 
     WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
      $searchCode=$resultDataEdit['account'];
      $sqlAccountantCodeData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $searchCode";  
      $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
      $sqlCustomerCodeData="SELECT `customername` FROM `customers` WHERE `customercode` = $searchCode";  
      $queryCustomerCodeData=mysqli_query($link,$sqlCustomerCodeData);
      $sqlSupplierCodeData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $searchCode";  
      $querySupplierCodeData=mysqli_query($link,$sqlSupplierCodeData);
      $sqlEmpCodeData="SELECT `fullname` FROM `users` WHERE `userid` = $searchCode";  
      $queryEmpCodeData=mysqli_query($link,$sqlEmpCodeData);
      $sqlStaffCodeData="SELECT `staffname` FROM `allstaff` WHERE `id` = $searchCode";  
      $queryStaffCodeData=mysqli_query($link,$sqlStaffCodeData);
      if(mysqli_num_rows($queryAccountantCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryAccountantCodeData);        
       $name=$accData['accountName'];
      }
      else if(mysqli_num_rows($queryCustomerCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryCustomerCodeData);        
       $name=$accData['customername'];
      }
      else if(mysqli_num_rows($querySupplierCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($querySupplierCodeData);        
       $name=$accData['suppliername'];
      }
      else if(mysqli_num_rows($queryEmpCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryEmpCodeData);        
       $name=$accData['fullname'];
      }
      else if(mysqli_num_rows($queryStaffCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryStaffCodeData);        
       $name=$accData['staffname'];
      }
      echo"<tr>
       <input class='choosedName' value='Bank' style='display: none;'>
       <input class='tableName' value='cash_transaction' style='display: none;'>
       <td>$resultDataEdit[transactionDate]</td>
       <td>$resultDataEdit[income]</td>
       <td>$resultDataEdit[withdrawal]</td>
       <td>$resultDataEdit[description]</td>
       <td>$name</td>
       <td>$resultDataEdit[poNum]</td>
       <td>$resultDataEdit[invNumber]</td>
       <td><button class='btn btn-link Edit' value='$resultDataEdit[cash_transaction_id]'>Edit</button></td> 
      </tr>";
     }
    }
    # Investment
    else if($resData == 4){
     echo"
      <th>Date</th>
      <th>Quantaty</th>
      <th>Buying</th>
      <th>Sales</th>
      <th>Group</th>
      <th>Discr</th>
      <th>Edit</th>
     </thead>
     <tbody>";
     $sqlDataEdit="SELECT `investmentId`,`transactionDate`,`quantaty`,`buyingAmount`,`salesAmount`,`investmentGroup`,`description` FROM `investment`";
     $queryDataEdit=mysqli_query($link,$sqlDataEdit);
     WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
      $searchCode=$resultDataEdit['investmentGroup'];
      if($searchCode == 10){$name="أراضى";}
      else if($searchCode == 11){$name="عقارات";}
      else if($searchCode == 12){$name="أسهم";}
      else if($searchCode == 13){$name="ذهب";}
      else if($searchCode == 14){$name="عملات";}
      else if($searchCode == 15){$name="سيارات";}
      echo"<tr>
       <input class='choosedName' value='Investment' style='display: none;'>
       <input class='tableName' value='investment' style='display: none;'>
       <td>$resultDataEdit[transactionDate]</td>
       <td>$resultDataEdit[quantaty]</td>
       <td>$resultDataEdit[buyingAmount]</td>
       <td>$resultDataEdit[salesAmount]</td>
       <td>$name</td>
       <td>$resultDataEdit[description]</td>
       <td><button class='btn btn-link Edit' value='$resultDataEdit[investmentId]'>Edit</button></td> 
      </tr>";
     }
    }  
    #Custdy
    else if($resData == 5){
     echo"
      <th>Date</th>
      <th>Discription</th>
      <th>Employee</th>
      <th>Amount</th>
      <th>CashBack</th>
      <th>Edit</th>
     </thead>
     <tbody>";
     $sqlDataEdit="SELECT `custody_Id`, `custodyTransactionDate`, `poNum`, `discription`, `empCode`, `amount`, `cashBack` FROM `custody` WHERE `custodyRef` = 1";
     $queryDataEdit=mysqli_query($link,$sqlDataEdit);
     WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
      $searchCode=$resultDataEdit['empCode'];
      $sqlAccountantCodeData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $searchCode";  
      $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
      $sqlCustomerCodeData="SELECT `customername` FROM `customers` WHERE `customercode` = $searchCode";  
      $queryCustomerCodeData=mysqli_query($link,$sqlCustomerCodeData);
      $sqlSupplierCodeData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $searchCode";  
      $querySupplierCodeData=mysqli_query($link,$sqlSupplierCodeData);
      $sqlEmpCodeData="SELECT `fullname` FROM `users` WHERE `userid` = $searchCode";  
      $queryEmpCodeData=mysqli_query($link,$sqlEmpCodeData);
      $sqlStaffCodeData="SELECT `staffname` FROM `allstaff` WHERE `id` = $searchCode";  
      $queryStaffCodeData=mysqli_query($link,$sqlStaffCodeData);
      if(mysqli_num_rows($queryAccountantCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryAccountantCodeData);        
       $name=$accData['accountName'];
      }
      else if(mysqli_num_rows($queryCustomerCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryCustomerCodeData);        
       $name=$accData['customername'];
      }
      else if(mysqli_num_rows($querySupplierCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($querySupplierCodeData);        
       $name=$accData['suppliername'];
      }
      else if(mysqli_num_rows($queryEmpCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryEmpCodeData);        
       $name=$accData['fullname'];
      }
      else if(mysqli_num_rows($queryStaffCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryStaffCodeData);        
       $name=$accData['staffname'];
      }
      echo"<tr>
       <input class='choosedName' value='Custdy' style='display: none;'>
       <input class='tableName' value='custody' style='display: none;'>
       <td>$resultDataEdit[custodyTransactionDate]</td>
       <td>$resultDataEdit[discription]</td>
       <td>$name</td>
       <td>$resultDataEdit[amount]</td>
       <td>$resultDataEdit[cashBack]</td>
       <td><button class='btn btn-link Edit' value='$resultDataEdit[custody_Id]'>Edit</button></td> 
      </tr>";
     }
    }
    #Advance
    else if($resData == 6){
     echo"
      <th>advanceDate</th>
      <th>empId</th>
      <th>recived</th>
      <th>cashback</th>
      <th>installment</th>
      <th>recevedRef</th>
      <th>Edit</th>
     </thead>
     <tbody>";
     $sqlDataEdit="SELECT `advanceId`, `advanceDate`, `empId`, `recived`, `cashback`, `installment`, `recevedRef`, `advanceRef` FROM `advance`";
     $queryDataEdit=mysqli_query($link,$sqlDataEdit);
     WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
      $searchCode=$resultDataEdit['empId'];
      $sqlAccountantCodeData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $searchCode";  
      $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
      $sqlCustomerCodeData="SELECT `customername` FROM `customers` WHERE `customercode` = $searchCode";  
      $queryCustomerCodeData=mysqli_query($link,$sqlCustomerCodeData);
      $sqlSupplierCodeData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $searchCode";  
      $querySupplierCodeData=mysqli_query($link,$sqlSupplierCodeData);
      $sqlEmpCodeData="SELECT `fullname` FROM `users` WHERE `userid` = $searchCode";  
      $queryEmpCodeData=mysqli_query($link,$sqlEmpCodeData);
      $sqlStaffCodeData="SELECT `staffname` FROM `allstaff` WHERE `id` = $searchCode";  
      $queryStaffCodeData=mysqli_query($link,$sqlStaffCodeData);
      if(mysqli_num_rows($queryAccountantCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryAccountantCodeData);        
       $name=$accData['accountName'];
      }
      else if(mysqli_num_rows($queryCustomerCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryCustomerCodeData);        
       $name=$accData['customername'];
      }
      else if(mysqli_num_rows($querySupplierCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($querySupplierCodeData);        
       $name=$accData['suppliername'];
      }
      else if(mysqli_num_rows($queryEmpCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryEmpCodeData);        
       $name=$accData['fullname'];
      }
      else if(mysqli_num_rows($queryStaffCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryStaffCodeData);        
       $name=$accData['staffname'];
      }
      echo"<tr>
       <input class='tableName' value='advance' style='display: none;'> 
       <input class='choosedName' value='Advance' style='display: none;'>
       <td>$resultDataEdit[advanceDate]</td>
       <td>$name</td>
       <td>$resultDataEdit[recived]</td>
       <td>$resultDataEdit[cashback]</td>
       <td>$resultDataEdit[installment]</td>
       <td>$resultDataEdit[recevedRef]</td>
       <td><button class='btn btn-link Edit' value='$resultDataEdit[advanceId]'>Edit</button></td> 
      </tr>";
     }
    }
    #Manufactured Offer
    else if($resData == 7){
     echo"
      <th>purchasesOrderDate</th>
      <th>purchasesOrderNum</th>
      <th>supplierid</th>
      <th>VAT</th>
      <th>totalAmout</th>
      <th>Edit</th>
     </thead>
     <tbody>";
     $sqlDataEdit="SELECT `purchasesorderid`, `supplierid`, `purchasesOrderNum`, `purchasesOrderDate`, `jobref`, `VAT`, `totalAmout`, `poId` FROM `purchasesorder`";
     $queryDataEdit=mysqli_query($link,$sqlDataEdit);
     WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
      $searchCode=$resultDataEdit['supplierid'];
      include("getAccountantName.php");
      echo"<tr>
       <input class='tableName' value='purchasesorder' style='display: none;'> 
       <input class='choosedName' value='Manufactured Offer' style='display: none;'>
       <td>$resultDataEdit[purchasesOrderDate]</td>
       <td>$resultDataEdit[purchasesOrderNum]</td>
       <td>$name</td>
       <td>$resultDataEdit[VAT]</td>
       <td>$resultDataEdit[totalAmout]</td>
       <td><button class='btn btn-link Edit' value='$resultDataEdit[purchasesorderid]'>Edit</button></td> 
      </tr>";
     }     
    }
    #Purchases Invoice
    else if($resData == 8){
     echo"
      <th>Date</th>
      <th>Number</th>
      <th>Supplier</th>
      <th>Amount</th>
      <th>Vat</th>
      <th>Total</th>
      <th>Type</th>
      <th>Edit</th>
     </thead>
     <tbody>";
     $sqlDataEdit="SELECT `suppliersInvoiceId`,`suppliersInvoiceNumber`,`supplierOrderNum`,`suppliersInvoiceDate`,`supplierCode`,`suppliersInvoiceType`,
     `suppliersInvoiceSupTotal`,`suppliersInvoiceDiscount`,`suppliersInvoiceVat`,`suppliersInvoiceTotal`,`paidAmount`,`paidType`,`paiedStuts` FROM `supplierInvoice`
     WHERE `paiedStuts` = 3";
     $queryDataEdit=mysqli_query($link,$sqlDataEdit);
     WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
      $invType = "Supplier";
      $searchCode=$resultDataEdit['supplierCode'];
      include("getAccountantName.php");
      echo"<tr>
       <input class='tableName' value='supplierInvoice' style='display: none;'> 
       <input class='choosedName' value='Purchases Invoice' style='display: none;'>
       <td>$resultDataEdit[suppliersInvoiceDate]</td>
       <td>$resultDataEdit[suppliersInvoiceNumber]</td>
       <td>$name</td>
       <td>$resultDataEdit[suppliersInvoiceSupTotal]</td>
       <td>$resultDataEdit[suppliersInvoiceVat]</td>
       <td>$resultDataEdit[suppliersInvoiceTotal]</td>
       <td>$invType</td>        
       <td><button class='btn btn-link Edit' value='$resultDataEdit[suppliersInvoiceId]'>Edit</button></td> 
      </tr>";
     }     
    }
    #Sales Invoice
    else if($resData == 9){
     echo"
      <th>Date</th>
      <th>Number</th>
      <th>Customer</th>
      <th>Amount</th>
      <th>Vat</th>
      <th>Total</th>
      <th>Edit</th>
     </thead>
     <tbody>";
     $sqlDataEdit="SELECT `salesInvoiceId`, `salesInvoiceNumber`,`jopRef`,`salesInvoiceDate`,`customerCode`,`salesInvoiceType`,`salesInvoiceSupTotal`,`salesInvoictVat`,
     `totalInvoice`,`invoiceCollectAmount` FROM `salesInvoice`";
     $queryDataEdit=mysqli_query($link,$sqlDataEdit);
     WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
      $searchCode=$resultDataEdit['customerCode'];
      $sqlAccountantCodeData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $searchCode";  
      $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
      $sqlCustomerCodeData="SELECT `customername` FROM `customers` WHERE `customercode` = $searchCode";  
      $queryCustomerCodeData=mysqli_query($link,$sqlCustomerCodeData);
      $sqlSupplierCodeData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $searchCode";  
      $querySupplierCodeData=mysqli_query($link,$sqlSupplierCodeData);
      $sqlEmpCodeData="SELECT `fullname` FROM `users` WHERE `userid` = $searchCode";  
      $queryEmpCodeData=mysqli_query($link,$sqlEmpCodeData);
      $sqlStaffCodeData="SELECT `staffname` FROM `allstaff` WHERE `id` = $searchCode";  
      $queryStaffCodeData=mysqli_query($link,$sqlStaffCodeData);
      if(mysqli_num_rows($queryAccountantCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryAccountantCodeData);        
       $name=$accData['accountName'];
      }
      else if(mysqli_num_rows($queryCustomerCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryCustomerCodeData);        
       $name=$accData['customername'];
      }
      else if(mysqli_num_rows($querySupplierCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($querySupplierCodeData);        
       $name=$accData['suppliername'];
      }
      else if(mysqli_num_rows($queryEmpCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryEmpCodeData);        
       $name=$accData['fullname'];
      }
      else if(mysqli_num_rows($queryStaffCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryStaffCodeData);        
       $name=$accData['staffname'];
      }
      echo"<tr>
       <input class='tableName' value='salesInvoice' style='display: none;'> 
       <input class='choosedName' value='Sales Invoice' style='display: none;'>
       <td>$resultDataEdit[salesInvoiceDate]</td>
       <td>$resultDataEdit[salesInvoiceNumber]</td>
       <td>$name</td>
       <td>$resultDataEdit[salesInvoiceSupTotal]</td>
       <td>$resultDataEdit[salesInvoictVat]</td>
       <td>$resultDataEdit[totalInvoice]</td>
       <td><button class='btn btn-link Edit' value='$resultDataEdit[salesInvoiceId]'>Edit</button></td> 
      </tr>";
     }
    }
    #Adjusting Entry
    else if($resData == 10){
     echo"
      <th>Date</th>
      <th>Number</th>
      <th>debtor</th>
      <th>creditor</th>
      <th>description</th>
      <th>transactionCode</th>
      <th>Edit</th>
     </thead>
     <tbody>";
     $sqlDataEdit="SELECT `financialTransactionsId`, `transactionsYear`, `transactionsMonth`, `transactionNumber`, `transactionsDate`, `debtor`, `creditor`,
     `description`, `transactionCode`, `entryRef` FROM `financialTransactions` WHERE `entryRef` = 'Add Settlement Entry' ";
     $queryDataEdit=mysqli_query($link,$sqlDataEdit);
     WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
      $searchCode=$resultDataEdit['transactionCode'];
      $sqlAccountantCodeData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $searchCode";  
      $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
      $sqlCustomerCodeData="SELECT `customername` FROM `customers` WHERE `customercode` = $searchCode";  
      $queryCustomerCodeData=mysqli_query($link,$sqlCustomerCodeData);
      $sqlSupplierCodeData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $searchCode";  
      $querySupplierCodeData=mysqli_query($link,$sqlSupplierCodeData);
      $sqlEmpCodeData="SELECT `fullname` FROM `users` WHERE `userid` = $searchCode";  
      $queryEmpCodeData=mysqli_query($link,$sqlEmpCodeData);
      $sqlStaffCodeData="SELECT `staffname` FROM `allstaff` WHERE `id` = $searchCode";  
      $queryStaffCodeData=mysqli_query($link,$sqlStaffCodeData);
      if(mysqli_num_rows($queryAccountantCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryAccountantCodeData);        
       $name=$accData['accountName'];
      }
      else if(mysqli_num_rows($queryCustomerCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryCustomerCodeData);        
       $name=$accData['customername'];
      }
      else if(mysqli_num_rows($querySupplierCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($querySupplierCodeData);        
       $name=$accData['suppliername'];
      }
      else if(mysqli_num_rows($queryEmpCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryEmpCodeData);        
       $name=$accData['fullname'];
      }
      else if(mysqli_num_rows($queryStaffCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryStaffCodeData);        
       $name=$accData['staffname'];
      }
      echo"<tr>
       <input class='tableName' value='financialTransactions' style='display: none;'> 
       <input class='choosedName' value='Adjusting Entry' style='display: none;'>
       <td>$resultDataEdit[transactionsDate]</td>
       <td>$resultDataEdit[transactionNumber]</td>
       <td>$resultDataEdit[debtor]</td>
       <td>$resultDataEdit[creditor]</td>
       <td>$resultDataEdit[description]</td>
       <td>$name</td>
       <td><button class='btn btn-link Edit' value='$resultDataEdit[financialTransactionsId]'>Edit</button></td> 
      </tr>";
     }
    }
    #Billing in Process
    else if($resData == 11){
     echo"
      <th>Date</th>
      <th>Customer</th>
      <th>Amount</th>
      <th>Edit</th>
     </thead>
     <tbody>";
     $sqlDataEdit="SELECT `salesInvoiceDraftId`, `jopRef`, `customerCode`, `salesInvoiceSupTotal`, `salesInvoiceDate`, `itemsQya`, `itemRowId`, `ref` FROM `salesInvoiceDraft` WHERE `ref` = 1";
     $queryDataEdit=mysqli_query($link,$sqlDataEdit);
     WHILE($resultDataEdit=mysqli_fetch_assoc($queryDataEdit)){
      $searchCode=$resultDataEdit['customerCode'];
      $sqlAccountantCodeData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $searchCode";  
      $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
      $sqlCustomerCodeData="SELECT `customername` FROM `customers` WHERE `customercode` = $searchCode";  
      $queryCustomerCodeData=mysqli_query($link,$sqlCustomerCodeData);
      $sqlSupplierCodeData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $searchCode";  
      $querySupplierCodeData=mysqli_query($link,$sqlSupplierCodeData);
      $sqlEmpCodeData="SELECT `fullname` FROM `users` WHERE `userid` = $searchCode";  
      $queryEmpCodeData=mysqli_query($link,$sqlEmpCodeData);
      $sqlStaffCodeData="SELECT `staffname` FROM `allstaff` WHERE `id` = $searchCode";  
      $queryStaffCodeData=mysqli_query($link,$sqlStaffCodeData);
      if(mysqli_num_rows($queryAccountantCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryAccountantCodeData);        
       $name=$accData['accountName'];
      }
      else if(mysqli_num_rows($queryCustomerCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryCustomerCodeData);        
       $name=$accData['customername'];
      }
      else if(mysqli_num_rows($querySupplierCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($querySupplierCodeData);        
       $name=$accData['suppliername'];
      }
      else if(mysqli_num_rows($queryEmpCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryEmpCodeData);        
       $name=$accData['fullname'];
      }
      else if(mysqli_num_rows($queryStaffCodeData) > 0 ){
       $accData=mysqli_fetch_assoc($queryStaffCodeData);        
       $name=$accData['staffname'];
      }
      echo"<tr>
       <input class='tableName' value='salesInvoiceDraft' style='display: none;'> 
       <input class='choosedName' value='Billing in Process' style='display: none;'>
       <td>$resultDataEdit[salesInvoiceDate]</td>
       <td>$name</td>
       <td>$resultDataEdit[salesInvoiceSupTotal]</td>
       <td><button class='btn btn-link Edit' value='$resultDataEdit[salesInvoiceDraftId]'>Edit</button></td> 
      </tr>";
     }
    }
   ?>
  </tbody>
 </table>
</div>
<!-- Modal -->
<div class="modal fade" id="acountantEdit" aria-hidden="true" aria-labelledby="edtinigModalData" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title" id="edtinigModalData"></h5>
    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   </div>
   <div class="modal-body edtinigData"></div>
  </div>
 </div>
</div>
<div class='table-responsive-lg'>
 <?php
  date_default_timezone_set("Africa/Cairo");
  include_once("connection.php");
  $table =$_POST['myTable'];
  $rowid=$_POST['rowId'];
  # Partnership Ratio
  if($table == "partnershipRatio"){
   $sqlPartnersDataEdit="SELECT `partnershipRatioId`, `partnersName`, `partnerPercentage` FROM `partnershipRatio` WHERE  `partnershipRatioId` = $rowid";
   $queryPartnersDataEdit=mysqli_query($link,$sqlPartnersDataEdit);
   $resultPartnersDataEdit=mysqli_fetch_assoc($queryPartnersDataEdit);
   $partnarCode=$resultPartnersDataEdit['partnersName'];
   $sqlPartnersData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = '$partnarCode' ";
   $queryPartnersData=mysqli_query($link,$sqlPartnersData)or die("ERROR LOA_S:01");
   $resultPartnersData=mysqli_fetch_assoc($queryPartnersData);    
   $avalibalePer = 100;
   echo "<div class='table-responsive-lg'>
    <table class='table table-sm w-75 table-borderless'>
     <thead class='text-center'>
      <th>Partner Name</th>
      <th>Partner Percentage</th>
     </thead>
     <tbody>
      <tr>
       <td>
        <select class='form-control' id='oldPartnerName' disabled>
         <option value='$partnarCode'>$resultPartnersData[accountName]</option>
        </select>
       </td>
       <td>
        <select class='form-control' id='OldpartnerPer' disabled>
         <option value='$resultPartnersDataEdit[partnerPercentage]'>$resultPartnersDataEdit[partnerPercentage]</option>
        </select>
       </td>
      </tr>
      <tr>
       <td>
        <select class='form-control' id='newPartnerName'><option value='$partnarCode'>$resultPartnersData[accountName]</option>";         
        $sqlFirstLevelData="SELECT `accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 12 AND `accountCode` LIKE '5%' AND `accountCode` != '$partnarCode'";
        $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:01");
        while($firstLevel=mysqli_fetch_assoc($queryFirstLevelData)){
         echo"<option value='$firstLevel[accountCode]'>$firstLevel[accountName]</option>";
        }
        echo"</select>
       </td>
       <td>
        <select class='form-control' id='newPartnerPer'><option value='$resultPartnersDataEdit[partnerPercentage]'>$resultPartnersDataEdit[partnerPercentage]</option>";
        for ($i=1; $i <= $avalibalePer ; $i++) {echo "<option value='$i'>$i</option>";}
        echo"</select>
       </td>
      </tr>
     </tbody>
    </table>
    <button class='btn btn-success w-100' id='saveEditPartner' value='$rowid'>Save</button>
   </div>";
  }
  else if($table == "cash_transaction"){
   $sqlDataEdit="SELECT `transactionDate`,`income`,`withdrawal`,`description`,`account`,`poNum`,`invNumber`,`statmentRef`,`tableName`,`tableRowId`,`ftId`
   FROM `cash_transaction` WHERE `cash_transaction_id` = $rowid";
   $queryDataEdit=mysqli_query($link,$sqlDataEdit); 
   $resultDataEdit=mysqli_fetch_assoc($queryDataEdit);
   $sqlCashCodeS="SELECT `accountName` FROM `accountantcode` WHERE `accountCode`= $resultDataEdit[statmentRef]";
   $queryCashCodeS=mysqli_query($link,$sqlCashCodeS)or die("ERROR LOA_S:01");
   $resultCashCodeS=mysqli_fetch_assoc($queryCashCodeS);
   $searchCode=$resultDataEdit['account'];
   include_once("getAccountantName.php");
   if( $resultDataEdit['income'] > 0){
    $amount=$resultDataEdit['income'];
    $typyCol="<td><input type='text' class='cashColuman' value='income' style='display: none;'></td>";
   }
   else if( $resultDataEdit['withdrawal'] > 0){
    $amount=$resultDataEdit['withdrawal'];
    $typyCol="<td><input type='text' class='cashColuman' value='withdrawal' style='display: none;'></td>";
   }
   if($resultDataEdit['tableName'] == "Cash"){
    $accSelect=" <select class='form-control accountName'>";
   } 
   else{
    $accSelect=" <select class='form-control accountName' disabled>";
   }
   # Fixed Name To Finincal Transaction
   $ftDate=$resultDataEdit['transactionDate'];
   $ftdiscr=$resultDataEdit['description'];
   $ftCode=$resultDataEdit['account'];
   $entryNumber=$resultDataEdit['ftId'];
   $subTable=$resultDataEdit['tableName'];
   $subTableId=$resultDataEdit['tableRowId'];
   $nextTab="<td><input type='text' id='nTable' value='$subTable' style='display: none;'></td>";
   $nextTabId="<td><input type='text' id='nTableId' value='$subTableId' style='display: none;'></td>"; 
   echo"<table class='table table-sm w-100 table-borderless'>
    <thead class='text-center'>
     <th>Date</th>
     <th>Treasury</th>
     <th>Account</th>
     <th>Amount</th>
     <th>Description</th>        
     <th style='display: none;'>Id</th>
     <th style='display: none;'>old</th>
     <th style='display: none;'>col</th>
     <th style='display: none;'>nTab</th>     
     <th style='display: none;'>nTabI</th>
    </thead>
    <tbody>
     <td><input type='date' id='receDate' class='form-control' value='$ftDate'></td>
     <td>
      <select class='form-control' id='trasuty' disabled>
       <option value='$resultDataEdit[statmentRef]'>$resultCashCodeS[accountName]</option>
      </select>
     </td>
     <td>$accSelect";
     $sqlFirstLevelData2="SELECT `accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 12";
     $queryFirstLevelData2=mysqli_query($link,$sqlFirstLevelData2)or die("ERROR LOA_S:01");
     echo "<option value='$resultDataEdit[account]'>$name</option>";
     while($firstLevel2=mysqli_fetch_assoc($queryFirstLevelData2)){
      echo"<option value='$firstLevel2[accountCode]'>$firstLevel2[accountName]</option>";
     }
     echo"</select></td>    
     <td><input type='number' id='receivAmount' class='form-control' value='$amount'></td>
     <td><input type='text' id='receivDescription' class='form-control' value='$ftdiscr'></td>    
     <td><input type='number' id='cashRowId' value='$rowid' style='display: none;'></td>
     <td><input type='number' id='cashOldAmount' value='$amount' style='display: none;'></td>
     $typyCol
     $nextTab
     $nextTabId
    </tbody>
   </table>
   <div  style='display: none;'>
    <table class='table table-sm w-100 table-borderless'>
     <thead class='text-center'>
      <th>SN</th>
      <th>Date</th>
      <th>Account</th>
      <th>Debtor</th>
      <th>Creditor</th>
      <th>Description</th>        
      <th>info</th>
     </thead>
     <tbody>";
     // Financial Transactions Id
     $sqlFinancialTransactionsId="SELECT `financialTransactionsId` FROM `financialTransactions`  
     WHERE `transactionsDate` = '$ftDate' AND `transactionNumber` = '$entryNumber' ";
     $queryFinancialTransactionsId=mysqli_query($link,$sqlFinancialTransactionsId);
     $sn=0;
     while($resultFinancialTransactionsId=mysqli_fetch_assoc($queryFinancialTransactionsId)){
      $sn++;
      $entryId=$resultFinancialTransactionsId['financialTransactionsId'];
      $sqlFinancialData="SELECT `financialTransactionsId`, `transactionsYear`, `transactionsMonth`, `transactionNumber`, `transactionsDate`, `debtor`, `creditor`,
      `description`, `transactionCode`, `entryRef` FROM `financialTransactions` WHERE `financialTransactionsId` = $entryId ";
      $queryFinancialData=mysqli_query($link,$sqlFinancialData);
      $resultFinancialData=mysqli_fetch_assoc($queryFinancialData);
      if($resultFinancialData['debtor'] > 0){
       $debet="<input type='number' id='debtorAmount$sn' value='$resultFinancialData[debtor]' class='form-control amount'>";
       $cridet="<input type='number' id='creditorAmount$sn' value='$resultFinancialData[creditor]' class='form-control'>";
      }
      else if($resultFinancialData['creditor'] > 0){
       $debet="<input type='number' id='debtorAmount$sn' value='$resultFinancialData[debtor]' class='form-control'>";
       $cridet="<input type='number' id='creditorAmount$sn' value='$resultFinancialData[creditor]' class='form-control amount'>";
      }
      if($resultFinancialData['transactionCode'] == $resultDataEdit['account']){
       $disc="<input type='text' id='receivDescription$sn' class='form-control tableDiscr' value='$resultFinancialData[description]'>";
       $finincalAccount="<input type='text' class='form-control tableAccount' id='accountName$sn' value='$resultFinancialData[transactionCode]'>";
      }
      else{
       $disc="<input type='text' id='receivDescription$sn' class='form-control' value='$resultFinancialData[description]'>";
       $finincalAccount="<input type='text' class='form-control cashAccount' id='accountName$sn' value='$resultFinancialData[transactionCode]'>";
      }
      $searchCode=$resultFinancialData['transactionCode'];
      include("getAccountantName.php");
      echo"<tr>
       <td>$sn</td>
       <td><input type='date' id='receDate$sn' value='$resultFinancialData[transactionsDate]' class='form-control editData'></td>
       <td>$finincalAccount</td>
       <td>$debet</td>
       <td>$cridet</td>
       <td>$disc</td> 
       <td><input type='text' id='info$sn' class='form-control' value='$entryId'></td> 
      </tr>";
     }
     echo"
      </tbody>
     </table>
     <p>Sub Table</p>
     <table class='table table-sm w-100 table-borderless'>
      <thead class='text-center'>
       <th>Row Id</th>
       <th>Date</th>
       <th>Amount</th>
       <th>Description</th>        
      </thead>
      <tbody>";     
      include("nextTableData.php");
      echo"
       </tbody>
      </table>
     </div>
     <button class='btn btn-success' id='editDataSave'>Save Edit</button>";
  }
  else if($table == "investment"){
   $sqlInvestmentEdit="SELECT `investmentId`,`transactionDate`,`quantaty`,`buyingAmount`,`salesAmount`,`investmentGroup`,`description` FROM `investment`
   WHERE `investmentId` = $rowid";
   $queryInvestmentEdit=mysqli_query($link,$sqlInvestmentEdit);
   $resultInvestmentEdit=mysqli_fetch_assoc($queryInvestmentEdit);
   if($resultInvestmentEdit['buyingAmount'] > 0){
    $amount=$resultInvestmentEdit['buyingAmount'];
    echo "<input type='text' class='form-control investmentCoulm' value='buyingAmount' style='display: none;'>";
   }
   else if($resultInvestmentEdit['salesAmount'] > 0){
    $amount=$resultInvestmentEdit['salesAmount'];
    echo "<input type='text' class='form-control investmentCoulm' value='salesAmount' style='display: none;'>";
   }
   if($resultInvestmentEdit['investmentGroup'] == 10){$groupSeleact="<option value='10'>أراضى</option>";}
   else if($resultInvestmentEdit['investmentGroup'] == 11){$groupSeleact="<option value='11'>عقارات</option>";}
   else if($resultInvestmentEdit['investmentGroup'] == 12){$groupSeleact="<option value='12'>أسهم</option>";}
   else if($resultInvestmentEdit['investmentGroup'] == 13){$groupSeleact="<option value='13'>ذهب</option>";}
   else if($resultInvestmentEdit['investmentGroup'] == 14){$groupSeleact="<option value='14'>عملات</option>";}
   else if($resultInvestmentEdit['investmentGroup'] == 15){$groupSeleact="<option value='15'>سيارات</option>";}
   echo"<table class='table table-sm w-100 table-borderless'>
    <thead>
     <th>Date</th>
     <th>Amount</th>
     <th>Quantaty</th>
     <th>investmentGroup</th>
     <th>Description</th>
    </thead>
    <tbody>
     <tr>
     <td class='col-2'><input type='date' id='investmentDateOld' class='form-control' readonly value='$resultInvestmentEdit[transactionDate]'></td>
     <td class='col-2'><input type='number' id='investmentAmountOld' class='form-control' readonly value='$amount'></td>
     <td class='col-1'><input type='number' id='investmentQunOld' class='form-control' readonly value='$resultInvestmentEdit[quantaty]'></td>
     <td class='col-2'><select id='investmentGroupOld' class='form-control'>$groupSeleact</select></td>
     <td class='col-4'><input type='text' class='form-control' id='investmentDescriptionOld'  readonly value='$resultInvestmentEdit[description]'></td>
    </tr>
     <tr>
     <td class='col-2'><input type='date' id='investmentDateNew' class='form-control' value='$resultInvestmentEdit[transactionDate]'></td>
     <td class='col-2'><input type='number' id='investmentAmountNew' class='form-control' value='$amount'></td>
     <td class='col-1'><input type='number' id='investmentQunNew' class='form-control' value='$resultInvestmentEdit[quantaty]'></td>
     <td class='col-2'><select id='investmentGroupNew' class='form-control'>
      $groupSeleact
      <option value='10'>أراضى</option>
      <option value='11'>عقارات</option>
      <option value='12'>أسهم</option>
      <option value='13'>ذهب</option>
      <option value='14'>عملات</option>
      <option value='15'>سيارات</option>
     </select></td>
     <td class='col-4'><input type='text' class='form-control' id='investmentDescriptionNew' value='$resultInvestmentEdit[description]'></td>
    </tr>
    </tbody>
   </table>
   <button class='btn btn-success' id='editInvestmentSave' value='$rowid'>Save Edit</button>";
  }
  else if($table == "custody"){
   /*
    echo"<table class='table table-sm w-100 table-borderless'>
    <thead>
     <th>Date</th>
     <th>Discription</th>
     <th>Employee</th>
     <th>Amount</th>
    </thead>
    <tbody>";
    $sqlCustodyDataEdit="SELECT `custody_Id`, `custodyTransactionDate`, `poNum`, `discription`, `empCode`, `amount`, `cashBack` 
    FROM `custody` WHERE `custody_Id` = $rowid";
    $queryCustodyDataEdit=mysqli_query($link,$sqlCustodyDataEdit);
    $resultCustodyDataEdit=mysqli_fetch_assoc($queryCustodyDataEdit);
    if($resultCustodyDataEdit['amount'] > 0){
     $amount=$resultCustodyDataEdit['amount'];
     echo "<input type='text' class='form-control custdyCoulm' value='amount' style='display: none;'>";
    }
    else if($resultCustodyDataEdit['cashBack'] > 0){
     $amount=$resultCustodyDataEdit['cashBack'];
     echo "<input type='text' class='form-control custdyCoulm' value='cashBack' style='display: none;'>";
    }
    $searchCode=$resultCustodyDataEdit['empCode'];
    include("getAccountantName.php");
    echo"
     <tr>
      <td> <input type='date' class='form-control' id='oldDate' value='$resultCustodyDataEdit[custodyTransactionDate]' readonly></td>
      <td><input type='text' class='form-control' id='oldDisc' value='$resultCustodyDataEdit[discription]' readonly></td>
      <td><input type='text' class='form-control' value='$name' readonly></td>
      <td><input type='number' class='form-control' id='oldAmount' value='$amount' readonly></td>
     </tr>
     <tr>
      <td> <input type='date' class='form-control' id='newDate' value='$resultCustodyDataEdit[custodyTransactionDate]'></td>
      <td><input type='text' class='form-control' id='newDisc' value='$resultCustodyDataEdit[discription]'></td>
      <td><input type='text' class='form-control' value='$name' readonly></td>
      <td><input type='number' class='form-control' id='newAmount' value='$amount'></td>
     </tr>
    </tbody>
    </table>
    <button class='btn btn-success' id='editCustdySave' value='$resultCustodyDataEdit[custody_Id]'>Save</button>";
   */
  }
  else if($table == "advance"){}
  else if($table == "purchasesorder"){
   /*
   echo"<table class='table table-sm w-100 table-borderless'>
    <thead>
     <th>purchasesOrderDate</th>
     <th>purchasesOrderNum</th>
     <th>supplierid</th>
     <th>VAT</th>
     <th>totalAmout</th>
    </thead>
    <tbody>";
     $sqlDataEdit="SELECT `purchasesorderid`, `supplierid`, `purchasesOrderNum`, `purchasesOrderDate`, `jobref`, `VAT`, `totalAmout`, `poId` FROM `purchasesorder`";
     $queryDataEdit=mysqli_query($link,$sqlDataEdit);
     $resultDataEdit=mysqli_fetch_assoc($queryDataEdit);
      $invviceId=$resultDataEdit['purchasesorderid'];
      $searchCode=$resultDataEdit['supplierid'];
      include("getAccountantName.php");
      echo"<tr>
       <input id='tableName' value='purchasesorder' style='display: none;'> 
       <input id='choosedName' value='Manufactured Offer' style='display: none;'>
       <td>$resultDataEdit[purchasesOrderDate]</td>
       <td>$resultDataEdit[purchasesOrderNum]</td>
       <td>$name</td>
       <td>$resultDataEdit[VAT]</td>
       <td>$resultDataEdit[totalAmout]</td>
       <td><button class='btn btn-link Edit' value='$resultDataEdit[purchasesorderid]'>Edit</button></td> 
      </tr>
     </tbody>
    </table>
    <button class='btn btn-success' id='editMOSave' value='$resultDataEdit[purchasesorderid]'>Save</button>";
   */
  }
  else if($table == "supplierInvoice"){
   echo"<table class='table table-sm w-100 table-borderless'>
    <thead>
     <th class='col-sm-2 text-center'>Date</th>
     <th class='col-sm-2 text-center'>Invoice Num</th>
     <th class='col-sm-2 text-center'>supplier</th>
     <th class='col-sm-2 text-center'>Amout</th>
     <th class='col-sm-2 text-center'>VAT</th>
     <th class='col-sm-2 text-center'>Total</th>
    </thead>
    <tbody>";
     $sqlDataEdit="SELECT `suppliersInvoiceId`,`suppliersInvoiceNumber`,`suppliersInvoiceDate`, `supplierCode`, `suppliersInvoiceType`,`suppliersInvoiceSupTotal`,
     `suppliersInvoiceVat`,`suppliersInvoiceTotal` FROM `supplierInvoice` WHERE `suppliersInvoiceId` = $rowid";
     $queryDataEdit=mysqli_query($link,$sqlDataEdit);
     $resultDataEdit=mysqli_fetch_assoc($queryDataEdit);
     $searchCode=$resultDataEdit['supplierCode'];
     include("getAccountantName.php");
     echo"<tr>
      <td><input type='date' class='form-control' id='oldDate' value='$resultDataEdit[suppliersInvoiceDate]' readonly></td>
      <td><input type='text' class='form-control' id='oldNumber' value='$resultDataEdit[suppliersInvoiceNumber]' readonly></td>
      <td><select class='form-control' disabled><option value='$searchCode'>$name</option></select></td>
      <td><input type='number' class='form-control' id='oldAmount' value='$resultDataEdit[suppliersInvoiceSupTotal]' readonly></td>
      <td><input type='number' class='form-control' id='oldVat' value='$resultDataEdit[suppliersInvoiceVat]' readonly></td>
      <td><input type='number' class='form-control' id='oldTotal' value='$resultDataEdit[suppliersInvoiceTotal]' readonly></td>
     </tr>
    ";
   $sqlInvoiceItems="SELECT `supplierInvoiceDataId`,`supplierInvoiceNumber`,`ItemRowId`,`supplierInvoiceCount`,`supplierInvoiceUnitPrice`,`supplierInvoiceTotalItems` 
   FROM `supplierInvoiceData` WHERE `supplierInvoiceNumber` = $rowid";
   $queryInvoiceItems=mysqli_query($link,$sqlInvoiceItems);
   if(mysqli_num_rows($queryInvoiceItems) == 0){
    echo "</tbody>
     </table>
     <center>
     <button class='btn btn-success' id='delInv' value='$rowid'>Delete</button>
    <center>";   
   }
   else{
    echo"<tr>
     <td><input type='date' class='form-control' id='newDate' value='$resultDataEdit[suppliersInvoiceDate]'></td>
     <td><input type='text' class='form-control' id='newNumber' value='$resultDataEdit[suppliersInvoiceNumber]'></td>
      <td><select class='form-control' disabled><option value='$searchCode'>$name</option></select></td>
     <td><input type='number' class='form-control' id='newAmount' value='$resultDataEdit[suppliersInvoiceSupTotal]'></td>
     <td><input type='number' class='form-control' id='newVat' value='$resultDataEdit[suppliersInvoiceVat]'></td>
     <td><input type='number' class='form-control' id='newTotal' readonly value='$resultDataEdit[suppliersInvoiceTotal]'></td>
    </tr>
    </tbody>
    </table>
     <center>
     <button class='btn btn-success' id='saveEditSubSuplierInv' value='$rowid'>Save</button>
    <center>
     <h5 class='text-left'>Contant Items</h5>
     <table class='table table-sm table-bordered'>
      <thead class='text-center bg-secondary'>
       <th>Items</th>
       <th>Quantity</th>
       <th>Unit Price</th>
       <th>Total</th>
      </thead>
      <tbody>";   
      while($resultInvoiceItems=mysqli_fetch_assoc($queryInvoiceItems)){
      $subItemsName=$resultInvoiceItems['ItemRowId'];
      $getAllItemCode2="SELECT `descriptionname` FROM `stockitems` WHERE `description` = $subItemsName";
      $queryAllItemCode2=mysqli_query($link,$getAllItemCode2)or die("ERROR :01-AIC_AIDL_S");
      $resAllItemCode2=mysqli_fetch_assoc($queryAllItemCode2);
      echo"<tr>
       <td>$resAllItemCode2[descriptionname]</td>
       <td>$resultInvoiceItems[supplierInvoiceCount]</td>
       <td>$resultInvoiceItems[supplierInvoiceUnitPrice]</td>
       <td>$resultInvoiceItems[supplierInvoiceTotalItems]</td>
      </tr>";
     }        
     echo"</tbody>
     </table>
     <center>
     <button class='btn btn-success' id='editSubSuplierInv' value='$rowid'>Edit Items</button>
    <center>";
   }
  }
  else if($table == "salesInvoice"){  
  }
  else if($table == "financialTransactions"){}
 ?>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  $("#receivAmount").keyup(function(){
   var newVal = $(this).val();
   $(".amount").val(newVal);
  });
  $("#receDate").keyup(function(){
   var newDate = $(this).val();
   $(".editData").val(newDate);
  });
  $("#receDate").change(function(){
   var dateNew = $(this).val();
   $(".editData").val(dateNew);
  });
  $("#receivDescription").keyup(function(){
   var newDisc = $(this).val();
   $(".tableDiscr").val(newDisc);
  });
  $(".accountName").change(function(){
   var accountNew = $(this).val();
   $(".tableAccount").val(accountNew);
  });
  $("#trasuty").change(function(){
   var cashNew = $(this).val();
   $(".cashAccount").val(cashNew);
  });
 $("#newAmount").change(function(){
  var invNewAmount = $("#newAmount").val();
  var invNewVat = $("#newVat").val();
  var invTotal = (Number(invNewAmount) + Number(invNewVat));
  $("#newTotal").val(invTotal);
 });
 $("#newVat").change(function(){
  var invNewAmount2 = $("#newAmount").val();
  var invNewVat2 = $("#newVat").val();
  var invTotal2 = (Number(invNewAmount2) + Number(invNewVat2));
  $("#newTotal").val(invTotal2);
 });
 $("#editDataSave").click(function(){
  $("#acountantEdit").modal('toggle');
   var cashRowId=$("#cashRowId").val();
   var cashColum=$(".cashColuman").val();
   var nAmount=$("#receivAmount").val();
   var oAmount=$("#cashOldAmount").val();
   var nDate=$("#receDate").val();
   var nAccount=$(".accountName").val();
   var cashAccount=$("#trasuty").val();
   var cashDisc=$("#receivDescription").val();
   var ftRowId1=$("#info1").val();
   var ftDate1=$("#receDate1").val();
   var depit1=$("#debtorAmount1").val();
   var credit1=$("#creditorAmount1").val();
   var ftAccount1=$("#accountName1").val();
   var ftDisc1=$("#receivDescription1").val();
   var ftRowId2=$("#info2").val();
   var ftDate2=$("#receDate2").val();
   var depit2 = $("#debtorAmount2").val();
   var credit2=$("#creditorAmount2").val();
   var ftAccount2=$("#accountName1").val();
   var ftDisc2=$("#receivDescription2").val();
   var nextTableName = $("#nTable").val();
   var nextTableRowId = $("#nTableId").val();
   $.ajax({
    url:'dist/php/saveEditCash.php',
    type:"POST",
    data:{rcashRowId:cashRowId, rcashColum:cashColum, rnAmount:nAmount, roAmount:oAmount, rnDate:nDate, rnAccount:nAccount, rcashAccount:cashAccount, rcashDisc:cashDisc,rftRowId1:ftRowId1,rftDate1:ftDate1, rdepit1:depit1, rcredit1:credit1, rftAccount1:ftAccount1, rftDisc1:ftDisc1, rftRowId2:ftRowId2, rftDate2:ftDate2, rdepit2:depit2, rcredit2:credit2, rftAccount2:ftAccount2, rftDisc2:ftDisc2, rnextTableName:nextTableName, rnextTableRowId:nextTableRowId },
    success: function(cashEditData){
     alert("Data Edit Saved");
      var returnDataEdit = 2;
   $.ajax({
    url:'dist/php/accountantDataEdit.php',
    type:"POST",
    data:{editType:returnDataEdit},
    success: function(getAccountantData){
     $(".addDiv").html('');
     $(".addDiv").html(getAccountantData)
    }
   });

    }
   });
  });
  $("#editInvestmentSave").click(function(){
   $("#acountantEdit").modal('toggle');
   var investRowId=$(this).val();
   var investColum=$(".investmentCoulm").val();
   var oDate=$("#investmentDateOld").val();
   var oAmount=$("#investmentAmountOld").val();
   var oQun=$("#investmentQunOld").val();
   var oGroup=$("#investmentGroupOld").val();
   var oDisc=$("#investmentDescriptionOld").val();  
   var nDate=$("#investmentDateNew").val();
   var nAmount=$("#investmentAmountNew").val();
   var nQun=$("#investmentQunNew").val();
   var nGroup=$("#investmentGroupNew").val();
   var nDisc=$("#investmentDescriptionNew").val();
   $.ajax({
    url:'dist/php/saveEditInvest.php',
    type:"POST",
    data:{rRowId:investRowId, rColum:investColum, roDate:oDate, roAmount:oAmount, roQun:oQun, roGroup:oGroup,roDisc:oDisc, rnDate:nDate, rnAmount:nAmount, rnQun:nQun, rnGroup:nGroup,rnDisc:nDisc},
    success: function(investEditData){
     alert("Data Edit Saved");
     var returnDataEdit = 4;
   $.ajax({
    url:'dist/php/accountantDataEdit.php',
    type:"POST",
    data:{editType:returnDataEdit},
    success: function(getAccountantData){
     $(".addDiv").html('');
     $(".addDiv").html(getAccountantData)
    }
   });

    }
   });
  });
  $("#delInv").click(function(){
   $("#acountantEdit").modal('toggle');
   var supInvRowId=$(this).val();
   $.ajax({
    url:'dist/php/saveDelSupplierStockInv.php',
    type:"POST",
    data:{rRowId:supInvRowId},
    success: function(supInvDeleteData){
     alert("Data Delete");
    }
   });
  });
$("#saveEditSubSuplierInv").click(function(){
  $("#acountantEdit").modal('toggle');
  var supInvRowId=$(this).val();  
var oDate=$("#oldDate").val();
var oNumber=$("#oldNumber").val();
var oAmount=$("#oldAmount").val();
var oVat=$("#oldVat").val();
var oTotal=$("#oldTotal").val();  
var nDate=$("#newDate").val();
var nNumber=$("#newNumber").val();
var nAmount=$("#newAmount").val();
var nVat=$("#newVat").val();
var nTotal=$("#newTotal").val();
   $.ajax({
    url:'dist/php/saveEditSupplierStockInv.php',
    type:"POST",
    data:{rRowId:supInvRowId, roDate:oDate, roNumber:oNumber, roAmount:oAmount, roVat:oVat, roTotal:oTotal, rnDate:nDate, rnNumber:nNumber, rnAmount:nAmount, rnVat:nVat, rnTotal:nTotal
    },
    success: function(supInvEditData){
     alert("Edit Data Saved");
    }
   });
  });
$("#editSubSuplierInv").click(function(){
   //$("#acountantEdit").modal('toggle');
   var supInvRowId=$(this).val();
   $.ajax({
    url:'dist/php/editSupplierStockInv.php',
    type:"POST",
    data:{rRowId:supInvRowId},
    success: function(supInvEditData){
     $(".edtinigData").html('');
     $(".edtinigData").html(supInvEditData);
     //alert("Data Delete");
    }
   });
  });
  $("#saveEditPartner").click(function(){
   $("#acountantEdit").modal('toggle');
   var investRowId=$(this).val();
   var oName=$("#oldPartnerName").val();
   var oPer=$("#OldpartnerPer").val();
   var nName=$("#newPartnerName").val();
   var nPer=$("#newPartnerPer").val();
   $.ajax({
    url:'dist/php/saveEditPartner.php',
    type:"POST",
    data:{rRowId:investRowId, roName:oName, roPer:oPer, rnName:nName, rnPer:nPer},
    success: function(partnerEditData){
     alert("Data Edit Saved");
     var returnDataEdit = 1;
     $.ajax({
      url:'dist/php/accountantDataEdit.php',
      type:"POST",
      data:{editType:returnDataEdit},
      success: function(getAccountantData){
       $(".addDiv").html('');
       $(".addDiv").html(getAccountantData)
      }
     });
    }
   });
  });
 


 });//Document
</script>
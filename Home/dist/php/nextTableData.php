<?php
 #advance 
 if($subTable == "advance"){
  $sqlNextTableData="SELECT `advanceId` as rowId, `advanceDate` as myDate, `empId`, `recived`, `cashback`, `installment`, `recevedRef`, `advanceRef` FROM `advance` 
  WHERE `advanceId` = $subTableId";
  $queryNextTableData=mysqli_query($link,$sqlNextTableData)or die("ERROR LOA_S:01");
  while($resultNextTableData=mysqli_fetch_assoc($queryNextTableData)){
   echo"<tr>
    <td>$resultNextTableData[rowId]</td>
    <td><input type='date' id='tableDate' class='form-control editData' value='$resultNextTableData[myDate]'></td>
    <td><input type='number' id='tableAmount' value='$amount' class='form-control amount'></td>
    <td></td>
   </tr>";
  }
 }
 # Withdrawal Stock Suppliers Invoice
 else if($subTable == "supplierInvoice"){
  $sqlNextTableData="SELECT `suppliersInvoiceId` as rowId, `suppliersInvoiceNumber`, `supplierOrderNum`, `suppliersInvoiceDate`as myDate, `supplierCode`,
  `suppliersInvoiceType`, `suppliersInvoiceSupTotal`, `suppliersInvoiceDiscount`, `suppliersInvoiceVat`, `suppliersInvoiceTotal`, `paidAmount`, `paidType`, `paiedStuts` 
  FROM `supplierInvoice`  
  WHERE `suppliersInvoiceId` = '$subTableId'";
  $queryNextTableData=mysqli_query($link,$sqlNextTableData)or die("ERROR LOA_S:01");
  while($resultNextTableData=mysqli_fetch_assoc($queryNextTableData)){
   echo"<tr>
    <td>$resultNextTableData[rowId]</td>
    <td><input type='date' id='tableDate' class='form-control editData' value='$resultNextTableData[myDate]'></td>
    <td><input type='number' id='tableAmount' value='$amount' class='form-control amount'></td>
    <td><input type='number' id='tableAmount' value='$resultNextTableData[paidAmount]' class='form-control'></td>  
   </tr>";
  }
 }
 # Custy Cashback
 else if($subTable == "custody"){
  $sqlNextTableData="SELECT `custody_Id` as rowId, `custodyTransactionDate` as myDate,`discription`,`cashBack` FROM `custody` 
  WHERE `custody_Id` = $subTableId";
  $queryNextTableData=mysqli_query($link,$sqlNextTableData)or die("ERROR LOA_S:01");
  while($resultNextTableData=mysqli_fetch_assoc($queryNextTableData)){
   echo"<tr>
    <td>$resultNextTableData[rowId]</td>
    <td><input type='date' id='tableDate' class='form-control editData' value='$resultNextTableData[myDate]'></td>
    <td><input type='number' id='tableAmount' value='$resultNextTableData[cashBack]' class='form-control amount'></td>  
    <td></td>
   </tr>";
  }
 }
 # Collect Extract
 else if($subTable == "salesInvoiceDraft"){
  $sqlNextTableData="SELECT `salesInvoiceDraftId`, `jopRef`, `customerCode`, `salesInvoiceSupTotal`, `salesInvoiceDate`, `itemsQya`, `itemRowId`, `ref` 
  FROM `salesInvoiceDraft` WHERE  `salesInvoiceDraftId` = $subTableId";
  $queryNextTableData=mysqli_query($link,$sqlNextTableData)or die("ERROR LOA_S:01");
  while($resultNextTableData=mysqli_fetch_assoc($queryNextTableData)){
   echo"<tr>
    <td>$resultNextTableData[salesInvoiceDraftId]</td>
    <td><input type='date' id='tableDate' class='form-control editData' value='$resultNextTableData[salesInvoiceDate]'></td>
    <td><input type='number' id='tableAmount' value='$resultNextTableData[salesInvoiceSupTotal]' class='form-control amount'></td>  
    <td></td>
   </tr>";
  }
 }
 # Collect Invoice
 else if($subTable == "salesInvoice"){
  $sqlNextTableData="SELECT `salesInvoiceId`, `salesInvoiceNumber`, `jopRef`, `salesInvoiceDate`, `customerCode`, `salesInvoiceType`, `salesInvoiceSupTotal`,
  `invoiceDiscount`, `salesInvoictVat`, `totalInvoice`, `invoiceCollectAmount` FROM `salesInvoice` WHERE `salesInvoiceId` = $subTableId";
  $queryNextTableData=mysqli_query($link,$sqlNextTableData)or die("ERROR LOA_S:01");
  while($resultNextTableData=mysqli_fetch_assoc($queryNextTableData)){
   echo"<tr>
    <td>$resultNextTableData[salesInvoiceId]</td>
    <td><input type='date' id='tableDate' class='form-control editData' value='$resultNextTableData[salesInvoiceDate]'></td>
    <td><input type='number' id='tableAmount' value='$resultNextTableData[invoiceCollectAmount]' class='form-control amount'></td>  
    <td></td>
   </tr>";
  }
 }
?>
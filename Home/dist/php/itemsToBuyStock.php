<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $invNumber=$_POST['invId'];
 $getAllInvoice="SELECT `suppliersInvoiceId` FROM `supplierInvoice` WHERE `suppliersInvoiceNumber` = '$invNumber'";
 $queryAllInvoice=mysqli_query($link,$getAllInvoice)or die("ERROR :01-AIC_AIDL_S");
 $resAllInvoice=mysqli_fetch_assoc($queryAllInvoice);
 $invoiceId=$resAllInvoice['suppliersInvoiceId'];



 $getAllItemCode="SELECT `description`, `descriptionname`, `partnumber` FROM `stockitems` 
 WHERE `description` IS NOT NULL AND `description` NOT IN( SELECT `ItemRowId` FROM `supplierInvoiceData` WHERE `supplierInvoiceNumber` = $invoiceId)";
 $queryAllItemCode=mysqli_query($link,$getAllItemCode)or die("ERROR :01-AIC_AIDL_S");
 while($resAllItemCode=mysqli_fetch_assoc($queryAllItemCode)){
  echo "
                <option data-value='$resAllItemCode[description]' 
                value='$resAllItemCode[descriptionname] - $resAllItemCode[partnumber]'>
";
 }

//  <option value='$resAllItemCode[description]'>$resAllItemCode[descriptionname]</option>
?>
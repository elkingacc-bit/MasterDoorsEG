<?php
 session_start();
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 
$sqlGetWaitingStock="SELECT `suppliersInvoiceNumber` AS InvoNum FROM `supplierInvoice`, `supplierInvoiceData` 
WHERE `supplierInvoice`.`ref` = 0 AND `suppliersInvoiceId` = `supplierInvoiceNumber` 
GROUP BY `supplierInvoiceNumber`";
$queryWaitingStock=mysqli_query($link,$sqlGetWaitingStock)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryWaitingStock) > 0)
{
	while($resWaitingStock= mysqli_fetch_assoc($queryWaitingStock))
	{
		echo "
		<option value='$resWaitingStock[InvoNum]'>
		";
	}
} 
else
{
	echo "
		<option value=''>No Pending Stock
	";
}
?>
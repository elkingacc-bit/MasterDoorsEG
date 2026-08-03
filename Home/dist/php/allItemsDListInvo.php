<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	
$invoNum = $_POST['invNumDl'];
	$sqlGetInvoItem=" SELECT supplierInvoiceDataId , `ItemRowId` FROM `supplierInvoiceData`, `supplierInvoice` 
	WHERE `supplierInvoiceNumber` = `suppliersInvoiceId` AND `supplierInvoiceData`.`ref` != 1 AND 
	`supplierInvoice`.`suppliersInvoiceNumber` = '$invoNum'";
	$queryGetInvoItem=mysqli_query($link,$sqlGetInvoItem)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetInvoItem= mysqli_fetch_assoc($queryGetInvoItem))
	{
		$sqlGetPartNum = "SELECT `descriptionname`FROM `stockitems` WHERE `description` = 
		$resGetInvoItem[ItemRowId]";
		$queryGetPartNum=mysqli_query($link,$sqlGetPartNum)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
		$resGetPartNum= mysqli_fetch_assoc($queryGetPartNum);
		
			echo "<option data-value='$resGetInvoItem[supplierInvoiceDataId]' 
			value='$resGetPartNum[descriptionname]'> ";
	}
}
?>

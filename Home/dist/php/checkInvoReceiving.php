<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$Invoice = $_POST['invoForCheckQTY'];


	$sqlCheckQTY = "SELECT `supplierInvoiceCount` FROM `supplierInvoiceData`,
	 `supplierInvoice` WHERE `supplierInvoice`.`ref` = 0 AND `suppliersInvoiceNumber` = '$Invoice' 
	 AND `supplierInvoiceData`.`supplierInvoiceNumber` = 	`suppliersInvoiceId`";	
	$queryCheckQTY = mysqli_query($link,$sqlCheckQTY)or die("ERROR :02-AM_AMDL_S".mysqli_error($link));
	
	if(mysqli_num_rows($queryCheckQTY) == 0)
	{
		echo 0;
	}
	else
	{
		echo 1 ;
		exit();
	}
?>

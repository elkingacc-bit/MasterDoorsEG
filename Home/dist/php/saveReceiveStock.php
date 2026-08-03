<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$staff = $_SESSION['fname'];
$ItemName=$_POST['RecItemName'];
$PartNum = $_POST['RecPartNo'];
$Invoice = $_POST['RecInvo'];
$TRowId = $_POST['tableRowId'];
$Qty = $_POST['RecQty'];


	$sqlCheckQTY = "SELECT `supplierInvoiceCount`, `supplierInvoiceUnitPrice`, `supplierInvoiceNumber` 
	,`receivedItems`, `ItemRowId` FROM `supplierInvoiceData` WHERE `supplierInvoiceDataId` = $TRowId";	
	$queryCheckQTY = mysqli_query($link,$sqlCheckQTY)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resCheckQTY = mysqli_fetch_assoc($queryCheckQTY);
	
	$sqlGetSupp = "SELECT `supplierCode` FROM `supplierInvoice` WHERE `suppliersInvoiceId` =
	$resCheckQTY[supplierInvoiceNumber]";	
	$queryGetSupp = mysqli_query($link,$sqlGetSupp)or die("ERROR :02-AM_AMDL_S".mysqli_error($link));
	$resGetSupp = mysqli_fetch_assoc($queryGetSupp);
	
	$ItemCode=$resCheckQTY['ItemRowId'];
	$suppQTY = $resCheckQTY['supplierInvoiceCount'];
	$ReceivedQTY = $resCheckQTY['receivedItems'];
	$remainingQTY = ($suppQTY - $ReceivedQTY);
	
if($Qty > $remainingQTY)
{
	echo 0;
}
else
{
	$totalRecevied = ($Qty + $ReceivedQTY);
	
	if($totalRecevied == $suppQTY)
	{
		$SuppTableref = 1;
	}
	else
	{
		$SuppTableref = 0;
	}
	
	$sqlGetDocSerial = "SELECT `docSerial` FROM `warehouse` WHERE `invoicenumber` = '$Invoice' AND `whref` = 0";	
	$queryGetDocSerial = mysqli_query($link,$sqlGetDocSerial)or die("ERROR :03-AM_AMDL_S".mysqli_error($link));
	if(mysqli_num_rows($queryGetDocSerial) > 0)
	{
		$resGetDocSerial = mysqli_fetch_assoc($queryGetDocSerial);
		$docSerial = $resGetDocSerial['docSerial'];
	}
	else 
	{
	$sqlGetNewDocSerial = "SELECT `docSerial` FROM `warehouse` WHERE `whref` = 1 AND `invoicenumber` != '0'
	ORDER BY `docSerial` DESC LIMIT 1";	
	$queryGetNewDocSerial = mysqli_query($link,$sqlGetNewDocSerial)or die("ERROR :04-AM_AMDL_S"
	.mysqli_error($link));
		
		if(mysqli_num_rows($queryGetNewDocSerial) > 0)
		{
			$resGetNewDocSerial = mysqli_fetch_assoc($queryGetNewDocSerial);
			$docSerial = $resGetNewDocSerial['docSerial'];
			$docSerial++; 
		}
		else
		{
			$docSerial = 1;
		}
	}
	
	//echo "test -> " . $docSerial;
	
	$sqlGetOverCost = "SELECT `overprice`, `salesfactor` FROM `stockitems` WHERE `description` = $ItemCode";	
	$queryOverCost = mysqli_query($link,$sqlGetOverCost)or die("ERROR :05-AM_AMDL_S".mysqli_error($link));
	$resOverCost = mysqli_fetch_assoc($queryOverCost);
	
	$pCost = $resCheckQTY['supplierInvoiceUnitPrice'];
	$SuppCode = $resGetSupp['supplierCode'];
	$overCost = $resOverCost['overprice'];
	$saleFact = (round(($resOverCost['salesfactor'] / 100), 2));
	
	$sqlGetSuppName = "SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $SuppCode";	
	$querySuppName = mysqli_query($link,$sqlGetSuppName)or die("ERROR :06-AM_AMDL_S".mysqli_error($link));
	$resSuppName = mysqli_fetch_assoc($querySuppName);
	
	$suppName = $resSuppName['suppliername'];
	
	$sqlGetCurrntStcok = "SELECT `warehouse` FROM `lookupstock` WHERE `descriptioncode` = $ItemCode";
	$queryGetCurrntStcok = mysqli_query($link,$sqlGetCurrntStcok)or die("ERROR :07-AM_AMDL_S"
	.mysqli_error($link));
	if(mysqli_num_rows($queryGetCurrntStcok) == 0)
	{
		$cStock = 0;
	}
	else
	{
		$resGetCurrntStcok = mysqli_fetch_assoc($queryGetCurrntStcok);
		$cStock = $resGetCurrntStcok['warehouse'];
	}
	
	$newStock = ($Qty + $cStock);
	$newCost = ($pCost + $overCost);
	$newTotalCost = ($newStock * $newCost);
	
	if($saleFact > 0)
	{
		$itemPrice = round(($saleFact * $newCost) + $newCost);
	}
	else
	{
		$itemPrice = 0;
	}
	
	$sqlAddStock = "INSERT INTO `warehouse`( `description`, `date`, `income`, `invoicenumber`, `amount`, 
	`salesprice`, `supplier`, `responsible`, `docSerial`,`whref`) VALUES ($ItemCode, NOW(), $Qty, '$Invoice', 
	'$pCost', '$itemPrice',$SuppCode, '".$_SESSION['username']."', $docSerial, 0)";
	mysqli_query($link,$sqlAddStock)or die("ERROR :08-AM_AMDL_S".mysqli_error($link));
	
	$sqlNewImportStock = "INSERT INTO `importstock` (`descriptionCode`, `recQty`, `date`, `staff`, 
	`suppInvoDateRID`) VALUES ($ItemCode,  $Qty, NOW(), '$staff', $resCheckQTY[supplierInvoiceNumber])";
	mysqli_query($link,$sqlNewImportStock)or die("ERROR :09-AM_AMDL_S".mysqli_error($link));
	
	$sqlUpdateLUWH = "UPDATE `lookupstock` SET `warehouse` = $newStock, `cost` = '$newCost', 
	`sales` = '$itemPrice',`totalCost` = '$newTotalCost', `supplier` = '$suppName' 
	WHERE `descriptioncode` = $ItemCode";
	mysqli_query($link,$sqlUpdateLUWH)or die("ERROR :10-AM_AMDL_S".mysqli_error($link));
	
	$sqlUpdateSuppInvoData = "UPDATE `supplierInvoiceData` SET `ref` = $SuppTableref , `receivedItems` 
	= $totalRecevied WHERE `supplierInvoiceDataId` = $TRowId";
	mysqli_query($link,$sqlUpdateSuppInvoData)or die("ERROR :11-AM_AMDL_S".mysqli_error($link));
	
	
	$sqlCheckStatus = "SELECT `ref` FROM `supplierInvoiceData` WHERE `ref` = 0 AND 
	`supplierInvoiceNumber` = $resCheckQTY[supplierInvoiceNumber]";	
	$queryCheckStatus = mysqli_query($link,$sqlCheckStatus)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckStatus) == 0)
	{
		$sqlUpdateSuppInvoice = "UPDATE `supplierInvoice` SET `ref` = 1
		WHERE `suppliersInvoiceId` = $resCheckQTY[supplierInvoiceNumber]";
		mysqli_query($link,$sqlUpdateSuppInvoice)or die("ERROR :12-AM_AMDL_S".mysqli_error($link));
	}
	
	$action="Receive New Stock from Invoice: $Invoice Item ($PartNum | $ItemName) QTY = $Qty";
	$logRef=11;
	include_once("aduLog.php");
		
		echo 1 ;
		exit();
}
?>


<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
  
 $itemRId = $_POST['IRID'];
 $salesFactorVal = round(($_POST['SalesFactVal'] / 100) ,2);
 $OverPrice = $_POST['overPriCost'];
 
 $sqlGetItems="SELECT `descriptionname`, `description` FROM `stockitems` WHERE `itemsid` = $itemRId";
$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AU_AU_S");
$resGetItems = mysqli_fetch_assoc($queryGetItems);

$sqlGetItemsCost="SELECT `supplierInvoiceUnitPrice`	FROM `supplierInvoiceData`, `supplierInvoice` 
	WHERE `suppliersInvoiceId` = `supplierInvoiceNumber` AND  `ItemRowId` = $resGetItems[description] 
	ORDER BY `suppliersInvoiceDate` DESC LIMIT 1";
	$queryGetItemsCost=mysqli_query($link,$sqlGetItemsCost)or die("ERROR :02-AM_AMDL_S".mysqli_error($link));
	if(mysqli_num_rows($queryGetItemsCost) == 0)
	{
		$itemPrice = 0;
		$totalCost = 0 ;
		$itemCost = 0;
	}
	else
	{
	    $resGetItemsCost= mysqli_fetch_assoc($queryGetItemsCost);
		$itemCost = $resGetItemsCost['supplierInvoiceUnitPrice'];
		
	 $sqlGetStock="SELECT `warehouse` FROM `lookupstock` WHERE `descriptioncode` = $resGetItems[description]";
$queryGetStock=mysqli_query($link,$sqlGetStock)or die("ERROR :03-AU_AU_S");
$resGetStock = mysqli_fetch_assoc($queryGetStock);
	
	$totalStock = $resGetStock['warehouse'];
	
		$itemPrice = (($salesFactorVal * ($OverPrice + $itemCost)) + ($OverPrice +$itemCost));
		$totalCost = ($itemCost * $totalStock) ;
	}
	 
	$sqlUpdateLoockUp = "UPDATE `lookupstock` SET `cost` = '$itemCost', `sales` = '$itemPrice', 
	`totalCost` = '$totalCost' WHERE `descriptioncode` = $resGetItems[description]";
	mysqli_query($link,$sqlUpdateLoockUp)or die("ERROR :03-AU_AU_S");

$sqlAddSF = "UPDATE `stockitems` SET `salesfactor` = '$salesFactorVal', `overprice` = '$OverPrice' WHERE 
`itemsid` = $itemRId";
mysqli_query($link,$sqlAddSF)or die("ERROR :04-AU_AU_S");

$action="Add New Sales Factor = $salesFactorVal% and OverCost = $OverPrice For 
Item: $resGetItems[descriptionname]";
		$logRef=12;
		include_once("aduLog.php");
			echo 1;
			exit();

}
?>

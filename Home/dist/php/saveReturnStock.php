<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$WhRowId=$_POST['WRID'];


$sqlGetExptStock="SELECT `description`, `export`, `custcode`, `poIdRef` FROM `warehouse` 
WHERE `warehouseId` = $WhRowId ";
	$queryGetExptStock=mysqli_query($link,$sqlGetExptStock)or die("ERROR :01-AU_AU_S");
	$resGetExptStock= mysqli_fetch_assoc($queryGetExptStock);
	
	$ItemCode = $resGetExptStock['description'];
	$Qty = $resGetExptStock['export'];	
	
		
			$sqlGetItemData = "SELECT `descriptionname`, `partnumber` FROM `stockitems` 
			WHERE `description` = $resGetExptStock[description]";
			$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
			$resGetItemData= mysqli_fetch_assoc($queryGetItemData);
			
			$PartNum = $resGetItemData['partnumber'];
			$ItemName = $resGetItemData['descriptionname'];
			
			$sqlGetCust = "SELECT `customername` FROM `customers` WHERE `customercode` =
			$resGetExptStock[custcode]";
			$queryGetCust = mysqli_query($link,$sqlGetCust)or die("ERROR :02-ANJ_GCN_S");
			$resultGetCust = mysqli_fetch_array($queryGetCust);	
			
			$sqlGetJobRef = "SELECT `jobidref`, `PoNum`, `orderType` FROM `customerpo` WHERE `poId` =
			 $resGetExptStock[poIdRef]";
			$queryGetJobRef=mysqli_query($link,$sqlGetJobRef)or die("ERROR :03-AU_AU_S");
			$resGetJobRef= mysqli_fetch_assoc($queryGetJobRef);
			
			
			if($resGetJobRef['orderType'] == 'Doors')
			{
				$sqlUpdateOffer = "UPDATE `offerproperties` SET `jobproref` =  1, `whrefid` = NULL 
				WHERE `whrefid` = $WhRowId";
				mysqli_query($link,$sqlUpdateOffer)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
			}
			else if($resGetJobRef['orderType'] == 'Stock')
			{
				$sqlUpdateOffer = "UPDATE `stockoffers` SET `ref` =  1, `whref` = 0 
				WHERE `whref` = $WhRowId";
				mysqli_query($link,$sqlUpdateOffer)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
			}

 
	$sqlAddStock = "INSERT INTO `warehouse`( `description`, `date`, `income`, `invoicenumber`, `supplier`, 
	`responsible`, `whref`) VALUES ($ItemCode, NOW(), $Qty, NULL , 0, '".$_SESSION['username']."', 2)";
	mysqli_query($link,$sqlAddStock)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	
	$sqlUpdateStock = "UPDATE `warehouse` SET `whref` =  2 WHERE `warehouseId` = $WhRowId";
	mysqli_query($link,$sqlUpdateStock)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	
	$sqlUpdateCustPo = "UPDATE `customerpo` SET `poRef` =  NULL WHERE `poId` = $resGetExptStock[poIdRef]";
	mysqli_query($link,$sqlUpdateCustPo)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	
	$sqlGetCurrntStcok = "SELECT `warehouse` FROM `lookupstock` WHERE `descriptioncode` = $ItemCode";
	$queryGetCurrntStcok = mysqli_query($link,$sqlGetCurrntStcok)or die("ERROR :02-AM_AMDL_S"
	.mysqli_error($link));
	if($queryGetCurrntStcok == 0)
	{
		$cStock = 0;
	}
	else
	{
		$resGetCurrntStcok = mysqli_fetch_assoc($queryGetCurrntStcok);
		$cStock = $resGetCurrntStcok['warehouse'];
	}
	
	$newStock = ($Qty + $cStock);
	
	$sqlUpdateLUWH = "UPDATE `lookupstock` SET `warehouse` = $newStock WHERE `descriptioncode` = $ItemCode";
	mysqli_query($link,$sqlUpdateLUWH)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	
	$action="Return Stock for Item ($PartNum | $ItemName) QTY = $Qty was Exported before For Customer: 
	$resultGetCust[customername] in PO: $resGetJobRef[PoNum]";
	$logRef=11;
	include_once("aduLog.php");
	
	echo 1 ;
	exit();
?>

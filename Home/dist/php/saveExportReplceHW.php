<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

//
$offerPorpRID=$_POST['offerPropRID'];
$ItemName=$_POST['replacedHWName'];
$ItemCode=$_POST['replacedHWCode'];
$Po = $_POST['orderNoR'];
$PoRowId = $_POST['orderRIDR'];
$Qty = $_POST['newExptQTYR'];
$exportedQty = $_POST['expedQTYR'];
$OfferQty = $_POST['offerdQTYHW'];

$sqlGetDescCode = "SELECT `descripcode`, `ioidref`, `jobidref` FROM `offerproperties` 
WHERE `offproId` = $offerPorpRID";
	$queryGetDescCode = mysqli_query($link,$sqlGetDescCode)or die("ERROR :02-AM_AMDL_S"
	.mysqli_error($link));
	$resGettDescCode = mysqli_fetch_assoc($queryGetDescCode);

$ItemRID = $resGettDescCode['ioidref'];
$descCode = $resGettDescCode['descripcode'];
$jobRID = $resGettDescCode['jobidref'];

$sqlGetOfferItemName="SELECT `itemname` FROM `lookupstock` WHERE `descriptioncode` = $descCode";
	$queryGetOfferItemName=mysqli_query($link,$sqlGetOfferItemName)or die("ERROR :01-AM_AMDL_S"
	.mysqli_error($link));
	$resGetOfferItemName= mysqli_fetch_assoc($queryGetOfferItemName);
$offeredItemName = $resGetOfferItemName['itemname'];	


//echo "test -> ";
$sqlGetCurrntStcok = "SELECT `warehouse` FROM `lookupstock` WHERE `descriptioncode` = $ItemCode";
	$queryGetCurrntStcok = mysqli_query($link,$sqlGetCurrntStcok)or die("ERROR :02-AM_AMDL_S"
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
	
	if($Qty > $cStock)
	{
		echo 0;
	}
	else
	{
		
	$sqlGetDocSerial = "SELECT `docSerial` FROM `warehouse` WHERE `poIdRef` =  $PoRowId AND `whref` = 0";	
	$queryGetDocSerial = mysqli_query($link,$sqlGetDocSerial)or die("ERROR :02-AM_AMDL_S"
	.mysqli_error($link));
	if(mysqli_num_rows($queryGetDocSerial) > 0)
	{
		$resGetDocSerial = mysqli_fetch_assoc($queryGetDocSerial);
		$docSerial = $resGetDocSerial['docSerial'];
	}
	else 
	{
	$sqlGetNewDocSerial = "SELECT `docSerial` FROM `warehouse` WHERE `whref` = 1 AND `poIdRef` != 0 ORDER BY `docSerial` DESC 
	LIMIT 1";	
	$queryGetNewDocSerial = mysqli_query($link,$sqlGetNewDocSerial)or die("ERROR :02-AM_AMDL_S"
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
		
	$sqlGetCust = "SELECT `custCode`, `jobidref`, `orderType` FROM `customerpo` WHERE `poId` = $PoRowId";
	$queryGetCust = mysqli_query($link,$sqlGetCust)or die("ERROR :02-ANJ_GCN_S");
	$resultGetCust = mysqli_fetch_array($queryGetCust);	

	$orderTypeExp= $resultGetCust['orderType'];
	
		
	$sqlAddStock = "INSERT INTO `warehouse`( `description`, `date`, `export`, `invoicenumber`, `supplier`, 
	`responsible`, `custcode`, `docSerial`, `poIdRef`, `whref`) VALUES ($ItemCode, NOW(), $Qty, '0', 0, 
	'".$_SESSION['username']."', $resultGetCust[custCode], $docSerial ,$PoRowId, 0)";
	mysqli_query($link,$sqlAddStock)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	
	$sqlGetWHID = "SELECT `warehouseId` FROM `warehouse` WHERE `description` = $ItemCode AND `poIdRef` = 
	$PoRowId ORDER BY `warehouseId` DESC LIMIT 1";
	$queryGetWHID = mysqli_query($link,$sqlGetWHID)or die("ERROR :02-ANJ_GCN_S");
	$resultGetWHID = mysqli_fetch_array($queryGetWHID);	
	
	$sqlReplceStock = "INSERT INTO `replacedexpt`( `descriptionRCode`, `exptqty`, `whrefid`, `exptdate`,
	 `offereditemcode`, `porefrowid`) VALUES ($ItemCode, $Qty, $resultGetWHID[warehouseId], NOW(), 
	 $descCode, $PoRowId)";
	mysqli_query($link,$sqlReplceStock)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	
	if($orderTypeExp == 'Doors')
	{
		/*$sqlGetAllOfferQTY = "SELECT SUM(`descripquantity`) AS descripquantity FROM `offerproperties` 
		WHERE `jobidref` = $jobRID AND `descripcode` = $ItemCode";
		$queryAllOfferQTY = mysqli_query($link,$sqlGetAllOfferQTY)or die("ERROR :02-ANJ_GCN_S");
		$resultAllOfferQTY = mysqli_fetch_array($queryAllOfferQTY);
		*/
		$allItemOfferedQTY = $OfferQty;	
		
		
		/*$sqlGetAllExpQTY = "SELECT SUM(`export`) AS ExportedQTY FROM `warehouse` 
		WHERE `poIdRef` = $PoRowId AND `description` = $ItemCode";
		$queryAllExpQTY = mysqli_query($link,$sqlGetAllExpQTY)or die("ERROR :02-ANJ_GCN_S");
		$resultAllExpQTY = mysqli_fetch_array($queryAllExpQTY);
		
		if($resultAllExpQTY['ExportedQTY'] == "")
		{
			$allItemExtedQTY = 0 ;	
		}
		else
		{*/
			$allItemExtedQTY = ($exportedQty + $Qty);	
		//}
				

		if( $allItemExtedQTY == $allItemOfferedQTY)
		{
			
			$sqlUpdateOffer = "UPDATE `offerproperties` SET `whrefid` = $resultGetWHID[warehouseId],
			`jobproref` = 2 WHERE `jobidref` = $jobRID AND `descripcode` = $ItemCode";
		}
		else
		{
			
			$sqlUpdateOffer = "UPDATE `offerproperties` SET `whrefid` = $resultGetWHID[warehouseId],
			`jobproref` = 1 WHERE `jobidref` = $jobRID AND `descripcode` = $ItemCode";
		}
		
		$sqlCheckItem = "SELECT `jobproref` FROM `offerproperties` WHERE `jobproref` = 1 
		AND `jobidref` = $jobRID";
		$queryCheckItem = mysqli_query($link,$sqlCheckItem)or die("ERROR :02-ANJ_GCN_S");
	
		if(mysqli_num_rows($queryCheckItem) == 0)
		{
			
			$sqlUpdateItem = "UPDATE `itemoffer` SET `ref` = 3 WHERE `jobidref` = $jobRID 
			AND `descripcode` = $ItemCode";
			mysqli_query($link,$sqlUpdateItem)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
		}
	 	/*else
		{
			echo 2;
		}*/
		
		
	}
			
	$newStock = ( $cStock - $Qty);
	
	$sqlUpdateLUWH = "UPDATE `lookupstock` SET `warehouse` = $newStock WHERE `descriptioncode` = $ItemCode";
	mysqli_query($link,$sqlUpdateLUWH)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	
	$action="Export Stock from Replaced Item For PO: $Po | Offered Item ($offeredItemName) |  
	New Item ($ItemName) | QTY = $Qty";
	$logRef=11;
	include_once("aduLog.php");
	
	echo 1 ;
	exit();
	}
?>

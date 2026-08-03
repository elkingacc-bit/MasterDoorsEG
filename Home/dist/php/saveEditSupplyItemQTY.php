<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$suppOrderId = $_POST['SORID'];
	$ItemRId = $_POST['SOItemRID'];
	$suppOrderType = $_POST['SOType'];
	$jobRowId = $_POST['SOEditJobRID'];
	$SupplyQTY = $_POST['editedQTY'];
	
	
	$sqlGetItems="SELECT `ItemRowId` FROM `supporderitems` WHERE `OIId` = $ItemRId ";
	$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	$resGetItems= mysqli_fetch_assoc($queryGetItems);
		
	if($suppOrderType == 'Doors')
	{	
		$sqlGetItemsQty="SELECT `itemqty`, `itemtype` FROM `itemoffer` 
		WHERE `id` = $resGetItems[ItemRowId] ";
	}
	else if($suppOrderType == 'Automatic')
	{	
		$sqlGetItemsQty="SELECT `doorqty`, `doortype` FROM `autodoorsoffer` 
		WHERE `id` = $resGetItems[ItemRowId] ";
	}
	
	$queryGetItemsQty=mysqli_query($link,$sqlGetItemsQty)or die("ERROR :02-AU_AU_S".mysqli_error($link));
	$resGetItemsQty= mysqli_fetch_array($queryGetItemsQty);	
	$offeredQTY = $resGetItemsQty[0];	
		
		if($SupplyQTY > $offeredQTY)
		{
			echo 0;
		}
		else
		{
			$sqlGetOrderData = "SELECT `SuppCode`, `OrderNumber` FROM `supplierorder` 
			 WHERE `SOId` = $suppOrderId";
			$queryGetOrderData = mysqli_query($link,$sqlGetOrderData)or die("ERROR :03-ANJ_GCN_S");
			$resGetOrderData = mysqli_fetch_assoc($queryGetOrderData);
			
			$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
			$resGetOrderData[SuppCode]";
			$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :04-AU_AU_S");
			$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);	
			
			$sqlGetOfferNum="SELECT `localref` FROM `job` WHERE `jobId` = $jobRowId";
			$queryGetOfferNum=mysqli_query($link,$sqlGetOfferNum)or die("ERROR :05-AU_AU_S");
			$resGetOfferNum= mysqli_fetch_assoc($queryGetOfferNum);	
			
			if($SupplyQTY == 0)
			{
				$sqlRemoveSupplyItem = "DELETE FROM `supporderitems` WHERE `OIId` = $ItemRId";
				mysqli_query($link,$sqlRemoveSupplyItem)or die("ERROR :06-AU_AU_S".mysqli_error($link));
				
				$action="Remove Supply QTY for Item Type : $resGetItemsQty[1] Supplier: 
				$resGetSupplier[suppliername] for Offer No: $resGetOfferNum[localref]";
			}
			else if($SupplyQTY == $offeredQTY)
			{
				$sqlEditSupplyItem = "UPDATE `supporderitems` SET  `qty` = $SupplyQTY, `OIRef` = 1 
				WHERE `OIId` = $ItemRId";
				mysqli_query($link,$sqlEditSupplyItem)or die("ERROR :07-AU_AU_S".mysqli_error($link));
				
				$action="Edit Supply QTY $SupplyQTY for Item Type : $resGetItemsQty[1] Supplier: 
				$resGetSupplier[suppliername] for Offer No: $resGetOfferNum[localref]";
			}
			else
			{
				$sqlEditSupplyItem = "UPDATE `supporderitems` SET  `qty` = $SupplyQTY WHERE `OIId` = $ItemRId";
				mysqli_query($link,$sqlEditSupplyItem)or die("ERROR :07-AU_AU_S".mysqli_error($link));
				
				$action="Edit Supply QTY $SupplyQTY for Item Type : $resGetItemsQty[1] Supplier: 
				$resGetSupplier[suppliername] for Offer No: $resGetOfferNum[localref]";
			}
			
			$logRef=9;	
			include_once("aduLog.php");
			
			echo 1;
			exit();	
		}
}
else
{
	echo 9;
}
?>

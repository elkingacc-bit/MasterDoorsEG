<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$suppOrderId = $_POST['SOReRowID'];
	$ItemRId = $_POST['ReItemRID'];
	$suppOrderType = $_POST['ReSOType'];
	$jobRowId = $_POST['SOReceJobRID'];
	$SupplyQTY = $_POST['receivedQTY'];
	
	
	$sqlGetItems="SELECT `ItemRowId`, `qty`, `receivedQTY` FROM `supporderitems` WHERE `OIId` = $ItemRId ";
	$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	$resGetItems= mysqli_fetch_assoc($queryGetItems);
	$orderedQTY = $resGetItems['qty'];
	$receivedQTY = $resGetItems['receivedQTY'];
	$remainingQTY = ($orderedQTY - $receivedQTY);
		
	
		
		
		
	if($suppOrderType == 'Doors')
	{	
		$sqlGetItemsQty="SELECT SUM(`itemqty`) AS itemqty, `itemtype` FROM `itemoffer` 
		WHERE `jobref` = $jobRowId ";
		
	}
	else if($suppOrderType == 'Automatic')
	{	
		$sqlGetItemsQty="SELECT SUM(`doorqty`) AS doorqty, `doortype` FROM `autodoorsoffer` 
		WHERE `jobid` = $jobRowId ";
	}
	
	$queryGetItemsQty=mysqli_query($link,$sqlGetItemsQty)or die("ERROR :02-AU_AU_S".mysqli_error($link));
	$resGetItemsQty= mysqli_fetch_array($queryGetItemsQty);	
	$offeredQTY = $resGetItemsQty[0];	
	
	$sqlGetTRecevQTY="SELECT SUM(`receivedQTY`) AS received FROM `supporderitems` WHERE `SOIdRef` 
	= $suppOrderId ";
	$queryGetTRecevQTY=mysqli_query($link,$sqlGetTRecevQTY)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	$resGetTRecevQTY= mysqli_fetch_assoc($queryGetTRecevQTY);
	$TotalreceivedQTY = $resGetTRecevQTY['received'];	
		
		if($remainingQTY == 0)
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
			
			$totalReceived = ($SupplyQTY + $receivedQTY);
			//echo "Test - >" . $TotalreceivedQTY;
		if(($TotalreceivedQTY + $SupplyQTY) == $offeredQTY)
			{
				$sqlEditSupplyItem = "UPDATE `supporderitems` SET  `receivedQTY` = $totalReceived, `OIRef` = 2
				 , `status` = 1 , `receiveddate` = NOW() WHERE `OIId` = $ItemRId";
				mysqli_query($link,$sqlEditSupplyItem)or die("ERROR :07-AU_AU_S".mysqli_error($link));
				
				if($offeredQTY == ($TotalreceivedQTY + $SupplyQTY))
				{
					$sqlUpdateSuppOrderRef= "UPDATE `supplierorder` SET  `SORef` = 2 
					WHERE `SOId` = $suppOrderId";
					mysqli_query($link,$sqlUpdateSuppOrderRef)or die("ERROR :07-AU_AU_S".mysqli_error($link));
					
					$sqlUpdateOrderStatus = "UPDATE `supporderitems` SET `status` = 1, `OIRef` = 2
					WHERE `SOIdRef` = $suppOrderId";
					mysqli_query($link,$sqlUpdateOrderStatus)or die("ERROR :07-AU_AU_S".mysqli_error($link));
					
					
				}
				
				$action="All Supply QTY $SupplyQTY Received for Item Type : $resGetItemsQty[1] Supplier: 
				$resGetSupplier[suppliername] for Offer No: $resGetOfferNum[localref] Order Closed";
			}
			else
			{
				$sqlEditSupplyItem = "UPDATE `supporderitems` SET  `receivedQTY` = $totalReceived 
				, `status` = 0, `receiveddate` = NOW() WHERE `OIId` = $ItemRId";
				mysqli_query($link,$sqlEditSupplyItem)or die("ERROR :07-AU_AU_S".mysqli_error($link));
				
				$action="Received Supply QTY $SupplyQTY for Item Type : $resGetItemsQty[1] Supplier: 
				$resGetSupplier[suppliername] for Offer No: $resGetOfferNum[localref] remaining = 
				$remainingQTY";
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

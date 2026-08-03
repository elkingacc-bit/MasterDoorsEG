<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	//echo "test -> ";
	$suppOrderId = $_POST['RecvAllSORID'];
	//$ItemRId = $_POST['ReItemRID'];
	$suppOrderType = $_POST['RecvSOType'];
	$jobRowId = $_POST['RecvAllJID'];
	
	
		
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
		
			$sqlGetOrderData = "SELECT `SuppCode`, `OrderNumber` FROM `supplierorder` 
			 WHERE `SOId` = $suppOrderId";
			$queryGetOrderData = mysqli_query($link,$sqlGetOrderData)or die("ERROR :03-ANJ_GCN_S");
			$resGetOrderData = mysqli_fetch_assoc($queryGetOrderData);
			
			$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
			$resGetOrderData[SuppCode]";
			$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :04-AU_AU_S");
			$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);	
			
			$sqlGetOfferNum="SELECT `projectName` FROM `job` WHERE `jobId` = $jobRowId";
			$queryGetOfferNum=mysqli_query($link,$sqlGetOfferNum)or die("ERROR :05-AU_AU_S");
			$resGetOfferNum= mysqli_fetch_assoc($queryGetOfferNum);	
			
				$sqlGetTRecevQTY="SELECT SUM(`receivedQTY`) AS received FROM `supporderitems` WHERE `SOIdRef` 
            	= $suppOrderId ";
	            $queryGetTRecevQTY=mysqli_query($link,$sqlGetTRecevQTY)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	            $resGetTRecevQTY= mysqli_fetch_assoc($queryGetTRecevQTY);
			    if($resGetTRecevQTY['received'] == "")
			    {
			       $receivedQTY = 0; 
			    }
                else
                {
			        $receivedQTY = $resGetTRecevQTY['received'];
                }
			    
			$totalReceived = ($SupplyQTY + $receivedQTY);
			//echo "Test - >" . $TotalreceivedQTY;
		
			$sqlEditSupplyItem = "UPDATE `supporderitems` SET  `receivedQTY` = `qty` 
			, `status` = 1, `receiveddate` = NOW(), `OIRef` = 2 WHERE `SOIdRef` = $suppOrderId";
			mysqli_query($link,$sqlEditSupplyItem)or die("ERROR :07-AU_AU_S".mysqli_error($link));
				
			$sqlGetTRecevQTY="SELECT SUM(`receivedQTY`) AS received FROM `supporderitems` WHERE `SOIdRef` 
			= $suppOrderId ";
			$queryGetTRecevQTY=mysqli_query($link,$sqlGetTRecevQTY)or die("ERROR :01-AU_AU_S"
			.mysqli_error($link));
			$resGetTRecevQTY= mysqli_fetch_assoc($queryGetTRecevQTY);
			$TotalreceivedQTY = $resGetTRecevQTY['received'];	
			
			$remainingQTY = ($offeredQTY - $TotalreceivedQTY);	
				
			if($offeredQTY == $TotalreceivedQTY)
			{	
				$sqlUpdateSuppOrderRef= "UPDATE `supplierorder` SET  `SORef` = 2 
				WHERE `SOId` = $suppOrderId";
				mysqli_query($link,$sqlUpdateSuppOrderRef)or die("ERROR :07-AU_AU_S".mysqli_error($link));
						
				$action="All Supply All Supplied QTY Received for Item Type : $resGetItemsQty[1] Supplier: 
				$resGetSupplier[suppliername] for Project: $resGetOfferNum[projectName] Order Closed";
			} 
			else
			{
				$sqlEditSupplyItem = "UPDATE `supporderitems` SET  `receivedQTY` = `qty` 
				, `status` = 0, `receiveddate` = NOW() WHERE `SOIdRef` = $suppOrderId";
				mysqli_query($link,$sqlEditSupplyItem)or die("ERROR :07-AU_AU_S".mysqli_error($link));
				
				$action="Received All Supplied QTY for Item Type : $resGetItemsQty[1] Supplier: 
				$resGetSupplier[suppliername] for Project: $resGetOfferNum[projectName] remaining = 
				$remainingQTY";
			}
			
			$logRef=9;	
			include_once("aduLog.php");
			
			echo 1;
			exit();
}
else
{
	echo 9;
}
?>

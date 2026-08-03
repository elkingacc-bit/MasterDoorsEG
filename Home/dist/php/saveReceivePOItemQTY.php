<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	//echo "test -> ";
	 $suppOrderId = $_POST['deliverSORowID'];
	$ItemRId = $_POST['deliverItemRowId'];
	$suppOrderType = $_POST['deliverOrderType'];
	$jobRowId = $_POST['deliverJobRID'];
	$deliveredQTY = $_POST['deliveredQTY'];
	
	
			$sqlGetOrderData = "SELECT `SuppCode`, `OrderNumber` FROM `supplierorder` 
			 WHERE `SOId` = $suppOrderId";
			$queryGetOrderData = mysqli_query($link,$sqlGetOrderData)or die("ERROR :03-ANJ_GCN_S");
			$resGetOrderData = mysqli_fetch_assoc($queryGetOrderData);
			
			$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
			$resGetOrderData[SuppCode]";
			$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :04-AU_AU_S");
			$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);	
			
			$sqlGetOfferNum="SELECT `localref` FROM `job` WHERE `jobId` = $jobRowId";
			$queryGetOfferNum=mysqli_query($link,$sqlGetOfferNum)or die("ERROR :04-AU_AU_S");
			$resGetOfferNum= mysqli_fetch_assoc($queryGetOfferNum);	
			
			$sqlGetOfferRID = "SELECT `ItemRowId` FROM `supporderitems` WHERE `OIId` = $ItemRId";
			$queryGetOfferRID = mysqli_query($link,$sqlGetOfferRID)or die("ERROR :03-ANJ_GCN_S");
			$resGetOfferRID = mysqli_fetch_assoc($queryGetOfferRID);
			
			
			if($suppOrderType == "Doors")
			{
				$sqlGetItemData = "SELECT `itemtype`, `itemqty`, `totalprice` FROM `itemoffer` 
				WHERE `id` = $resGetOfferRID[ItemRowId]";
				
				$sqlGetOrderQTY="SELECT SUM(`itemqty`) AS totalOrderQTY FROM `itemoffer`
				WHERE `jobref` = $jobRowId";
				$queryGetOrderQTY=mysqli_query($link,$sqlGetOrderQTY)or die("ERROR :01-AU_AU_S");
				$resGetOrderQTY= mysqli_fetch_assoc($queryGetOrderQTY);
				if($resGetOrderQTY['totalOrderQTY'] == "")
				{
					$allOrderQTY = 0;
				}
				else
				{
					$allOrderQTY = $resGetOrderQTY['totalOrderQTY'];
				}
			
			$dRAL = array();
			$doverlap = array();
			$dhandle = array();
			$DdoorNo = array();
			
			$sqlGetItemDetails = "SELECT  `RAL`, `Handle`, `Overlap`, `doornumber` 
			FROM `supporderitems` WHERE `OIId` = $ItemRId";
			$queryGetItemDetails=mysqli_query($link,$sqlGetItemDetails)or die("ERROR :02-AU_AU_S");
			$resGetItemDetails= mysqli_fetch_array($queryGetItemDetails);
			
			$doorNo = explode(',' ,$resGetItemDetails['doornumber']);
			$overlap = explode(',' ,$resGetItemDetails['Overlap']);
			$RAL = explode(',' ,$resGetItemDetails['RAL']);
			$handle = explode(',' ,$resGetItemDetails['Handle']);
			
			for($aK = 0 ; $aK < $deliveredQTY; $aK++)
			{
			 	array_push($dRAL, $doorNo[$aK]);
			 	array_push($doverlap, $overlap[$aK]);
				array_push($dhandle, $handle[$aK]);
				array_push($DdoorNo, $doorNo[$aK]);
			}
			
				$deliveredRAL = implode(',',$dRAL);
				$deliveredDoorNo = implode(',',$doverlap);
				$deliveredHandle = implode(',',$dhandle);
				$deliveredOverlap = implode(',',$DdoorNo);
			}
			else if($suppOrderType == "Automatic")
			{
				$sqlGetItemData = "SELECT `doortype`, `doorqty`, `totalprice` FROM `autodoorsoffer` 
				WHERE `id` = $resGetOfferRID[ItemRowId]";
				
				$sqlGetOrderQTY="SELECT SUM(`doorqty`) AS totalOrderQTY FROM `autodoorsoffer`
				WHERE `jobid` = $jobRowId";
				$queryGetOrderQTY=mysqli_query($link,$sqlGetOrderQTY)or die("ERROR :01-AU_AU_S");
				$resGetOrderQTY= mysqli_fetch_assoc($queryGetOrderQTY);
				if($resGetOrderQTY['totalOrderQTY'] == "")
				{
					$allOrderQTY = 0;
				}
				else
				{
					$allOrderQTY = $resGetOrderQTY['totalOrderQTY'];
				}
				
				$deliveredRAL = "";
				$deliveredDoorNo = "";
				$deliveredHandle = "";
				$deliveredOverlap = "";
				
			}
			$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
			$resGetItemData= mysqli_fetch_array($queryGetItemData);
			
			$sqlGetCustPO="SELECT `poId`, `PoNum` FROM `customerpo` WHERE `jobidref` = $jobRowId";
			$queryGetCustPO=mysqli_query($link,$sqlGetCustPO)or die("ERROR :01-AU_AU_S");
			$resGetCustPO= mysqli_fetch_assoc($queryGetCustPO);
			
			$sqlGetDeliverAmount="SELECT `deliverypayment` FROM `offerpolicy` 
			WHERE `jobRowId` = $jobRowId";
			$queryGetDeliverAmount=mysqli_query($link,$sqlGetDeliverAmount)or die("ERROR :01-AU_AU_S");
			$resGetDeliverAmount= mysqli_fetch_assoc($queryGetDeliverAmount);
			
			$deliverAmountPrest = $resGetDeliverAmount['deliverypayment'];
			
			$unitPrice = round($resGetItemData[2] / $resGetItemData[1]);
			
			$deliverAoumt = round((($unitPrice * $deliveredQTY) * $deliverAmountPrest), 2);
			
			
			//$totalReceive = ($resGetItemData[2] + $deliveredQTY);
			
			//$totalDeliverItem = ($oldDeliverQTY + $deliveredQTY);
			
			$sqlAddSupplyItem = "INSERT INTO `custorderdeliver` (`itemRowId`, `itemquantity`, 
			`deliverdate`, `deliveramount`, `jobRowId`, `poRowId`, `handling`, `doorNumber`, `Overlap`
			, `RAL`, `ref`) VALUES ($ItemRId, $deliveredQTY, NOW(), '$deliverAoumt', $jobRowId,
			 $resGetCustPO[poId], '$deliveredHandle', '$deliveredDoorNo', '$deliveredOverlap',
			 '$deliveredRAL' ,0)";
			mysqli_query($link,$sqlAddSupplyItem)or die("ERROR :02-AU_AU_S".mysqli_error($link));
			
			$sqlGetDeliverQTY="SELECT SUM(`itemquantity`) AS totalDelvQTY FROM `custorderdeliver`
			WHERE `poRowId` = $resGetCustPO[poId]";
			$queryGetDeliverQTY=mysqli_query($link,$sqlGetDeliverQTY)or die("ERROR :01-AU_AU_S");
			$resGetDeliverQTY= mysqli_fetch_assoc($queryGetDeliverQTY);
			if($resGetDeliverQTY['totalDelvQTY'] == "")
			{
				$oldDeliverQTY = 0;
			}
			else
			{
				$oldDeliverQTY = $resGetDeliverQTY['totalDelvQTY'];
			}
			
			
			if($allOrderQTY == $oldDeliverQTY)
			{
				$SORef = 3;
				$custPORef = 1;
				$action="Add New Delivered QTY $deliveredQTY for Item Type : $resGetItemData[0] 
				for Offer No: $resGetOfferNum[localref] Order Closed";
			}
			else
			{
				$SORef = 2;
				$custPORef = 'NULL';
				$action="Add New Delivered QTY $deliveredQTY for Item Type : $resGetItemData[0] 
				for Offer No: $resGetOfferNum[localref]";
			}
			
			$sqlUpdateSuppOrder = "UPDATE `supplierorder`SET `SORef` = $SORef WHERE `SOId`  = $suppOrderId";
			mysqli_query($link,$sqlUpdateSuppOrder)or die("ERROR :02-AU_AU_S".mysqli_error($link));
			
			$sqlUpdateCustPo = "UPDATE `customerpo`SET `poRef` = $custPORef WHERE `poId` = $resGetCustPO[poId]";
			mysqli_query($link,$sqlUpdateCustPo)or die("ERROR :02-AU_AU_S".mysqli_error($link));
			
			
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

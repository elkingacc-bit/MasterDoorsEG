<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{  
	//echo "test -> ";
	 $suppOrderId = $_POST['SOIdRequ'];
	$selectItemRId = $_POST['IRIdRequ'];
	$suppOrderType = $_POST['OTRequ'];
	$jobRowId = $_POST['JRIdRequ'];
	$SupplyQTY = $_POST['IQRequ'];
	
	
	/*$sqlGetAllItems="SELECT `OIId` FROM `supporderitems`  WHERE `ItemRowId` = $selectItemRId AND
	`soType` = '$suppOrderType'  ";
	$queryGetAllItems=mysqli_query($link,$sqlGetAllItems)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	//$resGetAllItems= mysqli_fetch_assoc($queryGetAllItems);
		if(mysqli_num_rows($queryGetAllItems) > 0)
		{
			echo 0;
		}
		else
		{*/
			$sqlGetOrderData = "SELECT `SuppCode`, `OrderNumber` FROM `supplierorder` 
			 WHERE `SOId` = $suppOrderId";
			$queryGetOrderData = mysqli_query($link,$sqlGetOrderData)or die("ERROR :03-ANJ_GCN_S");
			$resGetOrderData = mysqli_fetch_assoc($queryGetOrderData);
			
			$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
			$resGetOrderData[SuppCode]";
			$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :04-AU_AU_S");
			$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);	
			
			$sqlGetOfferNum="SELECT `localref`, `projectName` FROM `job` WHERE `jobId` = $jobRowId";
			$queryGetOfferNum=mysqli_query($link,$sqlGetOfferNum)or die("ERROR :04-AU_AU_S");
			$resGetOfferNum= mysqli_fetch_assoc($queryGetOfferNum);	
			
			if($suppOrderType == "Doors")
			{
			$sqlGetItemData = "SELECT `itemtype`, `itemqty` FROM `itemoffer` WHERE `id` = $selectItemRId";
			}
			else if($suppOrderType == "Automatic")
			{
				$sqlGetItemData = "SELECT `doortype`, `doorqty` FROM `autodoorsoffer` 
				WHERE `id` = $selectItemRId";
			}
			$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
			$resGetItemData= mysqli_fetch_array($queryGetItemData);
			
			if($resGetItemData[1] == $SupplyQTY)
			{
				$SORef = 1;
			}
			else
			{
				$SORef = 0;
			}
			
			$sqlAddSupplyItem = "INSERT INTO `supporderitems` (`ItemRowId`, `qty`, `price`, `status`
			, `SOIdRef`	, `soType`, `OIRef`, `receivedQTY`) VALUES ($selectItemRId, $SupplyQTY, '0'
			, 0 , $suppOrderId, '$suppOrderType', $SORef, 0)";
			mysqli_query($link,$sqlAddSupplyItem)or die("ERROR :02-AU_AU_S".mysqli_error($link));
			
			$sqlUpdateSuppOrder = "UPDATE `supplierorder`SET `SORef` = 1 WHERE `SOId`  = $suppOrderId";
			mysqli_query($link,$sqlUpdateSuppOrder)or die("ERROR :02-AU_AU_S".mysqli_error($link));
			
			$action="Add New Supply QTY $SupplyQTY for Item Type : $resGetItemData[0] Supplier: 
			$resGetSupplier[suppliername] for Project: $resGetOfferNum[projectName]";
			$logRef=9;	
			include_once("aduLog.php");
			
			echo 1;
			exit();	
		//}
		
		/*
		$resfulldate =  array(
	  "partNumGet" => $resGetPartNo['partnumber'], 
	  "ItemImage" => $imageSource, 
      "WHStock" => $warehouseStock,
	  "TotalStock" => $totalStock,	
	  "ItemPrice" => $itemPrice,	
	  );
	  
	  echo json_encode($resfulldate);die;
		*/
}
else
{
	echo 9;
}
?>

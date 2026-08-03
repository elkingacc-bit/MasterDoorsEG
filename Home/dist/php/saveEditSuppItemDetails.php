<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	//echo "test -> ";  
	$suppOrderId = $_POST['orderSupplyRIDDet2'];
	$selectItemRId = $_POST['DetItemRID2'];
	$suppOrderType = $_POST['DetSupplyOrderType2'];
	$jobRowId = $_POST['orderSupplyRIDDet2'];
	$SupplyLoopQTY = $_POST['loopQty'];
	
	$allDoorsNum = implode(',',$_POST['DorNo']);
	$allHandle = implode(',',$_POST['Handle']);
	$allOverLap = implode(',',$_POST['OverLap']);
	$allRAL = implode(',',$_POST['RAL']);
	
	
	$sqlCheckItemDetails = "SELECT `handling`, `doorNumber`, `Overlap`, `itemRal` FROM 
			`itemoffer` WHERE `id` = $selectItemRId";
			$queryCheckItemDetails=mysqli_query($link,$sqlCheckItemDetails)or die("ERROR :05-AU_AU_S");
			$resCheckItemDetails= mysqli_fetch_assoc($queryCheckItemDetails);	
			
			if($resCheckItemDetails['handling'] == "")
			{
				$updateHandle = $allHandle;
			}
			else
			{
				$updateHandle = $resCheckItemDetails['handling'].",". $allHandle;
			}
			
			if($resCheckItemDetails['doorNumber'] == "")
			{
				$updateDoorNo = $allDoorsNum;
			}
			else
			{
				$updateDoorNo = $resCheckItemDetails['doorNumber'].",". $allDoorsNum;
			}
			
			if($resCheckItemDetails['Overlap'] == "")
			{
				$updateOverLap = $allOverLap;
			}
			else
			{
				$updateOverLap = $resCheckItemDetails['Overlap'].",". $allOverLap;
			}
			
			if($resCheckItemDetails['itemRal'] == "")
			{
				$updateRal = $allRAL;
			}
			else
			{
				$updateRal = $resCheckItemDetails['itemRal'].",". $allRAL;
			}
	
	
		$sqlGetSuppOID = "SELECT `OIId` FROM `supporderitems` 
			 WHERE `ItemRowId` = $selectItemRId AND `SOIdRef` = $suppOrderId";
			$queryGetSuppOID = mysqli_query($link,$sqlGetSuppOID)or die("ERROR :03-ANJ_GCN_S");
			$resGetSuppOID = mysqli_fetch_assoc($queryGetSuppOID);
			$suppOrderItemRID = $resGetSuppOID['OIId'];
	
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
		
		for($i = 0; $i < ($SupplyLoopQTY - 1); $i++)
		{
			
			$sqlEditmsDetails = "UPDATE `suppitemdetails` SET `oiidRef` = $suppOrderItemRID, 
			`ral` = '".$_POST['RAL'][$i]."', `doornumber` = '".$_POST['DorNo'][$i]."',
			`handlingSupp` = '".$_POST['Handle'][$i]."', `overlap` = '".$_POST['OverLap'][$i]."'
			WHERE `id` = '".$_POST['rowId'][$i]."'";
			mysqli_query($link,$sqlEditmsDetails)or die("ERROR :08-AU_AU_S");
			
		}
		
		$sqlUpdateSupplyItem = "UPDATE `supporderitems` SET `Handle` = '$allHandle', `doornumber`
			= '$allDoorsNum', `Overlap` = '$allOverLap', `RAL` = '$allRAL' WHERE `OIId` = 
			$suppOrderItemRID";
			mysqli_query($link,$sqlUpdateSupplyItem)or die("ERROR :06-AU_AU_S");
			
			$sqlUpdateOfferItem = "UPDATE `itemoffer` SET `handling` = '$updateHandle', `doorNumber`
			= '$updateDoorNo', `Overlap` = '$updateOverLap', `itemRal` = '$updateRal' WHERE `id` = 
			$selectItemRId";
			mysqli_query($link,$sqlUpdateOfferItem)or die("ERROR :09-AU_AU_S");

			$action="Edit RAL, Handling, Overlap and Door Number for $SupplyLoopQTY in order: 
			$resGetOrderData[OrderNumber] | Project: $resGetOfferNum[projectName]";
			$logRef=9;	
			include_once("aduLog.php");
			
			echo 1;
			exit();	
		//}
		
}
else
{
	echo 9;
}
?>

<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
//echo "test ->  ";
$jobRowId = $_POST['JobRowIdRef'];
$itemRowId = $_POST['AsgnItemRID'];
$AsKitID = $_POST['AsKitID'];
$AsKitName = $_POST['AsKitName'];

	$sqlGetJobNum = "SELECT `localref`, `offerValue`, `projectName` FROM `job` WHERE `jobId` = $jobRowId";
	$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :01-ANJ_GCN_S");
	$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);
	
	$sqlGetItemName = "SELECT `itemname`, `itemqty` FROM `itemoffer` WHERE `id` = $itemRowId";
	$queryGetItemName = mysqli_query($link,$sqlGetItemName)or die("ERROR :02-ANJ_GCN_S");
	$resGetItemName = mysqli_fetch_assoc($queryGetItemName);
	
	$doorQTY = $resGetItemName['itemqty'];
	$itemName = $resGetItemName['itemname'];
	
	$offerVal = $resGetJobNum['offerValue'];
	$jobLocalRef = $resGetJobNum['localref'];
	$Project = $resGetJobNum['projectName'];
	
	$sqlGetItemRef = "SELECT `itemRef` FROM `itemoffer` WHERE `jobref` = $jobRowId ORDER BY `itemRef` 
	DESC LIMIT 1";
	$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :03-ANJ_GCN_S");
	$resGetItemRef = mysqli_fetch_assoc($queryGetItemRef);
		
		$itemRef = $resGetItemRef['itemRef'];
	

	$sqlGetKitItems = "SELECT `descripcode`, `Quantity` FROM `kitscomponents` WHERE `assemplyRowId` = $AsKitID";
	$queryGetKitItems = mysqli_query($link,$sqlGetKitItems)or die("ERROR :04-ANJ_GCN_S");
	while($resGetKitItems = mysqli_fetch_assoc($queryGetKitItems))
	{
		$itemQTY = ($resGetKitItems['Quantity'] * $doorQTY);
		
		$sqlGetItemPrice = "SELECT `sales` FROM `lookupstock` WHERE `descriptioncode` 
		= $resGetKitItems[descripcode]";
		$queryGetItemPrice = mysqli_query($link,$sqlGetItemPrice)or die("ERROR :05-ANJ_GCN_S");
		if(mysqli_num_rows($queryGetItemPrice) == 0)
		{
			$itemPrice = 0;
			$totalItemPrice = 0;
		}
		else
		{
			$resGetItemPrice = mysqli_fetch_assoc($queryGetItemPrice);
			
			$itemPrice = $resGetItemPrice['sales'];
			$totalItemPrice = round($itemQTY * $itemPrice);
		}
		
		if($itemPrice == 0)
		{
			$sqlGetPrice = "SELECT `overprice`, `salesfactor` FROM `stockitems` 
			WHERE `description` = $resGetKitItems[descripcode] ";
			$queryGetPrice = mysqli_query($link,$sqlGetPrice)or die("ERROR :02-ANJ_GCN_S");
			$resGetPrice = mysqli_fetch_assoc($queryGetPrice);
			if($resGetPrice['overprice'] > 0 && $resGetPrice['salesfactor'] > 0)
			{
				$itemPrice = round($resGetPrice['overprice'] + 
				($resGetPrice['overprice'] * $resGetPrice['salesfactor']));
				$totalItemPrice = round($itemQTY * $itemPrice);
				
			}
		}
		
		$sqlAddHWStock = "INSERT INTO `offerproperties`(`descripcode`, `descripquantity`, `unitPrice`, 
		`totalprice`, `jobidref`, `jobproref`, `offerItemRef`, `ioidref`) VALUES ($resGetKitItems[descripcode], 
		$itemQTY, '$itemPrice', '$totalItemPrice', 	$jobRowId, 0 , '$itemRef', $itemRowId)";
		mysqli_query($link,$sqlAddHWStock)or die("ERROR :06-ANJ_GCN_S");
	}
	
	$sqlGetTotalHW = "SELECT SUM(`totalprice`) AS HWTotal FROM `offerproperties` WHERE `ioidref` = $itemRowId";
	$queryGetTotalHW = mysqli_query($link,$sqlGetTotalHW)or die("ERROR :07-ANJ_GCN_S");
	$resGetTotalHW = mysqli_fetch_assoc($queryGetTotalHW);
	
	$totalHWVal = $resGetTotalHW['HWTotal'];
	$newOfferVal = round($offerVal + $totalHWVal);
	
	//$sqladdHWRef = "UPDATE `itemoffer` SET `itemRef` = '$itemRef' WHERE `id` = $itemRowId";
	//mysqli_query($link,$sqladdHWRef)or die("ERROR :08-ANJ_GCN_S");
	
	$sqlUpdateOfferVal = "UPDATE `job` SET `offerValue` = '$newOfferVal' WHERE `jobId` = $jobRowId";
	mysqli_query($link,$sqlUpdateOfferVal)or die("ERROR :09-ANJ_GCN_S");
	
	
$action="Assign Assembly Kit ($AsKitName) for Item : $itemName For Job No: $jobLocalRef Project: $Project";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
?>

